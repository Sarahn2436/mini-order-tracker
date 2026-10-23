# mini-order-tracker

A small order tracker featuring a summary dashboard, a customer database, and an order tracking database.

## Features

* **Dashboard Page**: Summarises the orders database.
* **Customers Page**: Shows customer names, emails, and companies. Allows you to edit, delete, or view them (the view option displays their specific orders etc.).
* **Orders Page**: Displays the order number, customer, product, quantity, unit price, status, and due date.

## Installation

### Prerequisites
Make sure you have the following installed on your local environment:
* PHP
* Composer
* Database engine (SQLite, MySQL, etc.)

### Setup Instructions

1. Go to your project directory.
2. Duplicate the environment configuration file:
   ```bash
   cp .env.example .env
   ```
3. Open the `.env` file and ensure your database credentials match your local setup configuration.
4. Install dependencies:
   ```bash
   composer install
   ```
5. Generate the application encryption key:
   ```bash
   php artisan key:generate
   ```
6. Run the database migrations and testing seeders:
   ```bash
   php artisan migrate --seed
   ```
7. Boot the application server:
   ```bash
   php artisan serve
   ```

When the server boots up, open your browser and go to http://localhost
