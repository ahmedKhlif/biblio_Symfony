# Banner Management System - Admin Guide

## Overview

The enhanced Banner Management System provides comprehensive control over system-wide banners through the EasyAdmin panel. This system allows administrators to create, schedule, manage, and analyze the performance of banners displayed to users.

## Features

### 1. **Advanced CRUD Operations**
- **Create**: Add new banners with full customization
- **Read**: View banner details with comprehensive information
- **Update**: Modify existing banners at any time
- **Delete**: Remove banners from the system

### 2. **Banner Types**
- **Promotion** (Green badge): Special offers and promotions
- **Warning** (Yellow badge): Important warnings or alerts
- **Announcement** (Blue badge): General announcements
- **Info** (Primary badge): General information

### 3. **Banner Positions**
- **Top**: Displayed at the top of the page
- **Bottom**: Displayed at the bottom of the page
- **Sidebar**: Displayed in the sidebar
- **Popup**: Displayed as a modal popup

### 4. **Banner Status Management**
- **Active**: Currently displayed to users
- **Inactive**: Hidden from users
- **Scheduled**: Will become active at a specified time
- **Expired**: No longer active (past end date)

### 5. **Advanced Filtering**
Access the admin panel and filter banners by:
- **Title**: Search by banner title
- **Type**: Filter by promotion, warning, announcement, or info
- **Position**: Filter by display position
- **Status**: Filter by active, inactive, scheduled, or expired
- **Date Range**: Filter by creation or modification date
- **Creator**: Filter by the admin who created the banner

### 6. **Quick Actions**
Located in the banner list and detail views:

#### Activate Button
- Immediately activates a banner
- Changes status from Inactive/Scheduled to Active
- Only shown if banner is not already active

#### Deactivate Button
- Immediately deactivates a banner
- Changes status from Active to Inactive
- Only shown if banner is currently active

#### Preview Button
- Opens a preview of how the banner will appear to users
- Shows full styling, content, and layout
- Displays banner metadata (type, position, priority, etc.)

#### Statistics Button
- Shows dismissal metrics for the banner
- Displays:
  - Total users who viewed the banner
  - Number of users who dismissed it
  - Dismissal rate (percentage)
  - Number of users who kept it visible
  - Visual chart of dismissal statistics
- Only visible from detail view

#### Reset Preferences Button
- Clears all user dismissal preferences for the banner
- Forces banner to reappear for all users who previously dismissed it
- Only visible from detail view
- Useful for re-promoting banners or after fixing issues

### 7. **Scheduling System**

#### Start Date
- When set: Banner becomes active at specified datetime
- When empty: Banner uses current status immediately
- Status automatically changes from "Scheduled" to "Active" when start date is reached

#### End Date
- When set: Banner automatically expires at specified datetime
- When empty: Banner remains active indefinitely
- Status automatically changes to "Expired" when end date is passed

#### Priority
- Numeric value (1-100) determining display order
- Higher numbers = higher priority (displayed first)
- Banners with same priority sorted by creation date

### 8. **Targeting System**

#### Target Audience
Configure which users can see the banner:

**Format**: JSON array of roles
```json
["guest", "ROLE_USER", "ROLE_ADMIN", "ROLE_MODERATOR"]
```

**Available Values**:
- `guest`: Anonymous users (not logged in)
- `ROLE_USER`: Authenticated users
- `ROLE_ADMIN`: Administrator users
- `ROLE_MODERATOR`: Moderator users
- Custom roles as defined in your system

**When Empty**: Banner is visible to all users regardless of role

### 9. **Content Configuration**

#### Title (Required)
- Up to 255 characters
- Displayed as the banner heading
- Searchable in admin panel

#### Content (Optional)
- Rich text editor support
- HTML and formatted text supported
- Displayed in banner body

#### Image (Optional)
- Upload banner image
- Supports common image formats (PNG, JPG, GIF, WebP)
- Displays in banner preview
- Stored in `/public/uploads/banners/`

#### Link (Optional)
- URL target for banner action button
- Can be internal or external
- Validates as proper URL format

#### Link Text (Optional)
- Button text displayed for the link
- Examples: "Learn More", "Sign Up", "View Details"
- Defaults to link URL if not specified

### 10. **User Dismissal Preferences**

#### How It Works
- When users click the close button (X) on a banner, a preference is recorded
- Banner is hidden for that user locally (JavaScript) and on server
- Preference persists across sessions (database storage)
- Anonymous users get localStorage fallback

#### Statistics Tracking
- Database tracks each user's dismissal via `UserBannerPreference` entity
- Stores: user, banner, hidden status, hidden timestamp
- Admins can view dismissal statistics per banner
- Preferences can be reset to force banner re-display

## Admin Panel Navigation

### Banner Management Routes

| Route | URL | Description |
|-------|-----|-------------|
| List Banners | `/admin/banner` | View all banners |
| Create Banner | `/admin/banner/new` | Create new banner |
| Edit Banner | `/admin/banner/{id}/edit` | Modify existing banner |
| View Details | `/admin/banner/{id}` | View full banner details |
| Preview | `/admin/banner/{id}/preview` | See how banner looks |
| Statistics | `/admin/banner/{id}/statistics` | View dismissal stats |
| Delete Banner | Delete action on detail | Remove banner |

### Action Flow

```
List View
    ├── Activate/Deactivate (quick toggle)
    ├── Preview (see rendering)
    ├── Statistics (view metrics)
    ├── Edit (modify)
    └── Delete (remove)

Detail View
    ├── Activate/Deactivate (quick toggle)
    ├── Preview (see rendering)
    ├── Statistics (view metrics)
    ├── Reset Preferences (clear dismissals)
    ├── Edit (modify)
    └── Delete (remove)

Create/Edit View
    ├── Title (required)
    ├── Content (optional, rich text)
    ├── Type (required)
    ├── Position (required)
    ├── Status (required)
    ├── Start Date (optional)
    ├── End Date (optional)
    ├── Priority (optional, 1-100)
    ├── Image (optional)
    ├── Link (optional)
    ├── Link Text (optional)
    └── Target Audience (optional, JSON array)
```

## Best Practices

### 1. **Scheduling Promotions**
- Use "Scheduled" status with start/end dates
- Set priority higher than regular announcements
- Target specific user roles if needed
- Use Preview button to verify appearance

### 2. **Managing Dismissal Fatigue**
- Monitor dismissal rates via Statistics
- High dismissal rates may indicate:
  - Irrelevant content for target users
  - Banner placement issues
  - Overly aggressive promotion
- Consider resetting preferences only when:
  - Content has significantly changed
  - New important information is added
  - Banner has been inactive for extended period

### 3. **Effective Targeting**
- Use role-based targeting for role-specific messages
- Leave empty for site-wide announcements
- Test with preview before publishing

### 4. **Performance Optimization**
- Archive old expired banners (delete if no longer needed)
- Avoid excessive active banners (3-5 recommended maximum)
- Use pagination to manage large lists
- Sort by priority to see high-priority items first

### 5. **Content Quality**
- Keep titles concise and clear
- Use rich content for complex information
- Include images for visual impact
- Always provide descriptive link text
- Proofread before publishing

## Troubleshooting

### Banner Not Showing
1. Check status is "Active"
2. Verify start/end dates are correct
3. Check target audience includes current user role
4. Check if user has dismissed the banner (Statistics view)
5. Clear browser cache if recently updated

### Users Still See Dismissed Banner
1. User preferences stored in database
2. Use "Reset Preferences" to clear dismissals
3. Force browser cache clear on client side

### Scheduling Not Working
1. Check server time is correct
2. Verify date format is correct
3. Ensure start date is before end date
4. Check status is "Scheduled" (not "Active")

### Statistics Not Showing
1. Banner must have been seen by at least one user
2. Stats update in real-time as users interact
3. Anonymous user dismissals tracked via localStorage

## Database Entities

### Banner Entity
- `id`: Unique identifier
- `title`: Banner title
- `content`: HTML content
- `type`: Banner type (promotion, warning, announcement, info)
- `position`: Display position (top, bottom, sidebar, popup)
- `status`: Current status (active, inactive, scheduled, expired)
- `startDate`: Activation datetime
- `endDate`: Expiration datetime
- `image`: Image filename
- `link`: Target URL
- `linkText`: Button text
- `priority`: Display priority (1-100)
- `targetAudience`: JSON array of roles
- `styling`: JSON array of CSS customizations
- `createdAt`: Creation timestamp
- `updatedAt`: Last modification timestamp
- `createdBy`: Admin user who created

### UserBannerPreference Entity
- `id`: Unique identifier
- `user`: Reference to User
- `banner`: Reference to Banner
- `hidden`: Boolean flag (true if dismissed)
- `hiddenAt`: Dismissal timestamp

## API Integration

The banner system integrates with API endpoints for frontend operations:

- `POST /api/banner/hide/{id}`: Hide banner for current user
- `POST /api/banner/show/{id}`: Show banner for current user
- `GET /api/banner/preferences`: Get user's current preferences

Frontend JavaScript automatically handles these API calls when users interact with banners.

## Security

- All banner operations require ROLE_ADMIN
- User preferences are personalized per user
- CSRF tokens required for state-changing operations
- HTML content sanitized to prevent XSS

## Version History

- **v1.0**: Initial release with basic CRUD
- **v2.0**: Added scheduling, targeting, and statistics
- **v3.0**: Enhanced admin interface with preview and reset preferences
