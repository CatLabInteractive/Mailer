# Mailer
A bit of glue between Neuron and various mail providers.

## Requirements
- PHP 7.4 or newer (tested on 7.4 and 8.5)
- `catlabinteractive/neuron` ^3.2
- PHPMailer 6.9+ or 7

## Upgrading from 3.x
4.0 moves from PHPMailer 5.2 (which does not run on PHP 8.5) to PHPMailer 6/7.
The public API of this package is unchanged. Only code that used PHPMailer's
global `PHPMailer` / `phpmailerException` classes directly needs to switch to
`PHPMailer\PHPMailer\PHPMailer` / `PHPMailer\PHPMailer\Exception`.

## Tests
```bash
composer install
docker run -d --name mailpit -p 1025:1025 -p 8025:8025 \
  -e MP_SMTP_AUTH_ACCEPT_ANY=1 -e MP_SMTP_AUTH_ALLOW_INSECURE=1 axllent/mailpit
MAILPIT_HOST=127.0.0.1 vendor/bin/phpunit
```
Without `MAILPIT_HOST` the SMTP tests are skipped.
