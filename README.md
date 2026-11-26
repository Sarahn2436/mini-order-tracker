# Mini Order Tracker

A small order tracker with a staff and customer view.

## Features

### Staff
* **Dashboard Page**: Summarises the orders database.
* **Customers Page**: Shows customer names, emails, and companies. Allows you to edit, delete, or view them (the view option displays their specific orders etc.).
* **Orders Page**: Displays the order number, customer, product, quantity, unit price, status, and due date.
* **Customer Service**: A live function where staff can talk directly to customers.

### Customers
* **Dashboard Page**: Lets customers look at products and browse product categories (Fashion, Technology, Cards, Misc). Takes you to the product view page.
* **Customer Orders Page**: Lets customers view items they have ordered.
* **Product view page**: lets you view a product's details.
* **Checkout**: Lets customers enter their payment details to buy products. Uses Stripe API.
* **Account Settings**: Lets customers delete their account, change the language, and change their details.
* **Basket** lets customers add things to basket and see what's in their basket +total price
* **Customer Service**: A live function where staff can talk directly to customers.

## Installation

### Prerequisites
Make sure you have the following installed on your local environment:
* PHP
* Composer
* Database engine (e.g., SQLite, MySQL)

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

When the server boots up, open your browser and navigate to `http://localhost:8000` (or the specific port provided by Artisan).
