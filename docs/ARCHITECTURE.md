# Architecture Overview

This document explains the architectural patterns and design decisions in the HR Outsourcing Platform.

## Multi-Tenancy

The system uses **database-level multi-tenancy** with a shared database approach.

### Implementation

**Global Scopes:**

-   All company-specific tables have a `company_id` column
-   `CompanyScope` automatically filters queries by company
-   Located in: `app/Models/Scopes/CompanyScope.php`

**Trait for Reusability:**

-   `HasCompanyScope` trait applied to models
-   Models: Employee, Position, LeaveRequest, Absence, Attendance, Vacancy
-   Located in: `app/Models/Traits/HasCompanyScope.php`

## Service Layer Pattern

Business logic is separated from HTTP concerns using the Service Layer pattern.

### Services

**Location:** `app/Services/`

#### AttendanceService

```php
public function checkIn(Employee $employee): Attendance
public function checkOut(Employee $employee): Attendance
```

**Responsibilities:**

-   Validate one check-in per day
-   Validate check-out requires check-in
-   Create/update attendance records

#### LeaveRequestService

```php
public function createRequest(Employee $employee, array $data): LeaveRequest
public function approve(LeaveRequest $leaveRequest, int $approvedBy): LeaveRequest
public function reject(LeaveRequest $leaveRequest, int $rejectedBy, string $reason): LeaveRequest
```

**Responsibilities:**

-   Validate non-overlapping date ranges
-   Validate status transitions (only pending can be approved/rejected)
-   Update approval/rejection data

#### AbsenceService

```php
public function register(Employee $employee, string $date, ?string $reason): Absence
```

**Responsibilities:**

-   Validate one absence per employee per date
-   Register absence with automatic `created_by` tracking

## Observer Pattern

Automatic field tracking using Laravel Observers.

### Implementation

**Observers:**

-   `LeaveRequestObserver` - Auto-set `created_by` for leave requests
-   `AbsenceObserver` - Auto-set `created_by` for absences

**Location:** `app/Observers/`

**Registration:** `app/Providers/AppServiceProvider.php`

## Authorization

Two-level authorization approach.

### Middleware (Route-Level Blocking)

**CheckRole Middleware:**

-   Blocks requests at route level
-   Returns 403 if user doesn't have required role
-   Location: `app/Http/Middleware/CheckRole.php`

### Controller (Data Filtering)

**Role-Based Queries:**

## Database Design

### User vs Employee Separation

**Rationale:** Not all users are employees

```
users table:
- Authentication data (email, password)
- Role (hr, admin, company_admin, employee)

employees table:
- HR data (position, salary, hire_date, manager)
- Links to user: user_id (nullable for HR/Admin)
```

## API Authentication

**Laravel Sanctum** provides token-based authentication for the API.

## Testing Architecture

Comprehensive test coverage using **Pest PHP**.

### Test Structure

**Location:** `tests/Feature/Api/`

**Test Files:**

-   `AuthenticationTest.php` (3 tests)
-   `AttendanceTest.php` (5 tests)
-   `LeaveRequestTest.php` (4 tests)
-   `AbsenceTest.php` (3 tests)

**Total: 15 tests** covering API endpoints

### AAA Pattern

All tests follow **Arrange-Act-Assert** pattern:

### Factory Pattern

**Model Factories** provide clean, isolated test data.

**Location:** `database/factories/`

**Factories:**

-   CompanyFactory
-   PositionFactory
-   EmployeeFactory
-   AttendanceFactory
-   LeaveRequestFactory
-   AbsenceFactory

## Validation Strategy

Two-level validation approach.

### Input Validation (Form Requests)

**Form Request Classes:**

-   `StoreVacancyRequest`
-   `StoreLeaveRequest`
-   `StoreAbsenceRequest`

**Location:** `app/Http/Requests/`

**Purpose:**

-   Data type validation
-   Required fields
-   Format validation
-   Field-level rules

### Business Logic Validation (Services)

**Service Classes:**

-   AttendanceService
-   LeaveRequestService
-   AbsenceService

**Purpose:**

-   Database state validation
-   Multi-record checks
-   Complex business rules

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                        # API controllers (JSON)
│   │   │   ├── AttendanceController.php
│   │   │   ├── LeaveRequestController.php
│   │   │   └── AbsenceController.php
│   │   └── Web/                        # Web controllers (HTML)
│   │       └── EmployeeController.php
│   ├── Middleware/
│   │   └── CheckRole.php               # Role-based authorization
│   └── Requests/                       # Form Request validation
│       ├── StoreLeaveRequest.php
│       └── StoreAbsenceRequest.php
├── Models/
│   ├── User.php
│   ├── Employee.php
│   ├── Attendance.php
│   ├── LeaveRequest.php
│   ├── Absence.php
│   ├── Scopes/
│   │   └── CompanyScope.php            # Multi-tenancy scope
│   └── Traits/
│       └── HasCompanyScope.php         # Apply scope to models
├── Observers/
│   ├── LeaveRequestObserver.php        # Auto-set created_by
│   └── AbsenceObserver.php             # Auto-set created_by
├── Providers/
│   └── AppServiceProvider.php          # Register observers
└── Services/                           # Business logic layer
    ├── AttendanceService.php
    ├── LeaveRequestService.php
    └── AbsenceService.php

database/
├── factories/                          # Model factories for testing
│   ├── CompanyFactory.php
│   ├── EmployeeFactory.php
│   ├── AttendanceFactory.php
│   └── LeaveRequestFactory.php
├── migrations/                         # Database schema
└── seeders/                            # Seed data (development)
    ├── CompanySeeder.php
    ├── UserSeeder.php
    └── EmployeeSeeder.php

resources/
├── views/
│   ├── dashboard/                      # Role-based dashboards
│   │   ├── employee.blade.php
│   │   └── hr.blade.php
│   └── employee/                       # Employee self-service pages
│       ├── attendance.blade.php
│       └── leave-requests.blade.php

routes/
├── api.php                             # API routes (/api/v1/*)
└── web.php                             # Web routes (employee UI)

tests/
└── Feature/
    └── Api/
        ├── AuthenticationTest.php      # 3 tests
        ├── AttendanceTest.php          # 5 tests
        ├── LeaveRequestTest.php        # 4 tests
        └── AbsenceTest.php             # 3 tests
```
