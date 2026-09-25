# AI Travel Product Management System — Backend

A Laravel REST API for managing travel products with OpenAI-powered product generation and natural-language product search.

## Overview

The backend provides the core business logic and API for the AI Travel Product Management System.

It handles:

* User authentication using Laravel Sanctum
* Travel product CRUD operations
* Product validation
* Product expiry handling
* Dashboard statistics
* AI-powered travel product generation
* AI-powered natural-language product search
* Structured filtering through MySQL

The frontend application is built separately using React.

---

## Tech Stack

* **PHP:** 8.3+
* **Laravel:** 13
* **MySQL:** 8+
* **Laravel Sanctum:** API authentication
* **OpenAI API:** AI product generation and natural-language search
* **Composer:** PHP dependency management

---

## Main Features

### Authentication

* User registration
* User login
* Bearer token authentication using Laravel Sanctum
* Logout and token revocation
* Protected API endpoints

### Product Management

Users can:

* Create products
* View products
* View individual product details
* Update products
* Delete products

Each product contains:

* Product name
* Destination
* Category
* Description
* Highlights
* Inclusions
* Tags
* Price
* Inventory count
* Valid from
* Valid until
* Active / inactive status

Expired products are automatically excluded from the main product listing and AI search results.

### AI Product Generation

Users can provide a natural-language description of the travel product they want to create.

OpenAI generates:

* Product name
* Destination
* Category
* Description
* Highlights
* Inclusions
* Tags

The generated information is returned to the frontend for review before the product is saved.

Price, inventory, validity dates and status are entered separately by the user.

### AI Natural-Language Search

Users can search using natural language, for example:

```text
Show active family packages in Colombo
```

or:

```text
Show products below LKR 10000
```

The backend sends the search request to OpenAI to interpret the user's intent into structured filters.

Laravel then applies those filters to the MySQL database.

The architecture is:

```text
Natural-language query
        ↓
      OpenAI
        ↓
Structured filters
        ↓
Laravel business logic
        ↓
      MySQL
        ↓
Filtered products
```

OpenAI interprets the user's intent, while Laravel applies the actual database and business rules. MySQL remains the source of truth for product data.

Expired products are excluded from AI search results.

### Dashboard

The dashboard API provides:

* Total products
* Active products
* Expired products

---

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AIController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── ProductController.php
│   ├── Requests/
│   │   ├── LoginRequest.php
│   │   ├── RegisterRequest.php
│   │   ├── StoreProductRequest.php
│   │   └── UpdateProductRequest.php
│   └── Resources/
│       └── ProductResource.php
│
├── Models/
│   ├── Product.php
│   └── User.php
│
└── Services/
    ├── OpenAIService.php
    ├── ProductAIService.php
    └── ProductSearchService.php

database/
└── migrations/

routes/
└── api.php
```

---

## Installation

### 1. Clone the repository

```bash
git clone <https://github.com/manooabi/AI-Travel-Product-Management-System---Backend.git>
cd AI-Travel-Product-Management-System---Backend
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

Copy:

```text
.env.example
```

to:

```text
.env
```

Configure the database and OpenAI API key.

Example:

```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_travel_product_management_system
DB_USERNAME=root
DB_PASSWORD=

OPENAI_API_KEY=
```

**Do not commit the `.env` file or expose the OpenAI API key.**

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Run database migrations

```bash
php artisan migrate
```

Alternatively, the provided SQL database script can be imported into MySQL.

### 6. Start the Laravel server

```bash
php artisan serve
```

The API will be available at:

```text
http://localhost:8000
```

---

## API Endpoints

### Authentication

| Method | Endpoint        | Description            |
| ------ | --------------- | ---------------------- |
| POST   | `/api/register` | Register a user        |
| POST   | `/api/login`    | Login                  |
| POST   | `/api/logout`   | Logout                 |
| GET    | `/api/user`     | Get authenticated user |

### Products

| Method    | Endpoint             | Description    |
| --------- | -------------------- | -------------- |
| GET       | `/api/products`      | List products  |
| POST      | `/api/products`      | Create product |
| GET       | `/api/products/{id}` | View product   |
| PUT/PATCH | `/api/products/{id}` | Update product |
| DELETE    | `/api/products/{id}` | Delete product |

### AI

| Method | Endpoint                    | Description                            |
| ------ | --------------------------- | -------------------------------------- |
| POST   | `/api/ai/products/generate` | Generate travel product content        |
| POST   | `/api/ai/search`            | Search products using natural language |

### Dashboard

| Method | Endpoint         | Description              |
| ------ | ---------------- | ------------------------ |
| GET    | `/api/dashboard` | Get dashboard statistics |

Protected endpoints require:

```text
Authorization: Bearer <token>
```

---

## Validation and Business Rules

The API validates product input before saving.

Important rules include:

* Product name, destination, category and description are required.
* Price cannot be negative.
* Inventory count cannot be negative.
* Valid until must be equal to or later than valid from.
* Status must be either `active` or `inactive`.
* Expired products are excluded from product listings.
* Expired products are excluded from AI search results.

---

## OpenAI Integration

The application uses the OpenAI Responses API.

The OpenAI API key is loaded through:

```env
OPENAI_API_KEY=
```

The key is never stored directly in application source code.

Structured JSON Schema responses are used for AI product generation and search interpretation so that the Laravel application receives predictable data.

---

## Frontend

The React frontend is maintained in a separate repository.

The frontend communicates with this backend through the REST API.

Configure the frontend API URL using:

```env
VITE_API_URL=http://localhost:8000/api
```

---

## Database

The application uses MySQL.

The main application table is:

```text
products
```

The product table stores the travel product information required by the assessment.

The Laravel migration files are included in the repository.

A database SQL export is also provided separately with the assessment submission.

---

## Testing

The following application flows were tested during development:

* User registration
* Duplicate registration validation
* User login
* Invalid login handling
* Protected API access
* Logout and token revocation
* Product creation
* Product listing
* Product details
* Product update
* Product deletion
* Product validation
* Expired product exclusion
* AI product generation
* AI natural-language search
* Dashboard statistics

The frontend production build also completes successfully using:

```bash
npm run build
```

---

## Security Notes

* `.env` should not be committed.
* OpenAI API credentials should be stored in environment variables.
* Protected endpoints use Laravel Sanctum bearer tokens.
* User passwords are securely hashed by Laravel.
* User input is validated through Laravel Form Requests.

---

## Assessment

This project was developed as a full-stack technical assessment demonstrating:

* Laravel REST API development
* React frontend development
* MySQL database design
* Authentication
* CRUD operations
* OpenAI API integration
* Structured AI responses
* Natural-language search
* Business-rule enforcement
* Responsive UI development
