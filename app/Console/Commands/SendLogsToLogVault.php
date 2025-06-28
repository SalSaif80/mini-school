<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use Exception;

class SendLogsToLogVault extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:send-to-vault {--debug} {--test} {--limit=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send logs to external system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // التحقق من الإعدادات
        // if (!env('LOG_API_URL') || !env('LOG_API_TOKEN')) {
        //     $this->error('أضف LOG_API_URL و LOG_API_TOKEN في .env');
        //     return 1;
        // }

        // اختبار الاتصال
        if ($this->option('test')) {
            return $this->testConnection();
        }

        // جلب السجلات
        $logs = $this->getLogs();

        if ($logs->isEmpty()) {
            $this->info('لا يوجد سجلات جديدة');
            return 0;
        }

        // إرسال السجلات
        $this->info("إرسال {$logs->count()} سجل...");

        if ($this->sendLogs($logs)) {
            $this->markAsSent($logs);
            $this->info('تم الإرسال بنجاح ✅');
        } else {
            $this->error('فشل الإرسال ❌');
        }

        return 0;
    }

    private function getLogs()
    {
        $query = Activity::where('created_at', '>=', now()->subDay())
            ->whereNull('properties->sent_to_vault')
            ->orderBy('created_at', 'asc');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        return $query->get();
    }

    private function sendLogs($logs)
    {
        $data = [];

        foreach ($logs as $log) {
            $data[] = [
                'external_log_id' => $log->id,
                'description' => $log->description,
                'causer_type' => $log->causer_type,
                'causer_id' => $log->causer_id,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'project_name' => env('LOG_PROJECT_NAME', 'Laravel'),
                'occurred_at' => $log->created_at->toISOString(),
                'properties' => $log->properties,
                'event' => $log->event,
                'log_name' => $log->log_name,
                'source_system' => env('LOG_PROJECT_NAME', 'Laravel'),
            ];
        }

        if ($this->option('debug')) {
            $this->line(json_encode(['logs' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $response = Http::timeout(30)
            ->withToken(env('LOG_API_TOKEN'))
            ->post(env('LOG_API_URL') . '/api/logs/batch', ['logs' => $data]);

        if ($this->option('debug')) {
            $this->line('Status: ' . $response->status());
            $this->line('Response: ' . $response->body());
        }

        return $response->successful();
    }

    private function markAsSent($logs)
    {
        foreach ($logs as $log) {
            $properties = $log->properties ? $log->properties->toArray() : [];
            $properties['sent_to_vault'] = now()->toISOString();
            $log->update(['properties' => $properties]);
        }
    }

    private function testConnection()
    {
        $this->info('اختبار الاتصال...');

        $response = Http::timeout(10)
            ->withToken(env('LOG_API_TOKEN'))
            ->post(env('LOG_API_URL') . '/api/logs/batch', [
                'logs' => [[
                    'external_log_id' => 99999,
                    'description' => 'connection test',
                    'project_name' => env('APP_NAME', 'Test'),
                    'occurred_at' => now()->toISOString(),
                    'source_system' => 'test',
                ]]
            ]);

        $this->line('Status: ' . $response->status());
        if ($this->option('debug')) {
            $this->line('Response: ' . $response->body());
        }

        return $response->successful() ? 0 : 1;
    }
}
