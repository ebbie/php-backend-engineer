# Laravel Project

## Introduction

This project is designed for managing loan data for New Lending Company, Inc., focusing solely on backend functionality (no frontend component included). 
It provides a REST API for complete CRUD operations on loan records and user data. 
Users can register, log in, and manage loans associated with lenders and borrowers in the system.

## Prerequisites

Make sure you have the following installed:

- **PHP** (version 8.x or later)
- **Composer**
- **MySQL** or **PostgreSQL** for the database
- **Git**

## Getting Started

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
https://github.com/ebbie/php-backend-engineer.git
cd php-backend-engineer/loan-management
```

### 2. Install PHP Dependencies
Use Composer to install PHP dependencies:

```bash
composer install
```

### 3. Copy the Environment File
Create a copy of the .env.example file as .env:

```bash
cp .env.example .env
```

### 4. Configure Database
Edit the .env file to set up your database configuration. For example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 5. Run Migrations
Run the database migrations to set up the database structure:

```bash
php artisan migrate
```
If your project has seeders, you can run them with:

```bash
php artisan db:seed
```
### 6. Run the Development Server
Start the Laravel development server:

```bash
php artisan serve
```
Open http://localhost:8000 in your browser to view the application.

Running Tests
To run tests, you can run them using:

To run tests with Artisan:

```bash
php artisan test
```
Or, to run with PHPUnit directly:

```bash
./vendor/bin/phpunit
```
# 7. Test with Postman

## API Testing Instructions

This guide explains how to test the API endpoints using Postman.

## Authorization Setup
For routes that require authentication, use **Bearer Token** authorization in Postman.
1. Log in using the `/login` endpoint to obtain a token.
2. Copy the token from the login response.
3. For authenticated routes, add it to the Authorization header as `Bearer <token>`.

## API Endpoints

### 7.1. Register User
- **Method**: `POST`
- **URL**: `{{base_url}}/api/register`
- **Body** (JSON):
  ```json
  {
    "name": "John Doe",
    "email": "johndoe@example.com",
    "password": "password",
  }
  ```
  
- **Expected Response**: User information with a success message.

### 7.2. Login User
- **Method**: POST
- **URL**: {{base_url}}/api/login
- **Body** JSON
```json
{
  "email": "johndoe@example.com",
  "password": "password123"
}
```

- **Expected Response**: A token for authentication.

### 7.3. View All Loans (Public)
- **Method**: GET
- **URL**: {{base_url}}/api/loans
- **Expected Response**:  List of all loans.

### 7.4. View Loan by ID (Public)
- **Method**: GET
- **URL**: {{base_url}}/api/loan/{id}
- **Path Variable**: Replace {id} with the loan ID.
- **Expected Response**: Loan details for the given ID.
  
### 7.5. Create Loan (Authenticated)
- **Method**: POST
- **URL**: {{base_url}}/api/loans
- **Authorization**: Bearer Token
-- **Body**: JSON
```json
{
  "amount": 5000,
  "interest_rate": 5,
  "duration_in_months": 12,
  "lender_id": 1,
  "borrower_id": 2
}
```

- **Expected Response**: Details of the created loan.
  
### 7.6. Update Loan by ID (Authenticated)
- **Method**: PUT
- **URL**: {{base_url}}/api/loan/edit/{id}
- **Path Variable**: Replace {id} with the loan ID.
- **Authorization**: Bearer Token
- **Body**: JSON
```json
{
  "amount": 5500,
  "interest_rate": 6,
  "duration_in_months": 10
}
```

- **Expected Response**: Updated loan details.
  
### 7.7. Delete Loan by ID (Authenticated)
- **Method**: DELETE
- **URL**: {{base_url}}/api/loan/{id}
- **Path Variable**: Replace {id} with the loan ID.
- **Authorization**: Bearer Token
- **Expected Response**: Success message confirming deletion.
  
### 7.8. Logout User
- **Method**: POST
- **URL**: {{base_url}}/api/logout
- **Authorization**: Bearer Token
- **Expected Response**: Success message confirming logout.

### Notes
- Replace ```{{base_url}}``` with the actual URL of your application.
