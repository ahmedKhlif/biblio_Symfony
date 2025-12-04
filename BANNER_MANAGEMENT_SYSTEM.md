# Banner Management System - Complete Implementation

## Overview
A comprehensive banner and notification management system for both administrators and end users, featuring CRUD operations, user preference tracking, and detailed analytics.

## Features Implemented

### 1. **Admin Banner Management** (`/admin/banner`)

#### CRUD Operations
- **Create Banner** - Create new banners with full customization
- **Edit Banner** - Modify existing banner details
- **Delete Banner** - Remove banners from the system
- **List Banners** - View all banners with filtering and pagination

#### Banner Properties
- Title and Content
- Type: Promotion, Announcement, Warning, Information
- Position: Top, Bottom, Sidebar, Popup
- Status: Active, Inactive, Scheduled, Expired
- Priority (1-100)
- Date Scheduling (start and end dates)
- Target Audience (All, Users, Admins, Guests)
- Call-to-Action Link

#### Admin Features
- **View Banner Details** - See complete banner information and who has dismissed it
- **Filter Banners** - By status, type, and search text
- **Manage Preferences** - View and reset user banner preferences
- **View Dismissal Stats** - See which users have dismissed each banner
- **Reset User Preferences** - Force show banners to specific users
- **Analytics Dashboard** - Dismissal rates and engagement metrics

### 2. **User-Facing Features**

#### Banner Display
- Smart display based on user roles and permissions
- Date-based scheduling (start/end dates)
- Priority-based ordering
- Dismissible banners with smooth animations

#### User Preferences (`/profile/banner-preferences`)
- View visible and hidden banners
- Show/hide individual banners
- Reset all banner preferences at once
- See dismissal history (date/time)

### 3. **API Endpoints**

#### For Authenticated Users
- `GET /api/banner-preferences` - Get list of hidden banners
- `POST /api/banner/hide/{id}` - Hide a specific banner
- `POST /api/banner/show/{id}` - Show a previously hidden banner
- `POST /api/banner/reset-all` - Reset all banner preferences

#### Admin Endpoints
- `GET /admin/banner` - List all banners
- `GET /admin/banner/create` - Create banner form
- `POST /admin/banner/create` - Save new banner
- `GET /admin/banner/edit/{id}` - Edit banner form
- `POST /admin/banner/edit/{id}` - Update banner
- `POST /admin/banner/delete/{id}` - Delete banner
- `GET /admin/banner/details/{id}` - View banner details and dismissals
- `GET /admin/banner/preferences/{id}` - Manage user preferences for banner
- `POST /admin/banner/activate/{id}` - Activate banner
- `POST /admin/banner/deactivate/{id}` - Deactivate banner
- `GET /admin/banner/stats` - View statistics
- `POST /admin/banner/reset-preferences/{id}` - Reset all user preferences
- `POST /admin/banner/reset-user-preference/{bannerId}/{userId}` - Reset specific user preference

### 4. **Database Entities**

#### Banner Entity
```php
- id: int (Primary Key)
- title: string (255)
- content: text (nullable)
- type: string (promotion, announcement, warning, info)
- position: string (top, bottom, sidebar, popup)
- status: string (active, inactive, scheduled, expired)
- startDate: DateTime (nullable)
- endDate: DateTime (nullable)
- priority: int (1-100)
- link: string (nullable)
- linkText: string (nullable)
- image: string (nullable)
- targetAudience: JSON array
- styling: JSON array (colors, borders)
- createdAt: DateTimeImmutable
- updatedAt: DateTimeImmutable
- createdBy: User (relationship)
```

#### UserBannerPreference Entity
```php
- id: int (Primary Key)
- user: User (Foreign Key)
- banner: Banner (Foreign Key)
- hidden: bool
- hiddenAt: DateTime (when user dismissed)
```

### 5. **Repositories**

#### BannerRepository
- `findActiveBanners(position, user)` - Get active banners for a position
- `findAllActiveBanners(user)` - Get all active banners
- `findByType(type)` - Find banners by type
- `findByStatus(status)` - Find banners by status
- `findExpiredBanners()` - Find expired banners
- `findScheduledBannersToActivate()` - Auto-activate scheduled banners
- `getBannerStatistics()` - Comprehensive banner stats

#### UserBannerPreferenceRepository
- `findOrCreate(user, banner)` - Get or create preference
- `isBannerHidden(user, banner)` - Check if banner is hidden
- `getHiddenBanners(user)` - Get user's hidden banners

### 6. **Admin Dashboard Views**

#### Index Page (`admin/banner/index.html.twig`)
- Statistics cards (active, total, by type)
- Search and filter capabilities
- Full banner list with actions
- Pagination support
- Quick actions (edit, delete, toggle status)

#### Create/Edit Form (`admin/banner/form.html.twig`)
- All banner properties
- Target audience selection
- Scheduling options
- Help section with tips

#### Details Page (`admin/banner/details.html.twig`)
- Complete banner information
- List of users who dismissed it
- Dismissal statistics
- Reset preferences option
- Quick action buttons

#### Preferences Page (`admin/banner/preferences.html.twig`)
- User preference list with filtering
- Dismissal status and date
- Individual user reset options
- Dismissal rate statistics

#### Statistics Page (`admin/banner/stats.html.twig`)
- Overview statistics
- Distribution by type, position, status
- Dismissal rates per banner
- Interactive progress bars
- Optimization tips

### 7. **User Profile View** (`banner/preferences.html.twig`)
- List of visible banners
- List of hidden banners with dismissal dates
- Show/hide toggle for each banner
- Bulk reset option
- Clean, user-friendly interface

### 8. **Forms**

#### BannerType Form (`src/Form/BannerType.php`)
- Title input
- Content textarea
- Type select
- Position select
- Status select
- Priority number input
- Start/End date pickers
- Link URL and text
- Target audience multi-select

## Frontend Integration

### JavaScript Integration
The banner component now includes:

```javascript
// Checks user authentication status
// Loads preferences from server for authenticated users
// Falls back to localStorage for guests
// Handles dismissal with server persistence
// Shows/hides banners based on preferences
// Smooth animations for dismiss actions
```

### Client-Side Storage
- **Authenticated Users**: Database (persistent across devices)
- **Anonymous Users**: localStorage (device-specific)
- **Automatic Sync**: Preferences sync on page load

## Admin Panel Integration

The system integrates with Easy Admin bundle:
- Menu items for Banner Management
- Dashboard widgets showing banner stats
- Quick actions from dashboard
- Consistent UI with admin theme

## Usage Examples

### Creating a Banner
1. Navigate to `/admin/banner`
2. Click "Create Banner"
3. Fill in details (title, content, type, etc.)
4. Set dates for scheduling
5. Choose target audience
6. Set status and save

### Managing User Preferences
1. Go to Banner Details
2. View list of users who dismissed it
3. Click "Reset" to force-show to specific users
4. Or "Reset All" to reset for everyone

### Analytics
1. Navigate to `/admin/banner/stats`
2. View dismissal rates by banner
3. Analyze engagement patterns
4. Optimize banner content based on metrics

### User Side
1. User can access `/profile/banner-preferences`
2. See all banners and their visibility status
3. Toggle individual banners
4. Reset all preferences at once

## Database Migrations

Run migrations to set up tables:
```bash
php bin/console doctrine:migrations:migrate
```

## Routes Configuration

Routes are automatically configured via attributes:
```php
#[Route('/admin/banner', name: 'app_admin_banner_index')]
#[Route('/admin/banner/create', name: 'app_admin_banner_create')]
#[Route('/admin/banner/edit/{id}', name: 'app_admin_banner_edit')]
// ... etc
```

## Security

- All admin routes require `ROLE_ADMIN`
- CSRF token validation on all POST operations
- User can only manage their own preferences
- Preference reset requires confirmation

## Permissions

- **Admin**: Full CRUD + preference management
- **User**: View preferences + manage their own
- **Guest**: View banners + localStorage-based preferences

## Performance Considerations

- Banners are cached in Symfony cache
- Preferences loaded once per session
- Pagination to prevent large queries
- Database indexes on user/banner/status fields

## Future Enhancements

1. A/B Testing for banners
2. Scheduled dismissals
3. Banner templates
4. Advanced targeting (location, device, etc.)
5. Email notifications for important banners
6. Banner impressions tracking
7. Custom styling per banner
8. Analytics export to CSV

## Files Modified/Created

### Controllers
- `src/Controller/BannerController.php` - Enhanced with full CRUD

### Entities
- `src/Entity/Banner.php` - Existing
- `src/Entity/UserBannerPreference.php` - Existing

### Repositories
- `src/Repository/BannerRepository.php` - Updated with proper statistics
- `src/Repository/UserBannerPreferenceRepository.php` - Existing

### Forms
- `src/Form/BannerType.php` - New

### Templates
- `templates/admin/banner/index.html.twig` - Updated
- `templates/admin/banner/form.html.twig` - New
- `templates/admin/banner/details.html.twig` - New
- `templates/admin/banner/preferences.html.twig` - New
- `templates/admin/banner/stats.html.twig` - Updated
- `templates/banner/preferences.html.twig` - New (user-facing)
- `templates/components/_banners.html.twig` - Updated with API integration

## Testing

To test the banner system:

1. **Create a test banner**
   - Admin → Banner Management → Create
   - Fill all fields
   - Set status to Active
   - Click Create

2. **Verify it displays**
   - Visit homepage
   - Should see banner in designated position

3. **Test dismissal**
   - Click dismiss/close button
   - Banner should hide
   - Refresh page - should still be hidden

4. **Check admin statistics**
   - Go to Banner Details
   - Should show 1 dismissal

5. **Reset preferences**
   - Admin → Preferences
   - Click Reset for user
   - Page refresh shows banner again

## Support

For issues or questions about the banner system, refer to the controller comments and template documentation.
