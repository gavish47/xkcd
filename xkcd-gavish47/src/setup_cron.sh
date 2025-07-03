#!/bin/bash
CRON_LINE="0 9 * * * php $(pwd)/cron.php"
(crontab -l 2>/dev/null; echo "$CRON_LINE") | crontab -
echo "✅ CRON job set to run daily at 9 AM!"
