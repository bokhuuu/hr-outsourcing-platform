# HR Outsourcing Platform

A multi-tenant HR management system that enables HRs to manage multiple companies, handle employee records, process leave requests, track attendance and manage job vacancies

### Multi-Tenancy and Security

-   Each company's data is automatically separated using Global Scopes
-   Roles: HR, Admin, Company Admin, Employee
-   Secure authentication using Laravel Sanctum
-   Role-based authorization middleware

### Service Layer Architecture

-   Clean separation between HTTP layer (controllers) and business logic (services)
-   Reusable across API, Web and CLI contexts

### Observer Pattern

-   Automatic field tracking
-   LeaveRequest and Absence models automatically set `created_by`

### Testing

-   15 Pest tests covering API endpoints
-   Tests follow AAA pattern (Arrange-Act-Assert)
-   Model factories for clean, isolated test data

### Functionality

**Vacancies Management**

-   Public API for listing and viewing job vacancies
-   HR can create and publish vacancies for any company
-   Published/draft status workflow

**Attendance Tracking**

-   Employee daily check-in/check-out
-   Automatic validation: one check-in per day
-   Check-out requires prior check-in
-   Attendance history viewing

**Leave Request System**

-   Employees create leave requests with date ranges
-   Automatic overlap detection - Prevents duplicate/overlapping requests
-   Approval workflow: pending -> approved/rejected
-   HR and Company Admins can approve or reject requests
-   Rejection reason tracking
-   Role-based filtering

**Absence Registration**

-   HR registers employee absences with reasons
-   One absence per employee per day
-   Tracking of who registered the absence

## User Interface

**Employee Self-Service Pages**

-   Role-based dashboards
-   Attendance management (check-in/check-out with history)
-   Leave request creation and tracking
-   View registered absences

**Authentication**

-   Login/logout functionality (Laravel Breeze)
-   Session-based web authentication
-   Token-based API authentication

## Tech Stack

-   **Framework:** Laravel 11
-   **Authentication:** Laravel Breeze + Laravel Sanctum
-   **Database:** MySQL 8.0+
-   **Testing:** Pest PHP
-   **Architecture Patterns:** Service Layer, Observer, Repository (through Eloquent)
-   **API:** RESTful with versioning

## Installation

### Prerequisites

-   PHP 8.2+
-   Composer
-   MySQL 8.0+
-   Node.js and NPM

### Setup Steps

**Clone the repository**

```bash
git clone https://github.com/bokhuuu/hr-outsourcing-platform.git
```

**Install dependencies**

```bash
composer install
npm install
```

**Configure environment**

```bash
cp .env.example .env
php artisan key:generate
```

**Configure database**

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr_outsourcing_platform
DB_USERNAME=root
DB_PASSWORD=
```

**Run migrations and seed database**

```bash
php artisan migrate --seed
```

**Compile frontend assets and start development server**

```bash
# Terminal 1
npm run dev
# Terminal 2
php artisan serve
```

The application will be available at `http://localhost:8000`

**Run tests**

```bash
php artisan test
```

## API Endpoints

### Authentication

```
POST   /api/login                          - Login and get token
POST   /api/v1/logout                      - Logout
GET    /api/v1/user                        - Get current user info
```

### Vacancies (Public)

```
GET    /api/v1/public/vacancies            - List published vacancies
GET    /api/v1/public/vacancies/{id}       - View single vacancy
```

### Vacancies (HR only)

```
POST   /api/v1/companies/{id}/vacancies    - Create vacancy
```

### Attendance (Protected)

```
POST   /api/v1/attendances/check-in        - Check in for the day
POST   /api/v1/attendances/check-out       - Check out
GET    /api/v1/attendances                 - List my attendances
```

### Leave Requests (Protected)

```
POST   /api/v1/leave-requests              - Create leave request
GET    /api/v1/leave-requests              - List leave requests (role-based)
PUT    /api/v1/leave-requests/{id}/approve - Approve request (HR and Company Admin)
PUT    /api/v1/leave-requests/{id}/reject  - Reject request (HR and Company Admin)
```

### Absences (Protected)

```
POST   /api/v1/absences                    - Register absence (HR only)
GET    /api/v1/absences                    - List absences (role-based)
```

## API Authentication

All protected endpoints require Bearer token authentication
