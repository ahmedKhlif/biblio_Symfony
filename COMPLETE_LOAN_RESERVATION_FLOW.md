# 📚 Complete Loan & Reservation System Flow

## User Journey: Book Browsing → Request → Loan/Reservation

---

## 1️⃣ STEP 1: USER BROWSES BOOKS

**Location:** `/livre` (Livre List Page)
**File:** `templates/livre/index.html.twig`

```
1. User visits /livre (list page)
2. Searches/filters books by:
   - Title, ISBN, Author
   - Category, Publisher
   - Rating, Price range
   - Sort by (newest, title, price, stock)
3. User clicks on a book to view details
   ↓
```

---

## 2️⃣ STEP 2: USER VIEWS BOOK DETAILS

**Location:** `/livre/{id}` (Livre Show Page)
**File:** `templates/livre/show.html.twig` (lines 285-315)
**Controller:** `LivreController.php` show() method

### What's Displayed:

```
Book Details:
├─ Cover image
├─ Title, Author, Publisher
├─ ISBN, Pages count
├─ Description, Rating, Reviews
├─ Availability status
├─ Number of copies available
└─ Borrowing section (KEY PART)
```

### Borrowing Section Logic:

```php
// BorrowingController.php line 46-47
if ($livre->isAvailableForBorrowing()) {
    // Show "Request Loan" button
} else {
    // Show "Join Waiting List" button
}
```

### What User Sees:

```html
<!-- SCENARIO 1: Book IS Available -->
<a href="/borrowing/request/{id}" class="btn btn-warning">
    <i class="fas fa-book-reader"></i> Demander un emprunt
</a>

<!-- SCENARIO 2: User has ACTIVE loan for this book -->
<div class="alert alert-info">
    Vous avez déjà un emprunt actif pour ce livre.
    <a href="/loan">Voir mes emprunts</a>
</div>

<!-- SCENARIO 3: Book NOT available (all copies borrowed) -->
<div class="alert alert-warning">
    Tous les exemplaires sont actuellement empruntés.
    <a href="/borrowing/request/{id}" class="btn btn-outline-warning">
        M'inscrire à la liste d'attente
    </a>
</div>
```

---

## 3️⃣ STEP 3: USER CLICKS BORROW BUTTON

**Location:** `/borrowing/request/{id}`
**Files:**
- Controller: `src/Controller/BorrowingController.php` (lines 20-83)
- Template: `templates/borrowing/request.html.twig`

---

## 🚀 DECISION POINT: IS BOOK AVAILABLE?

### PATH A: BOOK IS AVAILABLE ✅

**Condition:** `$livre->isAvailableForBorrowing()` returns `true`

```php
// Location: Livre.php (lines 384-391)
public function isAvailableForBorrowing(): bool
{
    if (!$this->isBorrowable) return false;  // Admin disabled borrowing
    return $this->nbExemplaires > $this->getActiveLoansCount();  // Copies > Active loans
}
```

**What Happens:**

1. **Form Displayed** - User sees borrowing request form
   ```html
   <form action="/borrowing/request/{id}" method="POST">
       <input name="loan[loanStartDate]" type="date">
       <input name="loan[dueDate]" type="date">
       <textarea name="loan[notes]"></textarea>
       <button type="submit">Soumettre la demande</button>
   </form>
   ```

2. **Form Submission** - User fills dates and clicks submit
   ```php
   // BorrowingController.php line 47-55
   $loan = new Loan();
   $loan->setUser($user);
   $loan->setLivre($livre);
   $loan->setStatus(Loan::STATUS_REQUESTED);  // "requested"
   $loan->setRequestedAt(new \DateTime());
   
   $entityManager->persist($loan);
   $entityManager->flush();
   ```

3. **Success Message** - ✅ Loan created
   ```
   ✓ Votre demande d'emprunt a été soumise avec succès.
   ```

4. **Email Triggered** - 📧 Admin notification sent
   ```php
   // AdminEmailListener.php (Doctrine PostPersist event)
   // Event: New Loan created
   // Email: Admin receives "New Loan Request" notification
   // Contains: Book title, User name, Requested dates
   ```

5. **User Redirected** - Back to book details page

---

### PATH B: BOOK NOT AVAILABLE ❌

**Condition:** `$livre->isAvailableForBorrowing()` returns `false`

**Reasons:**
- `isBorrowable = false` (admin disabled borrowing for this book)
- OR all copies are checked out (active loans ≥ nbExemplaires)

**What Happens:**

1. **Check Existing Reservation** - Does user already have a reservation?
   ```php
   // BorrowingController.php line 69-71
   $existingReservation = $reservationRepository->findUserActiveReservationForBook($user, $livre);
   
   if ($existingReservation) {
       // User already in queue, show warning
   }
   ```

2. **Create Reservation** - User added to waiting list
   ```php
   // BorrowingController.php line 75-87
   $reservation = new BookReservation();
   $reservation->setUser($user);
   $reservation->setLivre($livre);
   
   // Calculate position in queue
   $activeReservations = $reservationRepository->findActiveReservationsForBook($livre);
   $position = count($activeReservations) + 1;
   $reservation->setPosition($position);
   
   $entityManager->persist($reservation);
   $entityManager->flush();
   ```

3. **Success Message** - ✅ User added to queue
   ```
   ✓ Le livre n'est pas disponible actuellement. 
     Vous avez été ajouté à la liste d'attente (position 3).
   ```

4. **Display Current Queue** - User sees who's ahead
   ```html
   <div class="reservation-list">
       <div class="reservation-item">
           User A - Position 1 (since 15/11/2024)
       </div>
       <div class="reservation-item">
           User B - Position 2 (since 16/11/2024)
       </div>
       <div class="reservation-item">
           YOU - Position 3 (since 03/12/2024) ← Current user
       </div>
   </div>
   ```

5. **Email Triggered** - 📧 User confirmation sent
   ```
   Email: Reservation confirmation
   Subject: Votre réservation de livre
   Content: You are position X in the waiting list
   ```

---

## 📋 LOAN LIFECYCLE STATUSES

```
Loan Status Flow:
════════════════════════════════════════════════

1. REQUESTED (🟡 Yellow)
   ├─ User submitted loan request
   ├─ Waiting for admin approval
   └─ Can be cancelled by user

2. APPROVED (🔵 Blue)
   ├─ Admin approved the request
   ├─ Ready for user to pick up
   └─ Email sent: "Your loan was approved"

3. ACTIVE (🟢 Green)
   ├─ User picked up the book
   ├─ Loan period started
   └─ Can read, track progress, return

4. OVERDUE (🔴 Red)
   ├─ Due date passed
   ├─ Automatic status (no admin action needed)
   └─ Email sent: "Your loan is overdue"

5. RETURNED (⚫ Gray)
   ├─ User returned the book
   ├─ Final status
   └─ Email sent: "Book returned successfully"

6. CANCELLED ⚫ Gray)
   ├─ User cancelled the request
   ├─ Only possible in REQUESTED status
   └─ Position recalculated for reservations

```

---

## 🔔 RESERVATION LIFECYCLE

```
BookReservation Status:
════════════════════════════════════════════════

1. CREATED (Waiting)
   ├─ User added to queue
   ├─ position = X
   ├─ isActive = true
   └─ notifiedAt = NULL

2. NOTIFIED (Available!)
   ├─ Book became available
   ├─ User notified by email
   ├─ isActive = still true
   └─ notifiedAt = notification date

3. AUTO-PROMOTED (if available)
   ├─ Reservation auto-converts to Loan
   ├─ isActive = false (ends reservation)
   └─ New Loan created for user

4. CANCELLED
   ├─ User cancelled manually
   ├─ isActive = false
   └─ Positions recalculated

```

---

## 📧 EMAIL FLOW

### Scenario A: Loan Request Created

```
1. User submits loan request
   ↓
2. BorrowingController creates Loan entity
   ↓
3. Doctrine PostPersist event triggered
   ↓
4. AdminEmailListener listens for Loan creation
   ↓
5. EmailService called with:
   - Template: "admin_new_loan_request.html.twig"
   - To: Admin email
   - Data: User name, book title, requested dates
   ↓
6. Email sent via Gmail SMTP
```

### Scenario B: Loan Status Changes

```
1. Admin approves loan in EasyAdmin
   ↓
2. Doctrine PostUpdate event triggered
   ↓
3. AdminEmailListener checks status change
   ↓
4. If status = APPROVED:
   - EmailService sends to user
   - Template: "admin_loan_approved.html.twig"
   - Message: "Your loan was approved"
```

### Scenario C: Book Becomes Available (Reservation)

```
1. User returns a book
   ↓
2. Loan marked as RETURNED
   ↓
3. Check waiting list:
   - Get first active reservation
   ↓
4. If reservation found:
   - Mark as notified
   - Send notification email to user
   - Template: "admin_book_available.html.twig"
   - Message: "Book you reserved is available!"
```

---

## 🔗 USER MENU NAVIGATION

**Location:** `templates/backendofficebase.html.twig`

```html
<!-- Sidebar Navigation -->
Sidebar > Mes Livres
├─ Mes Emprunts (/loan) 📚
│  └─ Shows active loans, returned, overdue, history
│
├─ Mes Réservations (/reservation/my-reservations) ⏳ [NEW]
│  └─ Shows waiting list positions, notifications
│
├─ Mes Livres Achétés (/profile/owned-books)
├─ Ma Wishlist (/profile/wishlist)
└─ Mes Commandes (/profile/orders)

<!-- Topbar Dropdown -->
User Icon > Dropdown
├─ Mon Profil
├─ Mon Panier
├─ Mes Emprunts (/loan)
└─ Mes Réservations (/reservation/my-reservations) [NEW]
```

---

## 🎯 COMPLETE USER JOURNEY MAP

```
┌─────────────────────────────────────────────────────────────┐
│                    USER VISIT WEBSITE                       │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Browse Books (/livre)                          │
│  - Search, filter, sort                                    │
│  - See book list with thumbnails                           │
└────────────────────────┬────────────────────────────────────┘
                         │ Click on book
                         ▼
┌─────────────────────────────────────────────────────────────┐
│          View Book Details (/livre/{id})                    │
│  - Title, author, cover, description                       │
│  - Availability status                                     │
│  - Borrow/Reserve button                                   │
└────────────┬──────────────────────────────┬────────────────┘
             │                              │
      Available?                     Not Available?
        YES ✅                           NO ❌
             │                              │
             ▼                              ▼
┌──────────────────────┐    ┌──────────────────────────┐
│  LOAN WORKFLOW       │    │  RESERVATION WORKFLOW    │
├──────────────────────┤    ├──────────────────────────┤
│ 1. Show form         │    │ 1. Check user in queue   │
│ 2. User fills dates  │    │ 2. Add to waiting list   │
│ 3. Submit request    │    │ 3. Calculate position    │
│ 4. Loan created      │    │ 4. Show position         │
│ 5. Status: REQUESTED │    │ 5. Notify admin          │
│ 6. Email sent        │    │ 6. Email sent to user    │
│ 7. Redirect to book  │    │ 7. Redirect to book      │
│                      │    │                          │
│ ✉️ Admin receives    │    │ ✉️ User receives         │
│    notification      │    │    confirmation          │
└──────────┬───────────┘    └──────────┬───────────────┘
           │                           │
           └──────────┬────────────────┘
                      │
                      ▼
        ┌─────────────────────────────┐
        │  User Dashboard Page        │
        │  (/reservation/my-...)      │
        ├─────────────────────────────┤
        │ Sidebar: Mes Emprunts       │
        │ Sidebar: Mes Réservations   │
        │                             │
        │ Shows status, dates, queue  │
        │ Can cancel, view details    │
        └─────────────────────────────┘
```

---

## 🔧 CODE FLOW DIAGRAM

```
User Request (/livre/{id})
│
└─> LivreController::show()
    │
    └─> templates/livre/show.html.twig
        │
        ├─ IF hasPurchasedBook
        │  └─ Show PDF access (if purchased)
        │
        └─ IF not hasPurchasedBook AND livre.isBorrowable
           │
           ├─ Check if user has ACTIVE loan
           │  ├─ YES → Show "You have active loan" message
           │  └─ NO → Continue to availability check
           │
           └─ Check if livre.isAvailableForBorrowing()
              │
              ├─ YES (Book available)
              │  └─ Link: /borrowing/request/{id}
              │
              └─ NO (Book not available)
                 └─ Link: /borrowing/request/{id}
                    (will add to reservation instead)


User Clicks Borrow (/borrowing/request/{id})
│
└─> BorrowingController::request()
    │
    ├─ Validate: No existing loan/reservation
    │  └─ If exists: Show warning, redirect back
    │
    └─ Check: livre.isAvailableForBorrowing()
       │
       ├─ YES → LOAN PATH
       │  ├─ Create Loan entity
       │  ├─ Set status = REQUESTED
       │  ├─ Persist & flush
       │  ├─ Trigger PostPersist event
       │  ├─ AdminEmailListener sends email
       │  ├─ Show success message
       │  └─ Render: borrowing/request.html.twig (available=true)
       │
       └─ NO → RESERVATION PATH
          ├─ Create BookReservation entity
          ├─ Calculate position = count(active) + 1
          ├─ Set isActive = true
          ├─ Persist & flush
          ├─ Trigger PostPersist event
          ├─ AdminEmailListener sends email
          ├─ Show "added to queue position X" message
          └─ Render: borrowing/request.html.twig (available=false)
```

---

## 📊 DATABASE SCHEMA

```sql
-- LOANS TABLE
CREATE TABLE loan (
    id INT PRIMARY KEY,
    user_id INT,
    livre_id INT,
    status VARCHAR(20),  -- requested, approved, active, overdue, returned, cancelled
    requested_at DATETIME,
    approved_at DATETIME,
    loan_start_date DATE,
    due_date DATE,
    returned_at DATETIME,
    cancelled_at DATETIME,
    notes TEXT,
    updated_at DATETIME
);

-- RESERVATIONS TABLE
CREATE TABLE book_reservation (
    id INT PRIMARY KEY,
    user_id INT,
    livre_id INT,
    position INT,  -- queue position (1, 2, 3...)
    is_active BOOLEAN,  -- true if still waiting
    requested_at DATETIME,
    notified_at DATETIME  -- when user was notified
);

-- BOOKS TABLE (relevant fields)
CREATE TABLE livre (
    id INT PRIMARY KEY,
    titre VARCHAR(255),
    is_borrowable BOOLEAN,  -- Admin can disable borrowing
    nb_exemplaires INT,  -- Total copies
    ...
);
```

---

## ✅ ALL PAGES & ROUTES

| Route | Method | Controller | Template | Purpose |
|-------|--------|-----------|----------|---------|
| `/livre` | GET | LivreController::index() | livre/index.html.twig | Book list with filters |
| `/livre/{id}` | GET | LivreController::show() | livre/show.html.twig | Book details + borrow button |
| `/borrowing/request/{id}` | GET/POST | BorrowingController::request() | borrowing/request.html.twig | Loan/Reservation form |
| `/borrowing/calendar/{id}` | GET | BorrowingController::calendar() | borrowing/calendar.html.twig | Book's borrowing history |
| `/borrowing/my-calendar` | GET | BorrowingController::myCalendar() | borrowing/my_calendar.html.twig | User's all borrowing history |
| `/loan` | GET | LoanController::index() | loan/index.html.twig | User's loans dashboard |
| `/reservation/my-reservations` | GET | ReservationController::index() | reservation/index.html.twig | User's reservations list [NEW] |
| `/reservation/{id}/view` | GET | ReservationController::view() | reservation/view.html.twig | Reservation detail [NEW] |
| `/reservation/{id}/cancel` | POST | ReservationController::cancel() | - | Cancel reservation [NEW] |

---

## 🎓 SUMMARY

```
When user browses and finds a book:
┌─────────────────────────────────────────────────────────────┐
│  Is the book available (isBorrowable + copies > loans)?    │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  YES ✅ → CREATE LOAN                                       │
│         → Status: REQUESTED                                │
│         → User sees form to choose dates                   │
│         → Admin notified by email                          │
│         → Goes to /loan dashboard                          │
│                                                              │
│  NO ❌ → CREATE RESERVATION                                │
│        → Added to waiting queue                             │
│        → Position calculated automatically                  │
│        → User notified when book available                  │
│        → Goes to /reservation dashboard                     │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

All code is complete, tested, and ready to use! ✅
