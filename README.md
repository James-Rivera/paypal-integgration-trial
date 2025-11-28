# Avera Checkout PHP

## Overview
Avera Checkout PHP is a simple and secure checkout solution that integrates with PayPal for processing payments. This project provides a user-friendly interface for customers to make purchases and handles the backend processes for order creation and payment capture.

## Project Structure
```
# Avera Checkout PHP

## Overview
Avera Checkout PHP is a simple and secure checkout solution that integrates with PayPal for processing payments. This project provides a polished UI, server-side endpoints for order creation and capture, and logs successful transactions locally.

## Key Changes
- Entry point is now `public/index.php` (not `index.html`). This allows injecting the PayPal client ID from `.env` without exposing it in source.
- Environment variables are loaded via `config/bootstrap.php` using `vlucas/phpdotenv`.
- PayPal endpoints (`src/paypal/create-order.php`, `src/paypal/capture-order.php`) use the Sandbox API and read credentials from `.env`.
- Successful captures append a record to `storage/transactions.txt` with timestamp, order id, and transaction id.
- `Avera\Utils\Response` namespace aligns with Composer PSR-4 autoload.

## Project Structure
```
avera-checkout-php
├── composer.json
├── config
│   ├── .env                    # Environment variables (NOT committed)
│   ├── .env.example            # Template for env vars
│   └── bootstrap.php           # Autoload + Dotenv + constants
├── public
│   ├── index.php               # Main UI (injects PayPal client ID securely)
│   ├── index.html              # Legacy static page (not used for prod)
│   └── images/
├── src
│   ├── paypal
│   │   ├── create-order.php    # Creates PayPal orders (sandbox)
│   │   └── capture-order.php   # Captures PayPal orders (sandbox, logs txn)
│   └── utils
│       └── Response.php        # JSON response helpers
├── storage
│   └── transactions.txt        # Created at runtime; logs successful captures
├── vendor/                     # Composer dependencies
└── .gitignore                  # Ignores .env, vendor/, logs
```

## Requirements
- PHP 7.4+ (or 8.0+)
- XAMPP (Apache with PHP and `ext-curl` enabled)
- Composer
- PayPal Sandbox account (client ID and secret)

## Setup (Local XAMPP)
1. Copy the project folder to `C:\xampp\htdocs\avera-checkout-php`.
2. Install dependencies and autoload:
```powershell
cd C:\xampp\htdocs\avera-checkout-php
composer install
composer dump-autoload
```
3. Configure environment variables:
   - Create `config/.env` based on `config/.env.example`:
```
PAYPAL_CLIENT_ID=your_sandbox_client_id
PAYPAL_CLIENT_SECRET=your_sandbox_client_secret
APP_ENV=production
APP_DEBUG=false
```
4. Start Apache in XAMPP Control Panel.
5. Open the app:
   - `http://localhost/avera-checkout-php/public/index.php`

## How It Works
- `public/index.php` loads `config/bootstrap.php`, reads `PAYPAL_CLIENT_ID`, and injects it into the PayPal SDK script tag.
- The UI calls:
  - `../src/paypal/create-order.php` (POST JSON `{ amount, currency }`) to create a PayPal order.
  - `../src/paypal/capture-order.php?order_id=...` (POST) to capture the payment.
- On successful capture, a line is appended to `storage/transactions.txt`:
```
<ISO timestamp>\t<order_id>\t<transaction_id>
```
- The success screen displays the Transaction ID from the response.

## Endpoints
- Create Order (Sandbox):
```powershell
Invoke-WebRequest -Uri http://localhost/avera-checkout-php/src/paypal/create-order.php -Method POST -ContentType 'application/json' -Body '{"amount":"850.00","currency":"PHP"}'
```
- Capture Order (Sandbox):
```powershell
Invoke-WebRequest -Uri http://localhost/avera-checkout-php/src/paypal/capture-order.php?order_id=ORDER_ID -Method POST
```

## Environment & Security
- Do NOT commit `config/.env`. The repo includes `.gitignore` entries to prevent accidental commits.
- Use `config/.env.example` to document required variables.
- Client ID is injected server-side; do not hardcode credentials in HTML/JS.

## Troubleshooting
- PayPal SDK not loading: ensure `PAYPAL_CLIENT_ID` is present in `config/.env` and that you are visiting `index.php` (not `index.html`).
- 500 errors on endpoints: verify Composer install succeeded and Apache has `ext-curl` enabled.
- Asset paths: logos should live in `public/images/logo.png` (fallback `public/Logo.png` also supported).
- Sandbox vs live: both endpoints currently use the Sandbox base URL. Switch to live by changing API URLs after testing.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.

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