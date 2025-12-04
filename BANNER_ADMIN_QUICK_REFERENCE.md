# Banner Management Quick Reference

## Access Banner Management
- URL: `http://localhost:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CBannerCrudController`
- Or navigate through admin dashboard → Bannières

## Quick Actions Toolbar

| Action | Icon | Purpose | When Available |
|--------|------|---------|-----------------|
| **Activate** | ⚡ Power | Make banner active now | If Inactive/Scheduled |
| **Deactivate** | 🚫 Ban | Hide banner from users | If Active |
| **Preview** | 👁️ Eye | See how users will see it | Always |
| **Statistics** | 📊 Chart | View dismissal metrics | Detail view only |
| **Reset** | 🔄 Refresh | Clear user dismissals | Detail view only |

## Creating a Banner

### Step 1: Click "Créer une bannière"
- Fill in required fields: Title, Type, Position, Status
- Add optional content: Images, links, HTML

### Step 2: Configure Display Timing
- **Start Date**: When to show (leave empty for immediate)
- **End Date**: When to hide (leave empty for indefinite)
- **Priority**: 1-100 (higher = shown first)

### Step 3: Set Target Audience
Enter JSON array of roles:
```json
["guest", "ROLE_USER", "ROLE_ADMIN"]
```
Leave empty for all users.

### Step 4: Preview & Save
- Click "Preview" to see how it looks
- Click "Save" to publish

## Banner Status

| Status | Meaning | Auto-Change |
|--------|---------|-------------|
| 🟢 Active | Showing to users | → Expired (after end date) |
| ⚪ Inactive | Hidden | Manual activation only |
| 🟡 Scheduled | Waiting for start date | → Active (when start date reached) |
| 🔴 Expired | Past end date | Manual reactivation only |

## Banner Types

| Type | Color | Use For |
|------|-------|---------|
| Promotion | 🟢 Green | Sales, offers, special deals |
| Warning | 🟡 Yellow | Important alerts, cautions |
| Announcement | 🔵 Blue | News, updates, information |
| Info | 🔷 Purple | General information |

## Common Scenarios

### Promote a New Feature
1. Create banner, set Type="Announcement"
2. Set Status="Scheduled", Start Date=tomorrow at 9 AM
3. Set Priority=95 (high)
4. Set Target Audience=["ROLE_USER"] (for users only)
5. Preview and Save

### Fix High Dismissal Rate
1. Open banner detail page
2. Click "Statistics" to view dismissal metrics
3. Click "Réinit. préférences" to show banner again to everyone
4. Monitor statistics after reset

### Hide Banner Immediately
1. Click "Désactiver" button
2. Status changes to "Inactive"
3. Banner hidden from all users immediately

### Check Banner Appearance
1. Click "Preview" button
2. See exactly how banner renders
3. Verify styling, position, content
4. Go back and edit if needed

## Filtering Banners

Use filters at top of list:
- **Title**: Search by name
- **Type**: Filter by category
- **Position**: Filter by location
- **Status**: Filter by state
- **Start/End Date**: Filter by schedule
- **Created By**: Filter by creator

## Statistics Interpretation

### Dismissal Rate
- **0-20%**: Engagement successful, users find it relevant
- **20-50%**: Moderate interest, consider refreshing
- **50-80%**: Low engagement, might be irrelevant
- **80%+**: Very low engagement, consider retiring

### Metrics Shown
- **Total Views**: Users who saw the banner
- **Dismissed**: Users who clicked X
- **Kept Visible**: Users who didn't dismiss
- **Dismissal Rate %**: (Dismissed ÷ Total) × 100

## Tips & Tricks

💡 **Tip 1**: Use dates for seasonal promotions
- Create now, schedule for future dates
- Set end date to automatically hide after event

💡 **Tip 2**: Monitor statistics regularly
- High dismissal = might need content refresh
- Low dismissal = banner resonating well with audience

💡 **Tip 3**: Use Preview before publishing
- See exactly what users will see
- Catch styling or content issues early
- Verify links work correctly

💡 **Tip 4**: Be selective with banners
- Aim for 3-5 active banners maximum
- Too many = banner fatigue
- Users dismiss more when overwhelmed

💡 **Tip 5**: Use role-based targeting
- Admin-only messages: ["ROLE_ADMIN"]
- New user onboarding: ["ROLE_USER"]
- Guest promotions: ["guest"]

## Target Audience Examples

```json
// All users
[]

// Only authenticated users
["ROLE_USER"]

// Only administrators
["ROLE_ADMIN"]

// Users and moderators
["ROLE_USER", "ROLE_MODERATOR"]

// Everyone including guests
["guest", "ROLE_USER", "ROLE_ADMIN"]
```

## Keyboard Shortcuts
- **Tab**: Navigate between fields
- **Enter**: Save form
- **Escape**: Close popup/preview

## Batch Operations
- Select multiple banners with checkboxes
- Use bulk actions dropdown to:
  - Delete multiple banners
  - (Future: activate/deactivate groups)

## Performance Notes
- Banners displayed by position in frontend
- Dismissed banners retrieved from user preferences
- Statistics calculated in real-time
- Large image files may slow page loads (optimize before upload)

## Common Errors

| Error | Solution |
|-------|----------|
| "End date before start date" | Ensure start date < end date |
| "Invalid JSON in target audience" | Check format: `["ROLE_USER", "ROLE_ADMIN"]` |
| "Image too large" | Compress image before upload |
| "Banner not showing" | Verify status=Active, date range correct, audience matches |

## Support

For issues or questions:
1. Check Statistics to see if banner is being viewed
2. Use Preview to verify appearance
3. Check target audience configuration
4. Review browser console for JavaScript errors

---
**Last Updated**: January 2025
**System Version**: 3.0 - Enhanced Admin Management
