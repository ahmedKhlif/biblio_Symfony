# Banner Management - Routes Reference

## Admin Routes

### List & Dashboard
```
GET  /admin/banner
Name: app_admin_banner_index
Template: admin/banner/index.html.twig
Description: List all banners with stats and filtering
Requires: ROLE_ADMIN
```

### Create
```
GET  /admin/banner/create
POST /admin/banner/create
Name: app_admin_banner_create
Template: admin/banner/form.html.twig
Description: Create new banner
Requires: ROLE_ADMIN
```

### Edit
```
GET  /admin/banner/edit/{id}
POST /admin/banner/edit/{id}
Name: app_admin_banner_edit
Template: admin/banner/form.html.twig
Description: Edit existing banner
Requires: ROLE_ADMIN
Param: id = Banner ID
```

### Delete
```
POST /admin/banner/delete/{id}
Name: app_admin_banner_delete
Description: Delete banner
Requires: ROLE_ADMIN
CSRF Token: delete{id}
Param: id = Banner ID
```

### Activate
```
POST /admin/banner/activate/{id}
Name: app_admin_banner_activate
Description: Activate banner (make visible)
Requires: ROLE_ADMIN
CSRF Token: activate{id}
Param: id = Banner ID
```

### Deactivate
```
POST /admin/banner/deactivate/{id}
Name: app_admin_banner_deactivate
Description: Deactivate banner (hide)
Requires: ROLE_ADMIN
CSRF Token: deactivate{id}
Param: id = Banner ID
```

### Preview
```
GET /admin/banner/preview/{id}
Name: app_admin_banner_preview
Template: admin/banner/preview.html.twig
Description: Preview how banner looks
Requires: ROLE_ADMIN
Param: id = Banner ID
```

### Details & Dismissals
```
GET /admin/banner/details/{id}
Name: app_admin_banner_details
Template: admin/banner/details.html.twig
Description: View banner details and users who dismissed it
Requires: ROLE_ADMIN
Param: id = Banner ID
Query: page (pagination)
```

### Preferences Management
```
GET /admin/banner/preferences/{id}
Name: app_admin_banner_preferences
Template: admin/banner/preferences.html.twig
Description: Manage user preferences for specific banner
Requires: ROLE_ADMIN
Param: id = Banner ID
Query: page, dismissed_only (filter)
```

### Reset All Preferences
```
POST /admin/banner/reset-preferences/{id}
Name: app_admin_banner_reset_preferences
Description: Reset all user preferences for banner
Requires: ROLE_ADMIN
CSRF Token: reset{id}
Param: id = Banner ID
Effect: All users see banner again
```

### Reset User Preference
```
POST /admin/banner/reset-user-preference/{bannerId}/{userId}
Name: app_admin_banner_reset_user
Description: Reset preference for specific user
Requires: ROLE_ADMIN
CSRF Token: reset_user
Param: bannerId = Banner ID, userId = User ID
Effect: Specific user sees banner again
```

### Statistics
```
GET /admin/banner/stats
Name: app_admin_banner_stats
Template: admin/banner/stats.html.twig
Description: View analytics and dismissal rates
Requires: ROLE_ADMIN
Shows: Charts, statistics, dismissal rates
```

---

## User Routes

### User Preferences
```
GET /profile/banner-preferences
Name: app_banner_preferences
Template: banner/preferences.html.twig
Description: User view/manage their banner preferences
Requires: ROLE_USER
Shows: Visible and hidden banners
```

### Hide Banner
```
POST /api/banner/hide/{id}
Name: api_banner_hide
Description: Hide banner for authenticated user
Requires: ROLE_USER
CSRF Token: hide_banner
Param: id = Banner ID
Response: JSON { success: true/false }
```

### Show Banner
```
POST /api/banner/show/{id}
Name: api_banner_show
Description: Show previously hidden banner
Requires: ROLE_USER
CSRF Token: show_banner
Param: id = Banner ID
Response: JSON { success: true/false }
```

### Get Preferences
```
GET /api/banner-preferences
Name: api_banner_preferences
Description: Get list of hidden banners for user
Requires: ROLE_USER
Response: JSON { hiddenBannerIds: [...] }
```

### Reset All Preferences
```
POST /api/banner/reset-all
Name: api_banner_reset_all
Description: Reset all hidden banners
Requires: ROLE_USER
CSRF Token: reset_all_banners
Response: JSON { success: true/false, message: "..." }
```

---

## Route Parameters

### Path Parameters
| Param | Type | Example | Description |
|-------|------|---------|-------------|
| `{id}` | integer | 1 | Banner ID |
| `{bannerId}` | integer | 1 | Banner ID |
| `{userId}` | integer | 42 | User ID |

### Query Parameters
| Param | Type | Example | Description |
|-------|------|---------|-------------|
| `page` | integer | 2 | Page number for pagination |
| `status` | string | active | Filter by status |
| `type` | string | promotion | Filter by type |
| `search` | string | offer | Search term |
| `dismissed_only` | boolean | 1 | Show only dismissed preferences |

---

## CSRF Tokens Required

### Form Tokens
```
{{ csrf_token('activate' ~ banner.id) }}
{{ csrf_token('deactivate' ~ banner.id) }}
{{ csrf_token('delete' ~ banner.id) }}
{{ csrf_token('reset' ~ banner.id) }}
{{ csrf_token('reset_user') }}
```

### API Tokens
```
{{ csrf_token('hide_banner') }}
{{ csrf_token('show_banner') }}
{{ csrf_token('reset_all_banners') }}
```

---

## Response Examples

### Get Preferences Response
```json
{
  "hiddenBannerIds": [1, 3, 5],
  "count": 3
}
```

### Hide Banner Response
```json
{
  "success": true,
  "message": "Banner hidden successfully"
}
```

### Error Response
```json
{
  "success": false,
  "error": "Banner not found",
  "code": 404
}
```

---

## Route URLs in Templates

### Admin Routes
```twig
{{ path('app_admin_banner_index') }}
{{ path('app_admin_banner_create') }}
{{ path('app_admin_banner_edit', {id: banner.id}) }}
{{ path('app_admin_banner_delete', {id: banner.id}) }}
{{ path('app_admin_banner_activate', {id: banner.id}) }}
{{ path('app_admin_banner_deactivate', {id: banner.id}) }}
{{ path('app_admin_banner_preview', {id: banner.id}) }}
{{ path('app_admin_banner_details', {id: banner.id}) }}
{{ path('app_admin_banner_preferences', {id: banner.id}) }}
{{ path('app_admin_banner_reset_preferences', {id: banner.id}) }}
{{ path('app_admin_banner_stats') }}
```

### User Routes
```twig
{{ path('app_banner_preferences') }}
{{ path('api_banner_hide', {id: banner.id}) }}
{{ path('api_banner_show', {id: banner.id}) }}
{{ path('api_banner_preferences') }}
{{ path('api_banner_reset_all') }}
```

---

## HTTP Methods

| Route | Method | Purpose |
|-------|--------|---------|
| `/admin/banner` | GET | List banners |
| `/admin/banner/create` | GET | Show form |
| `/admin/banner/create` | POST | Save new |
| `/admin/banner/edit/{id}` | GET | Show form |
| `/admin/banner/edit/{id}` | POST | Save changes |
| `/admin/banner/delete/{id}` | POST | Delete |
| `/admin/banner/activate/{id}` | POST | Activate |
| `/admin/banner/deactivate/{id}` | POST | Deactivate |
| `/admin/banner/preview/{id}` | GET | Preview |
| `/admin/banner/details/{id}` | GET | View details |
| `/admin/banner/preferences/{id}` | GET | Manage prefs |
| `/admin/banner/reset-preferences/{id}` | POST | Reset all |
| `/admin/banner/reset-user-preference/{bid}/{uid}` | POST | Reset user |
| `/admin/banner/stats` | GET | View stats |
| `/profile/banner-preferences` | GET | User prefs |
| `/api/banner-preferences` | GET | Get hidden list |
| `/api/banner/hide/{id}` | POST | Hide banner |
| `/api/banner/show/{id}` | POST | Show banner |
| `/api/banner/reset-all` | POST | Reset all |

---

## Status Codes

| Code | Meaning | Common Cause |
|------|---------|-------------|
| 200 | OK | Successful GET/POST |
| 201 | Created | Successfully created |
| 302 | Redirect | After successful action |
| 400 | Bad Request | Invalid form data |
| 401 | Unauthorized | Not logged in |
| 403 | Forbidden | Not admin |
| 404 | Not Found | Banner/User not found |
| 405 | Method Not Allowed | Wrong HTTP method |
| 409 | Conflict | Unique constraint |
| 500 | Server Error | Server issue |

---

## Common Workflows

### Create & Activate Banner
```
1. GET  /admin/banner/create
2. POST /admin/banner/create (fill form)
3. POST /admin/banner/activate/{id}
4. Verify on GET /admin/banner
```

### Hide Banner (User)
```
1. POST /api/banner/hide/{id}
2. Check success response
3. Banner disappears on page
4. Persisted in database
```

### Reset User Preference (Admin)
```
1. GET  /admin/banner/details/{id}
2. Find user in dismissal list
3. POST /admin/banner/reset-user-preference/{bid}/{uid}
4. Verify success message
```

### View Analytics
```
1. GET /admin/banner/stats
2. Review dismissal rates
3. Identify problematic banners
4. Make decisions on content
```

---

## Quick Links for Development

**Banner Index:** `/admin/banner`
**Create Banner:** `/admin/banner/create`
**Statistics:** `/admin/banner/stats`
**User Preferences:** `/profile/banner-preferences`

---

*Last Updated: December 3, 2025*
