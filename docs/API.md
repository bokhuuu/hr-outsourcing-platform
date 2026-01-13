# API Documentation

Base URL: `http://localhost:8000/api`

## Authentication

**Login**

```http
POST /login
Content-Type: application/json

{
  "email": "hr@platform.ge",
  "password": "password"
}
```

**Logout**

```http
POST /v1/logout
Authorization: Bearer {token}
```

## Authorization

All endpoints below require `Authorization: Bearer {token}` header.

**Token Usage:**

```bash
curl http://localhost:8000/api/v1/attendances \
  -H "Authorization: Bearer 1|abc123..."
```

## Vacancies

### Public Endpoints (No Auth Required)

**List Published Vacancies**

```http
GET /v1/public/vacancies
```

**View Single Vacancy**

```http
GET /v1/public/vacancies/{id}
```

### HR Only Endpoints

**Create Vacancy**

```http
POST /v1/companies/{companyId}/vacancies
Authorization: Bearer {token}
Content-Type: application/json
```

## Attendance

### Check In

```http
POST /v1/attendances/check-in
Authorization: Bearer {token}
```

**Business Rules:**

-   Can only check in once per day
-   Creates attendance record for current date

### Check Out

```http
POST /v1/attendances/check-out
Authorization: Bearer {token}
```

**Business Rules:**

-   Must check in first
-   Updates existing attendance record

### List Attendances

```http
GET /v1/attendances
Authorization: Bearer {token}
```

**Role-Based Filtering:**

-   **Employee:** Sees only their own attendance
-   **HR/Admin:** Sees all company attendances

## Leave Requests

### Create Leave Request (Employee)

```http
POST /v1/leave-requests
Authorization: Bearer {token}
Content-Type: application/json
```

**Leave Types:** `vacation`, `sick`, `personal`

**Business Rules:**

-   `end_date` must be >= `start_date`
-   Cannot overlap with existing pending/approved requests
-   Status automatically set to `pending`
-   `created_by` automatically tracked via Observer

### List Leave Requests

```http
GET /v1/leave-requests
Authorization: Bearer {token}
```

**Role-Based Filtering:**

-   **Employee:** Sees only their own requests
-   **Company Admin:** Sees only their company's requests
-   **HR:** Sees all requests

### Approve Leave Request (HR/Company Admin)

```http
PUT /v1/leave-requests/{id}/approve
Authorization: Bearer {token}
```

**Business Rules:**

-   Only pending requests can be approved
-   `approved_by` and `approved_at` automatically set

### Reject Leave Request (HR/Company Admin)

```http
PUT /v1/leave-requests/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json
```

**Business Rules:**

-   Only pending requests can be rejected
-   Rejection reason is required

## Absences

### Register Absence (HR Only)

```http
POST /v1/absences
Authorization: Bearer {token}
Content-Type: application/json
```

**Business Rules:**

-   One absence per employee per date
-   `created_by` automatically tracked via Observer

### List Absences

```http
GET /v1/absences
Authorization: Bearer {token}
```

**Role-Based Filtering:**

-   **Employee:** Sees only their own absences
-   **HR/Company Admin:** Sees all company absences

## Testing

```bash
php artisan test
```

**Test Coverage:**

-   Authentication (login, logout, protected routes)
-   Attendance (check-in, check-out, validations)
-   Leave Requests (create, approve, reject, overlapping validation)
-   Absences (register, duplicates, authorization)
