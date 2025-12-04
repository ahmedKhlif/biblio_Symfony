# Quick Reference - Admin System

## Access Points

```
Admin Dashboard:     http://localhost:8000/admin
Admin Login:         http://localhost:8000/admin (if auth required)
```

## Menu Navigation

```
Dashboard
  ↓
Services Bibliotheque (MODERATOR+)
  ├─ Emprunts (Loans)
  └─ Reservations (NEW)

E-commerce
  ├─ Commandes (Orders)
  ├─ Articles de Commande
  ├─ Paniers
  └─ Articles du Panier

Gestion du Contenu (ADMIN only)
  ├─ Livres (Books)
  ├─ Auteurs (Authors)
  ├─ Categories
  └─ Editeurs (Publishers)

Gestion Utilisateurs (ADMIN only)
  ├─ Utilisateurs (Users)
  └─ Logs d'Activite
```

## Loan Management

### Navigate To
```
Admin → Services Bibliotheque → Emprunts
URL: /admin/loan
```

### Status Options
```
Demande (REQUESTED)      - Pending approval
Approuve (APPROVED)      - Approved, waiting activation
En cours (ACTIVE)        - Currently checked out
En retard (OVERDUE)      - Past due date
Retourne (RETURNED)      - Successfully returned
Annule (CANCELLED)       - Cancelled/Rejected
```

### Action Buttons
| Button | Use | Condition |
|--------|-----|-----------|
| Approuver | Approve request | Status = REQUESTED |
| Rejeter | Reject request | Status = REQUESTED |
| Marquer retourne | Mark as returned | Status = ACTIVE |
| Modifier | Edit details | Always |
| Supprimer | Delete loan | Always |

### Filters Available
```
- By Status
- By User
- By Book (Livre)
- By Request Date
- By Due Date
```

## Reservation Management

### Navigate To
```
Admin → Services Bibliotheque → Reservations
URL: /admin/book-reservation
```

### Key Fields
```
User              - Who made the reservation
Livre (Book)      - Which book
Position          - Queue position (0 = first)
isActive          - Active or cancelled
requestedAt       - When reserved
notifiedAt        - When converted to loan
```

### Action Buttons
| Button | Use | Condition |
|--------|-----|-----------|
| Promouvoir | Move up in queue | Active AND Position > 0 |
| Creer Emprunt | Convert to loan | Active AND Position = 0 |
| Annuler | Cancel reservation | Active |
| Modifier | Edit details | Always |
| Supprimer | Delete reservation | Always |

### Filters Available
```
- By Active Status
- By User
- By Book (Livre)
- By Request Date
- By Position
```

## Workflow: Reservation → Loan

```
Step 1: User makes Reservation
        → Position in queue assigned
        → Other reservations for same book updated

Step 2: Book becomes available
        → First reservation (Position 0) visible
        → "Creer Emprunt" button available

Step 3: Click "Creer Emprunt"
        → New Loan created (Status: APPROVED)
        → Book stock decreases by 1
        → Reservation deactivated
        → Next reservation promoted to Position 0

Step 4: Moderator can then activate the loan
        → Approve, Activate, or Extend as needed
```

## Dashboard Statistics

```
Loans:
  - Total loans
  - Pending requests
  - Approved loans
  - Active loans
  - Overdue loans
  - Returned loans

Reservations:
  - Total reservations
  - Active reservations
  - Notified/Converted reservations
```

## Date Formats

```
Displayed Format: DD/MM/YYYY HH:MM
Examples:
  - 03/12/2025 14:30
  - 25/11/2025 09:15
```

## Security

```
Who can see Loans & Reservations:
  ✅ ROLE_MODERATOR
  ✅ ROLE_ADMIN

Who can see Content Management (Books, Authors, etc.):
  ✅ ROLE_ADMIN only

Who can access Dashboard:
  ✅ ROLE_MODERATOR
  ✅ ROLE_ADMIN
```

## Common Tasks

### Create New Loan Request
```
1. Go to Admin → Services Bibliotheque → Emprunts
2. Click "Create new Emprunt"
3. Select User and Livre (Book)
4. Click "Save"
5. Loan created with Status = REQUESTED
6. Awaits approval
```

### Approve Pending Loan
```
1. Go to Admin → Services Bibliotheque → Emprunts
2. Find loan with Status = "Demande"
3. Click "Approuver" button
4. Status changes to "Approuve"
5. Book becomes unavailable
```

### Create Reservation
```
1. Go to Admin → Services Bibliotheque → Reservations
2. Click "Create new Reservation"
3. Select User and Livre (Book)
4. Position auto-assigned based on queue
5. Save and activation auto-enabled
```

### Convert Reservation to Loan
```
1. Go to Admin → Services Bibliotheque → Reservations
2. Find reservation with Position = 0 (first in queue)
3. Check book has available stock
4. Click "Creer Emprunt" button
5. New Loan created automatically
6. Reservation deactivated
7. Next reservation promoted
```

### Manage Reservation Queue
```
1. Go to Admin → Services Bibliotheque → Reservations
2. Select reservation with Position > 0
3. Click "Promouvoir" to move up
4. Or click "Annuler" to cancel
   (Other reservations auto-promoted)
```

## Troubleshooting

### Can't see Reservations menu
- ✅ Check user has ROLE_MODERATOR
- ✅ Check cache is cleared
- ✅ Refresh admin page

### Create Loan button disabled
- ✅ Check book has available copies
- ✅ Check reservation is in Position 0
- ✅ Check reservation is Active

### Dates showing incorrectly
- ✅ Format should be DD/MM/YYYY HH:MM
- ✅ Check system timezone settings
- ✅ No Intl extension required

### Action buttons not showing
- ✅ Check reservation/loan status
- ✅ Buttons show conditionally based on status
- ✅ Refresh page if needed

## Quick Stats

```
Total Admin Routes:           147
Loan Routes:                  13 (8 CRUD + 5 Custom)
Reservation Routes:           11 (8 CRUD + 3 Custom)
Configured Entities:          14+
EasyAdmin Controllers:        18
Custom Action Buttons:        8
Dashboard Statistics:         9
Security Roles:               2
```

## Support

For issues:
1. Check logs: `var/log/dev.log`
2. Clear cache: `symfony console cache:clear`
3. Check routes: `symfony console debug:router`
4. Verify roles: Check user roles in database

---

**Quick Reference Card - Version 1.0**
**Date: December 3, 2025**
