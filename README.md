# TmamApp
 A Laravel and React.js-based application integrating Zoho to manage expenses with multi-currency support. Enables authentication, syncing of Chart of Accounts, Contacts, Currencies, Expenses and receipts between Zoho and the app.

 # Laravel + React + Inertia Setup Guide
 This guide will help you set up and run the Laravel application with React, Inertia, and Zoho API integration. Follow these steps to get your environment ready and ensure the coding process is set up correctly.

## Prerequisites

Before setting up the project, make sure you have the following software installed:

- **PHP Version**: PHP 8.1+ (required for Laravel 10)
- **MySQL Version**: 8+ or (MariaDB 11+)
- **Composer Version**: 2.2+ (latest recommended)
- **Node.js Version**: 18.x+ (required for frontend asset compilation)
- **NPM Version**: 10.x+ (latest recommended for Node.js 18.x)
- **Apache Version**: 2.4+ (or an alternative web server like Nginx)

## Environmental Setup
### Clone the repository to your local machine:
```bash
git clone https://github.com/thanjeys/TmamApp.git
cd TmamApp
```
### Set Up Laravel (Backend) - Install PHP dependencies using Composer:
```bash
composer install
```
### Copy the .env.example file to .env:
```bash
cp .env.example .env
```
###Configure the environment settings in the .env file
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zohosocialite
DB_USERNAME=root
DB_PASSWORD=
```
###Generate the application key for Laravel
```bash
php artisan key:generate
```
###Migrate the Database
```bash
php artisan migrate
```
###Set Up React (Frontend) - Install JavaScript dependencies using npm
```bash
npm install
```
##Set Up Zoho API Integration
https://www.zoho.com/accounts/protocol/oauth-setup.html
Create OAuth with ServerBased Application
Configure Zoho API credentials in the .env file (client ID, client secret, Callback and OAuth tokens).
Ensure that the Zoho API SDK (or Guzzle HTTP client) is set up correctly in the backend to interact with Zoho services.
.env file
```bash
ZOHO_CLIENT_ID="1000.R9XAGNKGVXXXXXXX"
ZOHO_CLIENT_SECRET="XXXXXXXXX5071307579019"
ZOHO_REDIRECT_URI="http://127.0.0.1:8000/auth/zoho/callback"
ZOHO_API_ENDPOINT="https://www.zohoapis.com/books/v3/"
QUEUE_CONNECTION="database"
```
##Test the Application
Run the Laravel Development Server
```bash
php artisan serve
```
Run the Laravel Development Server
```bash
npm run dev
```
Run Queue Worker (while Expense & Contacts Syncing)
```bash
php artisan queue:work
```

Open your browser and navigate to http://127.0.0.1:8000/ to access the application.
Ensure that both the backend and frontend are working as expected, including the Zoho API integration.
 
