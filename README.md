# Avera Checkout PHP

## Overview
Avera Checkout PHP is a simple and secure checkout solution that integrates with PayPal for processing payments. This project provides a user-friendly interface for customers to make purchases and handles the backend processes for order creation and payment capture.

## Project Structure
```
# Avera Checkout PHP

Simple, secure PayPal checkout with a polished UI, `.env`-based configuration, and local transaction logging.

## Table of Contents
- Overview
- Highlights
- Project Structure
- Requirements
- Setup
- Flow
- Test via PowerShell
- Tips
- License

## Overview
This app renders a modern checkout UI, creates/captures PayPal orders via PHP endpoints, and logs successful transactions to `storage/transactions.txt`. Secrets remain in `config/.env`.

## Highlights
- Entry point: `public/index.php` injects `PAYPAL_CLIENT_ID` from `.env`.
- Endpoints: `src/paypal/create-order.php` and `src/paypal/capture-order.php` (Sandbox).
- Logging: successful captures append to `storage/transactions.txt`.
- Autoload: PSR-4 via Composer with `Avera\Utils\Response`.

## Project Structure
```text
composer.json
config/
   .env               # not committed
   .env.example       # sample env
   bootstrap.php      # autoload + dotenv
public/
   index.php          # main UI
   index.html         # legacy (do not use in prod)
   images/
src/
   paypal/
      create-order.php
      capture-order.php
   utils/
      Response.php
storage/
   transactions.txt   # runtime log
vendor/
.gitignore
```

## Requirements
- PHP 7.4+ or 8.0+
- XAMPP with Apache and `ext-curl`
- Composer
- PayPal Sandbox client ID and secret

## Setup
```powershell
cd C:\xampp\htdocs\avera-checkout-php
composer install
composer dump-autoload
```
Create `config/.env` from `config/.env.example`:
```env
PAYPAL_CLIENT_ID=your_sandbox_client_id
PAYPAL_CLIENT_SECRET=your_sandbox_client_secret
APP_ENV=production
APP_DEBUG=false
```
Start Apache, then open:
```
http://localhost/avera-checkout-php/public/index.php
```

## Flow
- `index.php` injects client ID into the PayPal SDK.
- UI calls:
   - `../src/paypal/create-order.php` with `{ amount, currency }`.
   - `../src/paypal/capture-order.php?order_id=...` (POST).
- On capture, a log line is appended:
```text
YYYY-MM-DDTHH:MM:SSZ	ORDER_ID	TRANSACTION_ID
```

## Test via PowerShell
```powershell
# Create Order
Invoke-WebRequest -Uri http://localhost/avera-checkout-php/src/paypal/create-order.php -Method POST -ContentType 'application/json' -Body '{"amount":"850.00","currency":"PHP"}'

# Capture Order
Invoke-WebRequest -Uri http://localhost/avera-checkout-php/src/paypal/capture-order.php?order_id=ORDER_ID -Method POST
```

## Tips
- Do not commit `config/.env`.
- Place your logo at `public/images/logo.png` (fallback `public/Logo.png`).
- Sandbox URLs are active; switch to live API when ready.

## License
MIT

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd avera-checkout-php
   ```
3. Install dependencies using Composer:
   ```
   composer install
   ```

## Configuration
1. Rename the `.env.example` file to `.env` and fill in your API keys and other sensitive information.
2. Ensure that the `config/.env` file is not publicly accessible.

## Usage
- Open `public/index.html` in your web browser to access the checkout page.
- Follow the instructions on the page to make a purchase using PayPal.

## Security
- Keep the `.env` file secure and do not expose it in public repositories.
- Ensure that your server is configured to prevent access to sensitive files.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.