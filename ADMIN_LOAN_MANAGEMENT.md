# Admin Loan Management Features

## Overview
Complete admin loan management system for the Biblio application with approval, rejection, and return workflows.

## Implementation Details

### 1. New Admin Controller: `LoanAdminController`
**Location**: `src/Controller/Admin/LoanAdminController.php`

Handles all loan management operations with the following routes:

#### Routes Implemented:
- **Approve Loan**: `POST|GET /admin/loan/{id}/approve` → `app_admin_loan_approve`
  - Changes loan status from `REQUESTED` to `APPROVED`
  - Sets `approvedAt` timestamp
  - Decrements book stock (`nbExemplaires - 1`)
  - Validates book availability

- **Reject/Decline Loan**: `POST|GET /admin/loan/{id}/reject` → `app_admin_loan_reject`
  - Changes loan status from `REQUESTED` or `APPROVED` to `CANCELLED`
  - Sets `cancelledAt` timestamp
  - No book stock impact

- **Mark as Returned**: `POST|GET /admin/loan/{id}/return` → `app_admin_loan_return`
  - Changes loan status from `ACTIVE` or `OVERDUE` to `RETURNED`
  - Sets `returnedAt` timestamp
  - Restores book stock (`nbExemplaires + 1`)

- **Activate Loan**: `POST|GET /admin/loan/{id}/activate` → `app_admin_loan_activate`
  - Changes loan status from `APPROVED` to `ACTIVE`
  - Sets `loanStartDate` to current time
  - Sets `dueDate` to current + 14 days (default borrowing period)

- **Extend Loan**: `POST /admin/loan/{id}/extend` → `app_admin_loan_extend`
  - Extends due date by 14 days
  - Works for `ACTIVE` or `OVERDUE` loans
  - Marks overdue loans as active again if extended

### 2. Enhanced EasyAdmin Configuration

**File**: `config/packages/easyadmin.yaml`

Added Loan entity to admin dashboard:
```yaml
Loan:
    class: App\Entity\Loan
    controller: App\Controller\Admin\LoanCrudController
```

Added menu item:
```yaml
- { entity: 'Loan', icon: 'exchange', label: 'Gestion des Emprunts' }
```

### 3. Updated LoanCrudController

**File**: `src/Controller/Admin/LoanCrudController.php`

Fixed status constant references:
- Changed `STATUS_PENDING` to `STATUS_REQUESTED` (to match Loan entity)
- Updated action display conditions for approve/reject actions

Added action buttons in the admin list and detail views:
- **Approve Button** (green checkmark): Approves pending loans
- **Reject Button** (red X): Rejects pending loans
- **Mark Returned Button** (blue undo): Marks active loans as returned

### 4. Loan Entity Methods Used

**File**: `src/Entity/Loan.php`

Helper methods utilized:
- `canBeApproved()`: Validates loan can be approved (status = REQUESTED, book available)
- `canBeCancelled()`: Validates loan can be cancelled (status = REQUESTED or APPROVED)
- `isOverdue()`: Checks if active loan is overdue
- `getDaysRemaining()`: Calculates days until due date

**File**: `src/Entity/Livre.php`

Methods utilized:
- `isAvailableForBorrowing()`: Checks if book has available copies for borrowing

## Loan Status Flow

```
REQUESTED
  ├→ [Admin Approves] → APPROVED
  └→ [Admin Rejects] → CANCELLED

APPROVED
  ├→ [Admin Activates] → ACTIVE
  └→ [Admin Rejects] → CANCELLED

ACTIVE (or OVERDUE)
  ├→ [Admin Returns] → RETURNED
  └→ [Admin Extends] → ACTIVE (extends due date)

RETURNED / CANCELLED (terminal states)
```

## Admin Dashboard Features

### Loan Management List View
- View all loans with status, user, book, and request date
- Filter by status, user, book, or date range
- Sort by request date (newest first)
- Display pagination (20 items per page)

### Loan Detail View
- See full loan information including:
  - User and book details
  - All timestamps (requested, approved, due, returned, cancelled)
  - Current status with human-readable label
  - Admin notes
  - Approved by (staff member)
  
### Action Buttons
- **Approve**: Available only for REQUESTED loans
- **Reject**: Available only for REQUESTED loans
- **Mark Returned**: Available only for ACTIVE loans
- **Activate**: Available only for APPROVED loans
- **Extend**: Available for ACTIVE or OVERDUE loans

## Security
All routes require `ROLE_ADMIN` authorization via `#[IsGranted('ROLE_ADMIN')]`

## Validations
- Approve: Checks book availability before allowing approval
- Reject: Only REQUESTED or APPROVED loans can be rejected
- Return: Only ACTIVE or OVERDUE loans can be returned
- Activate: Only APPROVED loans can be activated
- Extend: Only ACTIVE or OVERDUE loans can be extended

## Flash Messages
All operations display user-friendly success/error messages:
- Success messages confirm the action and show affected book/user
- Error messages explain why an action couldn't be performed

## Book Stock Management
- **On Approval**: `nbExemplaires - 1` (reserve the book)
- **On Rejection**: No change (no book reserved)
- **On Return**: `nbExemplaires + 1` (restore to inventory)

## Access
Navigate to admin dashboard:
1. Go to http://localhost:8000/admin
2. Look for "Gestion des Emprunts" menu item
3. View loan list with action buttons
4. Click on individual loans for detailed management

## Testing the Features

### Test Approval Flow:
1. User creates borrowing request (loan status = REQUESTED)
2. Admin navigates to Loan management
3. Admin clicks "Approuver" button
4. Loan status changes to APPROVED
5. Book stock decreases by 1

### Test Return Flow:
1. Admin activates an approved loan (status = ACTIVE)
2. Admin clicks "Marquer retourné" button
3. Loan status changes to RETURNED
4. Book stock increases by 1

### Test Rejection Flow:
1. User creates borrowing request (status = REQUESTED)
2. Admin clicks "Rejeter" button
3. Loan status changes to CANCELLED
4. Book stock unchanged
