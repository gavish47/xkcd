# XKCD Comic Email Subscription System

A PHP-based email subscription system that delivers daily XKCD comics to subscribers via email with verification and unsubscribe functionality.

## Features

- **Email Verification**: Secure subscription process with 6-digit verification codes
- **Daily Comic Delivery**: Automated delivery of random XKCD comics via email
- **Unsubscribe System**: Easy unsubscription with email confirmation
- **Modern Interface**: Clean, responsive web design with Bootstrap styling
- **File-based Storage**: Simple text file storage for email addresses
- **SMTP Integration**: Uses PHPMailer for reliable email delivery
- **Cron Job Support**: Automated daily comic delivery scheduling

## Screenshots



![Screenshot 2025-07-03 141336](https://github.com/user-attachments/assets/852fc4d7-5a3b-466d-84fb-0d9c4c7d1718)
![Screenshot 2025-07-03 141312](https://github.com/user-attachments/assets/088a5026-3c70-496c-9eca-30e4b03a5109)
![Screenshot 2025-07-03 141402](https://github.com/user-attachments/assets/02ec7612-4fdc-405f-8fdd-81bbd86374a1)

![Screenshot 2025-07-03 141459](https://github.com/user-attachments/assets/52ea3801-baf9-4ef8-b19f-e66979edab97)





## Installation

### Prerequisites

- PHP 8.0 or higher
- Composer
- SMTP email account (Gmail recommended)

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/gavish47/xkcd.git
   cd xkcd/xkcd-gavish47/src
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment variables**
   Set up the following environment variables:
   ```bash
   SMTP_USERNAME=your-email@gmail.com
   SMTP_PASSWORD=your-app-password
   ```

   For Gmail users:
   - Enable 2-factor authentication
   - Generate an App Password from Google Account Security settings
   - Use the App Password (not your regular password) for SMTP_PASSWORD

4. **Start the server**
   ```bash
   php -S localhost:5000
   ```

5. **Set up automated comic delivery (optional)**
   ```bash
   chmod +x setup_cron.sh
   ./setup_cron.sh
   ```

## Usage

### Subscription Process

1. Visit the application homepage
2. Enter your email address
3. Check your email for a 6-digit verification code
4. Enter the verification code to complete subscription
5. You'll receive daily XKCD comics via email

### Unsubscribe Process

1. Click the unsubscribe link in any comic email
2. Enter your email address on the unsubscribe page
3. Check your email for a verification code
4. Enter the code to confirm unsubscription

### Manual Comic Delivery

To manually send comics to all subscribers:
```bash
php cron.php
```

## File Structure

```
src/
├── index.php              # Main subscription interface
├── unsubscribe.php        # Unsubscription interface
├── functions.php          # Core application functions
├── cron.php              # Daily comic delivery script
├── setup_cron.sh         # Cron job setup script
├── registered_emails.txt # Subscriber email storage
├── composer.json         # PHP dependencies
└── vendor/               # Composer dependencies
```

## API Integration

The system integrates with the official XKCD API:
- **Endpoint**: `https://xkcd.com/{comic_id}/info.0.json`
- **Comic Range**: Random selection from comics 1-2800
- **Format**: HTML email with embedded images

## Email Templates

### Verification Email
- **Subject**: "Your Verification Code"
- **Content**: 6-digit numeric code
- **Sender**: Configured SMTP username

### Comic Email
- **Subject**: "Your XKCD Comic"
- **Content**: HTML formatted with comic image and unsubscribe link
- **Frequency**: Daily (via cron job)

### Unsubscribe Confirmation
- **Subject**: "Confirm Un-subscription"
- **Content**: 6-digit verification code
- **Purpose**: Secure unsubscription process

## Configuration

### SMTP Settings

The system uses Gmail SMTP by default:
- **Host**: smtp.gmail.com
- **Port**: 587
- **Security**: STARTTLS
- **Authentication**: Required

### Cron Job Schedule

The default setup runs daily at 9:00 AM:
```bash
0 9 * * * /usr/bin/php /path/to/cron.php
```

## Security Features

- **Email Validation**: Server-side email format validation
- **Verification Codes**: 6-digit random numeric codes
- **Session Management**: Secure session handling for verification
- **File Locking**: Prevents concurrent file access issues
- **Input Sanitization**: All user inputs are properly sanitized

## Development

### Testing Email Functionality

1. Ensure SMTP credentials are properly configured
2. Test verification email sending:
   ```bash
   # Subscribe with a test email
   # Check application logs for email delivery status
   ```

3. Test comic delivery:
   ```bash
   php cron.php
   # Check cron_errors.log for delivery status
   ```

### Debugging

- Check `cron_errors.log` for email delivery issues
- Enable SMTP debugging by setting `$mail->SMTPDebug = 2` in functions.php
- Verify environment variables are properly set

## Requirements Compliance

This implementation follows the project requirements:

✅ **Email verification with 6-digit codes**  
✅ **File-based email storage (registered_emails.txt)**  
✅ **HTML formatted emails (not JSON)**  
✅ **Unsubscribe functionality with verification**  
✅ **XKCD API integration**  
✅ **Automated cron job setup**  
✅ **Required form field names and IDs**  
✅ **Proper email subject and body formats**  

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the error logs
3. Create an issue on GitHub

## Troubleshooting

### Common Issues

**SMTP Authentication Failed**
- Verify SMTP_USERNAME is a valid email address
- Ensure SMTP_PASSWORD is an App Password (not regular password)
- Check that 2-factor authentication is enabled for Gmail

**No Comics Received**
- Verify email address is in registered_emails.txt
- Check cron_errors.log for delivery issues
- Test manual delivery with `php cron.php`

**Verification Code Not Received**
- Check spam/junk folder
- Verify SMTP configuration
- Test with a different email provider

## Changelog

- **July 03, 2025**: Initial release with full functionality
- **July 03, 2025**: Email system configuration and testing
- **July 03, 2025**: XKCD comic delivery system implementation
- **July 03, 2025**: Subscription and unsubscription workflows completed
