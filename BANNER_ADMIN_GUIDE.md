# Banner Management - Quick Admin Guide

## Accessing Banner Management

**URL:** `/admin/banner`

**Requirements:** Admin role (ROLE_ADMIN)

---

## Main Dashboard Features

### 1. Statistics Overview
- **Bannières Actives** - Count of currently active banners
- **Total Bannières** - All banners in system
- **Promotions** - Marketing/promotional banners
- **Avertissements** - Warning/alert banners

### 2. Quick Filters
- **Recherche** - Search by title or content
- **Statut** - Filter by status (Active, Inactive, Scheduled, Expired)
- **Type** - Filter by banner type
- **Pagination** - Navigate through banners

---

## Creating a Banner

1. Click **"+ Créer une bannière"** button
2. Fill in the form:
   - **Titre** - Banner headline (required)
   - **Contenu** - Banner message (optional)
   - **Type** - Choose from:
     - Promotion (green badge)
     - Warning (yellow badge)
     - Announcement (blue badge)
     - Information (primary badge)
   - **Position** - Where banner appears:
     - En haut (Top of page)
     - En bas (Bottom of page)
     - Barre latérale (Sidebar)
     - Popup (Modal popup)
   - **Statut** - Start as:
     - Actif (Show immediately)
     - Inactif (Don't show)
     - Programmé (Schedule for later)
     - Expiré (Archived)
   - **Priorité** - 1-100 (higher = shows first)
   - **Dates** - Schedule start/end dates
   - **Lien** - Optional URL for CTA button
   - **Public cible** - Who sees it:
     - Tous (Everyone)
     - ROLE_USER (Logged-in users)
     - ROLE_ADMIN (Admins only)
     - guest (Visitors)
3. Click **"Créer la Bannière"**

---

## Editing a Banner

1. Find banner in list
2. Click **Edit button** (pencil icon)
3. Modify any fields
4. Click **"Mettre à jour la Bannière"**

---

## Deleting a Banner

1. Find banner in list
2. Click **Delete button** (trash icon)
3. Confirm deletion

---

## Activating/Deactivating

**To Activate:**
- Click **Green checkmark** (✓) button
- Banner becomes visible

**To Deactivate:**
- Click **Red X** button
- Banner becomes hidden

---

## Viewing Banner Details

1. Click **Info circle** (ⓘ) button
2. See:
   - Complete banner information
   - Number of users who dismissed it
   - List of users with dismiss dates
   - Quick action buttons

---

## Managing User Preferences

### View Who Dismissed Banner
1. Go to Banner Details
2. Scroll to "Utilisateurs qui ont masqué cette bannière"
3. See list with:
   - User name
   - Email
   - When they dismissed it

### Reset for One User
1. In dismissal list
2. Click **Sync button** (↻) next to user
3. Preference resets - banner will show to them again

### Reset for All Users
1. Go to Banner Details
2. Click **"Réinitialiser tous"** button
3. Confirm action
4. All users see banner again

---

## Advanced Preference Management

**Full Preference Page:** `/admin/banner/{bannerId}/preferences`

- Filter by "Uniquement les utilisateurs qui ont masqué"
- View all user preferences
- See dismissal status for each user
- Bulk manage who has hidden it

---

## Analytics & Statistics

**URL:** `/admin/banner/stats`

### View:
- **Distribution by Type** - How many of each type
- **Distribution by Position** - Where banners are placed
- **Distribution by Status** - Active/Inactive/Scheduled counts
- **Dismissal Rates** - % of users hiding each banner

### Dismissal Rate Interpretation:
- **Green (< 40%)** - Banner performing well
- **Yellow (40-70%)** - Banner may need improvement
- **Red (> 70%)** - Consider revising banner

---

## Banner Preview

1. Click **Eye button** (👁) from list
2. See how banner looks to users
3. Preview different positions and types

---

## Common Tasks

### Task: Create Urgent Alert
```
Type: Warning
Position: Top/Popup
Status: Active
Priority: 100
Target: All
Content: Your urgent message
```

### Task: Promotional Banner (Logged-In Users Only)
```
Type: Promotion
Position: Top
Status: Active
Priority: 50
Target: ROLE_USER
Date: Set start/end dates
Link: Set promotional URL
```

### Task: Scheduled Announcement
```
Type: Announcement
Status: Scheduled
Start Date: Future date/time
End Date: When to remove
Content: Announcement text
```

### Task: Hide Banner from One User
```
Go to Banner Details
Find user in list
Click Reset/Sync button
```

---

## Status Explanations

| Status | Meaning | User Sees |
|--------|---------|-----------|
| **Actif** | Currently live | ✓ Yes |
| **Inactif** | Not shown | ✗ No |
| **Programmé** | Scheduled for later | ✗ Not yet |
| **Expiré** | Past end date | ✗ No |

---

## Type Explanations

| Type | Use Case | Icon Color |
|------|----------|-----------|
| **Promotion** | Special offers, discounts | 🟢 Green |
| **Warning** | Important warnings, alerts | 🟡 Yellow |
| **Announcement** | News, new features | 🔵 Blue |
| **Information** | General information | 🟣 Purple |

---

## Position Explanations

| Position | Display Location |
|----------|-----------------|
| **En haut** | Top of page, before content |
| **En bas** | Bottom of page |
| **Barre latérale** | Right/left sidebar |
| **Popup** | Modal popup overlay |

---

## Tips & Best Practices

✅ **DO:**
- Use clear, concise titles
- Set end dates for temporary banners
- Vary banner types and positions
- Monitor dismissal rates
- Update banners regularly
- Use appropriate priority levels
- Target specific user groups

❌ **DON'T:**
- Create too many banners at once
- Use overly long content
- Forget to set end dates
- Ignore high dismissal rates
- Use same banner for months
- Put high-priority banners in sidebar

---

## User Experience

**What Users See:**
- Banners appear based on their role
- Can dismiss banners with close button
- Dismissed banners don't appear until reset
- Preferences saved to their account
- Can reset all preferences in profile

**User Preference Page:** `/profile/banner-preferences`
- Users can view/manage their own preferences
- See which banners they've hidden
- Reset individual or all banners

---

## Troubleshooting

**Banner Not Showing?**
- Check Status (must be "Actif")
- Check dates (must be within start/end)
- Check target audience (user matches criteria)
- Check if user has hidden it (reset preferences)

**Users Complaining About Banner?**
- Check dismissal rate (maybe it's annoying)
- Consider changing position/type
- Add end date to remove it
- Or deactivate it

**Lost Banner?**
- Use Search to find by title
- Filter by type/status
- Check all pages with pagination

---

## Links
- 📊 [View Stats](/admin/banner/stats)
- 📋 [List All Banners](/admin/banner)
- ➕ [Create New Banner](/admin/banner/create)
- 👤 [User Preferences in Profile](/profile/banner-preferences)
