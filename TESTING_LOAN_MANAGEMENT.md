# Admin Loan Management - Testing Guide

## Quick Start

### Access the Admin Dashboard
1. Navigate to: `http://localhost:8000/admin`
2. Login with admin credentials
3. Look for "Gestion des Emprunts" in the left menu

### What You'll See

**Loan List View:**
- All pending, active, and completed loans
- Columns: ID, User, Book, Status, Request Date
- Filter options for: Status, User, Book, Date range
- Paginated (20 loans per page)

**Loan Detail View (click on any loan):**
- Complete loan information
- User details (name, email)
- Book details (title, author, copies available)
- All timestamps
- Admin notes field
- Action buttons (depending on loan status)

---

## Feature Testing Scenarios

### Scenario 1: Approve a Pending Loan Request

**Prerequisites:**
- User has created a borrowing request (Status = "Demandé")
- Book has available copies (nbExemplaires > 0)

**Steps:**
1. Go to Admin → Gestion des Emprunts
2. Find a loan with status "Demandé" (Requested)
3. Click the loan row or the "Voir détails" button
4. Click the green **"Approuver"** button
5. Confirm success message: "L'emprunt de [Book Title] par [Username] a été approuvé."

**Result:**
- Loan status changes to "Approuvé" (Approved)
- Book stock decreases by 1
- `approvedAt` timestamp is set

---

### Scenario 2: Reject a Pending Loan Request

**Prerequisites:**
- User has created a borrowing request (Status = "Demandé")

**Steps:**
1. Go to Admin → Gestion des Emprunts
2. Find a loan with status "Demandé" (Requested)
3. Click the loan row for details
4. Click the red **"Rejeter"** button
5. Confirm success message: "L'emprunt de [Book Title] par [Username] a été rejeté."

**Result:**
- Loan status changes to "Annulé" (Cancelled)
- Book stock remains unchanged
- `cancelledAt` timestamp is set

---

### Scenario 3: Activate an Approved Loan

**Prerequisites:**
- Loan with status "Approuvé" (Approved)

**Steps:**
1. Go to Admin → Gestion des Emprunts
2. Find a loan with status "Approuvé"
3. Click the loan row for details
4. Look for **"Activer"** button (if available in your template)
5. Click to activate
6. Confirm success message includes due date

**Result:**
- Loan status changes to "En cours" (Active)
- `loanStartDate` is set to current date/time
- `dueDate` is set to 14 days from now
- User can now see book in their active loans calendar

---

### Scenario 4: Mark a Loan as Returned

**Prerequisites:**
- Loan with status "En cours" (Active) or "En retard" (Overdue)

**Steps:**
1. Go to Admin → Gestion des Emprunts
2. Filter by status "En cours" (Active) or "En retard" (Overdue)
3. Find the loan to mark as returned
4. Click the loan row for details
5. Click the blue **"Marquer retourné"** button
6. Confirm success message

**Result:**
- Loan status changes to "Retourné" (Returned)
- Book stock increases by 1 (restored to inventory)
- `returnedAt` timestamp is set
- Loan no longer appears in user's active loans

---

### Scenario 5: Extend a Loan

**Prerequisites:**
- Loan with status "En cours" (Active) or "En retard" (Overdue)
- Loan nearing due date or already overdue

**Steps:**
1. Go to Admin → Gestion des Emprunts
2. Filter by status "En retard" (Overdue) 
3. Click the loan row for details
4. Look for **"Prolonger"** button
5. Click to extend
6. Confirm success message with new due date

**Result:**
- `dueDate` is extended by 14 more days
- If was "En retard", status changes back to "En cours"
- User gets extended borrowing period

---

## Expected Loan Status Values

| Status Code | Display Name (French) | Description |
|-------------|----------------------|-------------|
| requested   | Demandé              | User requested, pending admin approval |
| approved    | Approuvé             | Admin approved, book reserved, waiting activation |
| active      | En cours             | Loan active, user has book, counting against due date |
| overdue     | En retard            | Loan past due date, user still has book |
| returned    | Retourné             | User returned book, loan completed |
| cancelled   | Annulé               | Admin or system cancelled the request |

---

## Action Button Visibility

| Button | Shows When | Changes Status To |
|--------|-----------|------------------|
| Approuver | Status = Requested, book available | Approved |
| Rejeter | Status = Requested or Approved | Cancelled |
| Marquer retourné | Status = Active or Overdue | Returned |
| Activer | Status = Approved | Active |
| Prolonger | Status = Active or Overdue | (extends due date) |

---

## Common Issues & Solutions

### Issue: "Approuver" button doesn't appear
**Cause:** Loan status is not "Demandé" OR book has no available copies
**Solution:** Check loan status and book stock. Increase book copies if needed.

### Issue: Button says "Marquer retourné" but is greyed out
**Cause:** Loan status is not "En cours" or "En retard"
**Solution:** First activate the loan (status must be "Approuvé")

### Issue: Book stock goes negative
**Cause:** Approvals without checking availability
**Solution:** Check `Livre.nbExemplaires` before approval. System validates availability.

### Issue: Routes return 404
**Cause:** Cache not cleared after deployment
**Solution:** Run `symfony console cache:clear`

---

## Database Verification

To verify loan status changes in the database:

```sql
-- View all loans with their statuses
SELECT id, status, requestedAt, approvedAt, returnedAt, cancelledAt 
FROM loans 
ORDER BY requestedAt DESC;

-- View book stock
SELECT titre, nbExemplaires 
FROM livres 
WHERE id = [book_id];

-- View overdue loans
SELECT l.id, l.status, l.dueDate, u.username, li.titre 
FROM loans l 
JOIN users u ON l.user_id = u.id 
JOIN livres li ON l.livre_id = li.id 
WHERE l.dueDate < NOW() AND l.status = 'active';
```

---

## Performance Notes

- Loan list filters by `requestedAt` (DESC) - ensure this column is indexed
- Operations use direct Doctrine flush for performance
- No batch operations yet (can add for bulk processing later)

---

## Future Enhancements

- [ ] Bulk approve/reject functionality
- [ ] Email notifications to users on approval/rejection
- [ ] Automatic overdue detection and status update
- [ ] Fine/penalty system for late returns
- [ ] Bulk extend loans (e.g., extend all overdue for a user)
- [ ] Export loan history report
- [ ] Renewal request approval workflow
