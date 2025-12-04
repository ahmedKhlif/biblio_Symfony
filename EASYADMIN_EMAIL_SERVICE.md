# Email Service Integration in EasyAdmin

## Overview

Your Symfony Biblio application already has a fully functional **EmailService** with Gmail SMTP configured. You can easily integrate it into EasyAdmin controllers to send emails.

---

## Current Configuration

### 1. Mailer Setup (Already Configured)

**File:** `config/packages/mailer.yaml`
```yaml
framework:
    mailer:
        dsn: '%env(MAILER_DSN)%'
```

**File:** `.env`
```
MAILER_DSN=smtp://khlifahmed9@gmail.com:gncipbkkjkrmsogm@smtp.gmail.com:587
```

### 2. EmailService Configuration

**File:** `config/services.yaml`
```yaml
App\Service\EmailService:
    arguments:
        $fromEmail: '%app.email.from_address%'
        $fromName: '%app.email.from_name%'
```

---

## Available Email Methods

The `EmailService` (located at `src/Service/EmailService.php`) provides these methods:

### User-Related Emails
- `sendVerificationEmail(User $user)` - Email verification
- `sendWelcomeEmail(User $user)` - Welcome message
- `sendPasswordResetEmail(User $user, string $resetToken)` - Password reset
- `sendReadingGoalAchievedEmail(User $user, array $achievedGoals)` - Goal achievement
- `sendWeeklyReadingSummary(User $user, array $stats)` - Weekly stats
- `sendBookRecommendation(User $user, array $recommendations)` - Book recommendations

### Order-Related Emails
- `sendOrderConfirmationEmail(Order $order)` - Order confirmation
- `sendOrderStatusUpdateEmail(Order $order)` - Status update
- `sendOrderShippedEmail(Order $order)` - Shipment notification
- `sendOrderDeliveredEmail(Order $order)` - Delivery notification
- `sendOrderCancelledEmail(Order $order)` - Cancellation notification
- `sendNewOrderNotificationToAdmins(Order $order)` - Admin notification

### Admin Alerts
- `sendLowStockAlert(array $lowStockBooks)` - Low stock warning
- `sendRoleBasedNotification(string $notificationType, array $data)` - Generic notifications

### Bulk Operations
- `sendWeeklySummaries()` - Send summaries to all users
- `sendBookRecommendations()` - Send recommendations to all users

---

## How to Use in EasyAdmin

### Option 1: Send Email from a CRUD Controller

**Example: UserCrudController.php**

```php
<?php

namespace App\Controller\Admin;

use App\Service\EmailService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private EmailService $emailService)
    {
    }

    public static function getEntityFqcn(): string
    {
        return \App\Entity\User::class;
    }

    // Send welcome email to newly created user
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        parent::persistEntity($em, $entityInstance);
        
        // Send welcome email after user is created
        if ($entityInstance instanceof \App\Entity\User) {
            $this->emailService->sendWelcomeEmail($entityInstance);
        }
    }
}
```

### Option 2: Send Email from Dashboard Controller

**Example: DashboardController.php**

```php
<?php

namespace App\Controller\Admin;

use App\Service\EmailService;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EmailService $emailService,
        private EntityManagerInterface $entityManager
    ) {}

    public function index(): Response
    {
        // Send low stock alerts to admins
        $lowStockBooks = $this->entityManager->getRepository(Livre::class)
            ->createQueryBuilder('l')
            ->where('l.nbExemplaires < 5')
            ->getQuery()
            ->getResult();

        if (!empty($lowStockBooks)) {
            $this->emailService->sendLowStockAlert($lowStockBooks);
        }

        // ... rest of dashboard code
    }
}
```

### Option 3: Send Email from Custom Action

Create a custom action button in EasyAdmin to send emails:

**Example: OrderCrudController.php**

```php
<?php

namespace App\Controller\Admin;

use App\Service\EmailService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCrudController extends AbstractCrudController
{
    public function __construct(
        private EmailService $emailService,
        private EntityManagerInterface $entityManager
    ) {}

    public static function getEntityFqcn(): string
    {
        return \App\Entity\Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendShippingEmail = Action::new('sendShippingEmail', 'Send Shipping Email')
            ->linkToCrudAction('sendShippingEmail');

        return $actions
            ->add(Crud::PAGE_DETAIL, $sendShippingEmail);
    }

    #[Route('/admin/order/{id}/send-shipping-email', name: 'admin_order_send_shipping_email')]
    public function sendShippingEmail(Request $request): Response
    {
        $order = $this->entityManager->getRepository(Order::class)
            ->find($request->get('id'));

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        // Send shipping email
        $this->emailService->sendOrderShippedEmail($order);

        $this->addFlash('success', 'Shipping email sent successfully!');

        return $this->redirect($request->headers->get('referer'));
    }
}
```

---

## Email Templates

Email templates are located in `templates/emails/`. Current templates:

- `verification.html.twig` - Email verification
- `welcome.html.twig` - Welcome message
- `password_reset.html.twig` - Password reset
- `goal_achieved.html.twig` - Goal achievement
- `weekly_summary.html.twig` - Weekly summary
- `recommendations.html.twig` - Book recommendations
- `low_stock_alert.html.twig` - Low stock alert
- `order_confirmation.html.twig` - Order confirmation
- `order_status_update.html.twig` - Status update
- `order_shipped.html.twig` - Shipment notification
- `order_delivered.html.twig` - Delivery notification
- `order_cancelled.html.twig` - Cancellation
- `admin_new_order.html.twig` - Admin notification

---

## Example: Send Bulk Emails from EasyAdmin

**Create a custom controller command:**

```php
<?php

namespace App\Controller\Admin;

use App\Service\EmailService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailCampaignController extends AbstractController
{
    public function __construct(private EmailService $emailService)
    {
    }

    #[Route('/admin/send-weekly-summaries', name: 'admin_send_weekly_summaries')]
    public function sendWeeklySummaries(): Response
    {
        $this->emailService->sendWeeklySummaries();
        
        return new Response('Weekly summaries sent successfully!', 200);
    }

    #[Route('/admin/send-book-recommendations', name: 'admin_send_book_recommendations')]
    public function sendBookRecommendations(): Response
    {
        $this->emailService->sendBookRecommendations();
        
        return new Response('Book recommendations sent successfully!', 200);
    }
}
```

Then add to your EasyAdmin Dashboard MenuItem:

```php
public function configureMenuItems(): iterable
{
    return [
        MenuItem::linkToRoute('Send Weekly Summaries', 'fas fa-envelope', 'admin_send_weekly_summaries'),
        MenuItem::linkToRoute('Send Recommendations', 'fas fa-book', 'admin_send_book_recommendations'),
    ];
}
```

---

## Testing Email Service

To test emails without sending them, update `.env`:

```bash
# Development - use null transport (doesn't send emails)
MAILER_DSN=null://

# Test - use memory transport (stores in memory)
MAILER_DSN=in-memory://

# Production - use real SMTP
MAILER_DSN=smtp://khlifahmed9@gmail.com:gncipbkkjkrmsogm@smtp.gmail.com:587
```

---

## Troubleshooting

### Issue: "Gmail SMTP Error"

**Solution:** Make sure:
1. Less secure apps are enabled in Gmail settings
2. App password is used (if 2FA enabled)
3. Correct DSN format: `smtp://username:password@host:port`

### Issue: "Template not found"

**Solution:** Ensure templates exist in `templates/emails/` directory

### Issue: "No email sent"

**Solution:** Check logs:
```bash
tail -f var/log/dev.log | grep -i mail
```

---

## Best Practices

1. **Wrap in try-catch** when sending from admin actions:
```php
try {
    $this->emailService->sendOrderShippedEmail($order);
    $this->addFlash('success', 'Email sent successfully');
} catch (\Exception $e) {
    $this->addFlash('error', 'Failed to send email: ' . $e->getMessage());
}
```

2. **Use queues for bulk emails** (consider Messenger):
```php
# Install: symfony messenger:setup-transports
```

3. **Add email logging** for audit trail:
```php
// In EmailService
error_log("Email sent to: {$user->getEmail()}");
```

---

## Summary

Your EmailService is production-ready and fully integrated with EasyAdmin. You can:

✅ Send emails from CRUD controllers
✅ Send bulk emails from dashboard
✅ Create custom email actions
✅ Use pre-built email templates
✅ Send to single users or groups (by role)
✅ Track email history and errors

**Start using it now in your EasyAdmin controllers!**
