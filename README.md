## About LaravelJobBoard API

A RESTful API for a job board platform supporting job seekers and company users. Built using Laravel 12 and Sanctum for authentication.

## Features

- **Authentication**
  - Job seeker and company registration/login/logout
- **Job Postings**
  - Company users can create, update, delete, and list job postings
- **Job Applications**
  - Job seekers can apply to jobs, view and delete applications
  - Company users can view received applications and update statuses
- **Role-Based Access Control**
  - Middleware-protected routes for job seekers and company users

---

## Requirements

- PHP >= 8.2
- Composer
- Laravel 12
- MySQL or PostgreSQL

---

## Setup Instructions

1. **Clone the repository:**

   ```bash
   git clone https://github.com/ChepkemoiPeris/LaravelJobBoardApi.git
   cd LaravelJobBoardApi
   ```
2. **Install dependencies:**

```bash
composer install
```
3. **Copy .env or rename .env.example to .env:**
```bash
cp .env.example .env 
```
4. **Create a database:**
 
Create a new MySQL database named laraveljobboard or any name of your choice.

Then update the following lines in your .env file to match your database setup:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraveljobboard
DB_USERNAME=root
DB_PASSWORD=
```
 
5. **Run migrations:**

```bash
php artisan migrate
```
6. **Seed the database(Optional):**
 
```bash
php artisan db:seed
``` 
7. **Start the development server:**

```bash
php artisan serve
```


### API Access

Base URL: http://127.0.0.1:8000/api

Authentication: Token-based via Laravel Sanctum

Routing: See postman_collection/LaravelJobBoardPeris.postman_collection.json for postman collection you can use for test 

### Test users after seeding

// Job Seeker      
email:johndoe@example.com
password: password

// Company User        
email: janedoe@example.com 
password: password 

### Rate Limiting
 
Rate limiting is applied at the route level using Laravel’s throttle middleware to help prevent abuse:

Job Seeker

POST /jobseeker/user/register → 5 requests per minute

POST /jobseeker/user/login → 5 requests per minute

POST /jobseeker/jobs/apply/{job} → 3 requests per minute

Company

POST /company/user/register → 5 requests per minute

POST /company/user/login → 5 requests per minute

POST /company/store → 5 requests per minute

POST /company/jobs/store → 5 requests per minute

All other routes fall under Laravel’s default global limit of 60 requests per minute per IP.

### AI tools used 

AI assistance, Specifically, OpenAI’s ChatGPT (GPT-4) was used for: 
Creating documentation - README
Fixing few code errors