# Banner Management System - Deployment Checklist

## Pre-Deployment Verification

### Database
- [ ] Backup existing database
- [ ] Run migrations: `php bin/console doctrine:migrations:migrate`
- [ ] Verify tables created:
  - [ ] `banners` table exists
  - [ ] `user_banner_preference` table exists
  - [ ] Proper indexes on foreign keys
- [ ] Test data integrity

### Code
- [ ] Clear cache: `php bin/console cache:clear`
- [ ] Check for errors: `php bin/console lint:yaml config/`
- [ ] Verify no PHP errors: Check src/ directory
- [ ] Test routing: `php bin/console debug:router | grep banner`
- [ ] Verify entity mapping: `php bin/console doctrine:mapping:info`

### Dependencies
- [ ] All required classes imported
- [ ] No circular dependencies
- [ ] Form types registered
- [ ] Template engine configured

---

## File Verification Checklist

### Controllers
- [ ] `src/Controller/BannerController.php`
  - [ ] 13 public methods
  - [ ] All routes defined
  - [ ] CSRF token validation
  - [ ] Authorization checks

### Entities
- [ ] `src/Entity/Banner.php` (existing, verify)
  - [ ] All properties present
  - [ ] Lifecycle callbacks working
  - [ ] Relations configured

- [ ] `src/Entity/UserBannerPreference.php` (existing, verify)
  - [ ] User relationship
  - [ ] Banner relationship
  - [ ] Unique constraint

### Forms
- [ ] `src/Form/BannerType.php`
  - [ ] All fields present
  - [ ] Validation rules
  - [ ] Choice arrays correct

### Repositories
- [ ] `src/Repository/BannerRepository.php`
  - [ ] getBannerStatistics() method updated
  - [ ] All queries working
  - [ ] Save/remove methods present

- [ ] `src/Repository/UserBannerPreferenceRepository.php` (existing, verify)
  - [ ] findOrCreate() method
  - [ ] isBannerHidden() method
  - [ ] getHiddenBanners() method

### Templates - Admin
- [ ] `templates/admin/banner/index.html.twig`
  - [ ] Statistics cards
  - [ ] Filter form
  - [ ] Banner table
  - [ ] Pagination

- [ ] `templates/admin/banner/form.html.twig`
  - [ ] All form fields
  - [ ] Validation display
  - [ ] Help section

- [ ] `templates/admin/banner/details.html.twig`
  - [ ] Banner info display
  - [ ] Dismissal list
  - [ ] Reset buttons

- [ ] `templates/admin/banner/preferences.html.twig`
  - [ ] Preference list
  - [ ] Dismissal dates
  - [ ] Reset functionality

- [ ] `templates/admin/banner/stats.html.twig`
  - [ ] Statistics cards
  - [ ] Charts/visualizations
  - [ ] Dismissal rates

### Templates - User
- [ ] `templates/banner/preferences.html.twig`
  - [ ] Visible banners list
  - [ ] Hidden banners list
  - [ ] Show/hide buttons

### Components
- [ ] `templates/components/_banners.html.twig`
  - [ ] API integration
  - [ ] localStorage fallback
  - [ ] Authentication check
  - [ ] Animations

### Documentation
- [ ] `BANNER_MANAGEMENT_SYSTEM.md`
- [ ] `BANNER_ADMIN_GUIDE.md`
- [ ] `BANNER_USER_GUIDE.md`
- [ ] `BANNER_ROUTES_REFERENCE.md`
- [ ] `BANNER_IMPLEMENTATION_SUMMARY.md`

---

## Security Checklist

### CSRF Protection
- [ ] All POST routes protected
- [ ] Token fields in forms
- [ ] Token validation in controller
- [ ] Token names unique per action

### Authentication
- [ ] Admin routes require ROLE_ADMIN
- [ ] User routes require authentication
- [ ] API endpoints secured
- [ ] No direct object reference (use DBAL checks)

### Authorization
- [ ] Users can't edit others' preferences
- [ ] Admins have all permissions
- [ ] Guests can view but can't manage
- [ ] Permission checks in place

### Input Validation
- [ ] Form validation rules present
- [ ] Entity validation constraints
- [ ] Type hints on methods
- [ ] Range validations on numeric fields

### Data Protection
- [ ] No sensitive data in responses
- [ ] User emails not exposed unnecessarily
- [ ] No admin info leaked
- [ ] SQL injection prevented (using ORM)

---

## Performance Checklist

### Database
- [ ] Indexes on foreign keys
- [ ] Indexes on frequently searched columns
- [ ] Pagination implemented (10-20 items)
- [ ] Lazy loading used appropriately

### Caching
- [ ] Static assets cached
- [ ] Query results cached
- [ ] Template fragments cached
- [ ] Cache headers set

### Queries
- [ ] No N+1 problems
- [ ] Efficient joins
- [ ] Limit results in queries
- [ ] Use repository methods

---

## Testing Checklist

### Admin Functionality
- [ ] Create banner
  - [ ] All fields save correctly
  - [ ] Validation works
  - [ ] Redirects to list
  - [ ] Success message shown

- [ ] Edit banner
  - [ ] Load existing data
  - [ ] Update fields
  - [ ] Save changes
  - [ ] Verify in database

- [ ] Delete banner
  - [ ] Confirmation dialog
  - [ ] Record deleted
  - [ ] Redirect works
  - [ ] Related data handled

- [ ] Activate/Deactivate
  - [ ] Status changes
  - [ ] Immediate effect
  - [ ] CSRF token works

- [ ] View details
  - [ ] All info displays
  - [ ] Dismissal list loads
  - [ ] Pagination works

- [ ] Manage preferences
  - [ ] List loads correctly
  - [ ] Filters work
  - [ ] Reset functionality
  - [ ] Pagination works

- [ ] View statistics
  - [ ] All stats display
  - [ ] Charts render
  - [ ] Dismissal rates calculate
  - [ ] No errors

### User Functionality
- [ ] View preferences
  - [ ] Loads for logged-in user
  - [ ] Shows visible banners
  - [ ] Shows hidden banners
  - [ ] Displays timestamps

- [ ] Hide banner
  - [ ] API responds correctly
  - [ ] Banner hides immediately
  - [ ] Preference saved
  - [ ] Persists after refresh

- [ ] Show banner
  - [ ] API responds correctly
  - [ ] Banner appears again
  - [ ] Preference updated
  - [ ] Persists after refresh

- [ ] Reset all
  - [ ] All hidden banners reappear
  - [ ] Confirmation works
  - [ ] Preferences reset

### API Testing
- [ ] GET /api/banner-preferences
  - [ ] Returns correct hidden list
  - [ ] Requires authentication
  - [ ] Valid JSON response

- [ ] POST /api/banner/hide/{id}
  - [ ] CSRF token required
  - [ ] Saves preference
  - [ ] Returns success

- [ ] POST /api/banner/show/{id}
  - [ ] CSRF token required
  - [ ] Updates preference
  - [ ] Returns success

- [ ] POST /api/banner/reset-all
  - [ ] CSRF token required
  - [ ] Resets all preferences
  - [ ] Returns success

### Edge Cases
- [ ] Delete banner used by preferences
  - [ ] Cascade delete works
  - [ ] No orphaned records

- [ ] Reset non-existent preference
  - [ ] Handles gracefully
  - [ ] No errors

- [ ] Multiple admin simultaneous edits
  - [ ] Last save wins
  - [ ] No data corruption

- [ ] Banner expired past end date
  - [ ] Status auto-updates
  - [ ] Doesn't display

- [ ] Scheduled banner at start time
  - [ ] Status auto-updates
  - [ ] Starts displaying

---

## Browser Compatibility

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers
- [ ] Mobile responsiveness
- [ ] Touch interactions work

---

## Documentation Review

- [ ] Admin guide complete
- [ ] User guide complete
- [ ] Routes documented
- [ ] Code comments present
- [ ] Examples provided
- [ ] FAQs answered
- [ ] Troubleshooting covered

---

## Performance Monitoring

- [ ] Page load times acceptable
- [ ] Database queries < 100ms
- [ ] No memory leaks
- [ ] Cache hit ratio good
- [ ] API response times < 200ms

---

## Deployment Steps

1. [ ] Create backup of production database
2. [ ] Pull latest code
3. [ ] Run `composer install`
4. [ ] Run database migrations
5. [ ] Clear application cache
6. [ ] Run `bin/console warmup:cache`
7. [ ] Verify no errors in logs
8. [ ] Test admin access
9. [ ] Test user access
10. [ ] Monitor error logs
11. [ ] Verify analytics dashboard
12. [ ] Test on mobile devices

---

## Post-Deployment

- [ ] Monitor error logs
- [ ] Check database performance
- [ ] Verify cache working
- [ ] Test user reports
- [ ] Monitor dismissal rates
- [ ] Check admin dashboard
- [ ] Verify email notifications (if enabled)
- [ ] Document any issues

---

## Rollback Plan

If issues arise:

1. [ ] Identify issue
2. [ ] Stop banner display (deactivate all)
3. [ ] Check error logs
4. [ ] Clear cache
5. [ ] Verify database integrity
6. [ ] If critical: Revert code
7. [ ] Restore database from backup if needed

---

## Support Documentation

- [ ] Admin trained on system
- [ ] User documentation available
- [ ] FAQ accessible
- [ ] Support contacts identified
- [ ] Issue reporting process documented

---

## Sign-Off

- **Deployed By:** _______________________
- **Date:** _______________________
- **Tested By:** _______________________
- **Approved By:** _______________________

---

## Notes

_Use this space for any additional notes or observations:_

```
_________________________________________________________________________

_________________________________________________________________________

_________________________________________________________________________

_________________________________________________________________________

_________________________________________________________________________
```

---

**This checklist ensures a smooth deployment of the Banner Management System.**

For any issues, refer to:
- Technical Documentation: `BANNER_MANAGEMENT_SYSTEM.md`
- Admin Guide: `BANNER_ADMIN_GUIDE.md`
- Routes Reference: `BANNER_ROUTES_REFERENCE.md`

*Last Updated: December 3, 2025*
