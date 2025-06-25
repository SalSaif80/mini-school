# LogVault Integration Setup

## Required Environment Variables

Add the following variables to your `.env` file to enable LogVault integration:

```env
# LogVault Integration Settings
LOG_API_URL=https://your-logvault-domain.com
LOG_API_TOKEN=your_sanctum_token_here
```

## Configuration Steps

1. **Set LogVault API URL**: Replace `https://your-logvault-domain.com` with your actual LogVault domain
2. **Generate API Token**: Create a Sanctum token in your LogVault system for authentication
3. **Configure IP Whitelist**: Ensure your Mini School server IP is whitelisted in LogVault
4. **Test Connection**: Run the command manually to test the integration

## Available Commands

### Send Logs to LogVault
```bash
php artisan logs:send-to-vault
```
This command sends all unsent activity logs from the last 30 days to LogVault.

### Clean Old Activity Logs
```bash
php artisan activitylog:clean
```
This command removes activity logs older than 30 days (runs monthly).

## Scheduled Tasks

The system automatically runs:
- **Hourly**: Send logs to LogVault (`logs:send-to-vault`)
- **Monthly**: Clean old activity logs (`activitylog:clean`)

## Security Features

- **Token Authentication**: Uses Laravel Sanctum token for API authentication
- **IP Whitelisting**: LogVault should verify the sender's IP address
- **Duplicate Prevention**: Tracks sent logs to prevent duplicate submissions
- **Error Handling**: Comprehensive logging of failed attempts
- **Timeout Protection**: 30-second timeout for API requests

## Data Structure

Each log entry sent to LogVault includes:
- User information (type, id)
- Event type (created, updated, deleted)
- Affected model (type, id)
- Old and new values
- Request metadata (URL, IP, User Agent)
- Timestamp and source system identifier

## Troubleshooting

1. **Check Environment Variables**: Ensure `LOG_API_URL` and `LOG_API_TOKEN` are set
2. **Verify Token**: Test the token with LogVault API directly
3. **Check Logs**: Review Laravel logs for detailed error messages
4. **Network Connectivity**: Ensure the server can reach LogVault API
5. **IP Whitelist**: Verify your server IP is allowed in LogVault 
