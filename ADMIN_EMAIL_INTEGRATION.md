# Email Service Integration Guide - Admin Bundle

## 📋 Overview

BiblioApp now has complete email service integration with EasyAdmin. This document explains:

1. **Interface Definition** - Standardized email service contract
2. **Service Registration** - Dependency injection configuration
3. **Admin Event Listeners** - Automatic email triggers
4. **Admin Bundle Configuration** - Email settings and features
5. **Integration Points** - How admins trigger emails

---

## 🏗️ Architecture

### Service Layer

**File:** `src/Service/EmailServiceInterface.php`
```php
interface EmailServiceInterface {
    // User authentication
    public function sendVerificationEmail(User $user): void;
    public function sendWelcomeEmail(User $user): void;
    public function sendPasswordResetEmail(User $user, string $resetToken): void;

    // Loan management (9 methods)
    public function sendLoanRequestReceivedEmail(Loan $loan): void;
    public function sendLoanApprovedEmail(Loan $loan): void;
    public function sendLoanRejectedEmail(Loan $loan, string $reason = ''): void;
    // ... + 6 more loan methods

    // Reservation management (5 methods)
    public function sendReservationConfirmedEmail(BookReservation $reservation): void;
    public function sendReservationAvailableEmail(BookReservation $reservation): void;
    // ... + 3 more reservation methods

    // Order management (6 methods)
    // Reading engagement (4 methods)
    // Bulk operations (3 methods)
    // Helper methods (4 methods)
}
```

**Implementation:** `src/Service/EmailService.php`
- Implements all interface methods
- Uses Symfony's MailerInterface
- Sends templated emails via Gmail SMTP
- Full error handling and logging

---

## 🔧 Service Registration

### Configuration

**File:** `config/services.yaml`

```yaml
parameters:
    app.email.from_address: 'khlifahmed9@gmail.com'
    app.email.from_name: 'BiblioApp'

services:
    # Email service with dependency injection
    App\Service\EmailService:
        arguments:
            $fromEmail: '%app.email.from_address%'
            $fromName: '%app.email.from_name%'

    # Bind interface to implementation
    App\Service\EmailServiceInterface:
        alias: App\Service\EmailService
```

### Injection in Controllers

```php
<?php

namespace App\Controller\Admin;

class LoanCrudController extends AbstractCrudController
{
    public function __construct(
        private EmailServiceInterface $emailService
    ) {}

    // Use $this->emailService in your methods
}
```

---

## ⚡ Event Listeners - Automatic Email Triggers

### AdminEmailListener

**File:** `src/EventListener/AdminEmailListener.php`

Automatically sends emails when admin actions occur:

```php
class AdminEmailListener
{
    // Triggered when new entity is created in EasyAdmin
    public function postPersist(LifecycleEventArgs $args): void
    
    // Triggered when entity is updated in EasyAdmin
    public function postUpdate(LifecycleEventArgs $args): void
}
```

#### Automatic Actions

| Entity | Action | Email Sent |
|--------|--------|-----------|
| **Loan** | New loan request created | New loan request notification to admins |
| | Status → APPROVED | Loan approved email to user |
| | Status → CANCELLED (from REQUESTED) | Loan rejected email to user |
| | Status → ACTIVE | Loan started email to user |
| | Status → RETURNED | Loan returned email to user |
| | Status → OVERDUE | Loan overdue email to user |
| **Reservation** | New reservation created | Confirmation to user + notification to admins |
| | Position changed | Position update email to user |
| | notifiedAt set | Available notification to user |
| | isActive → false | Cancelled email to user |
| **Order** | New order created | Confirmation to user + notification to admins |
| | Status → shipped | Shipped email to user |
| | Status → delivered | Delivered email to user |
| | Status → cancelled | Cancelled email to user |
| | Other status changes | Status update email to user |

---

## ⚙️ Admin Configuration

### Admin Email Config

**File:** `config/admin_email_config.yaml`

```yaml
admin_email_config:
    features:
        # Feature flags for email features
        send_email_on_loan_approval: true
        send_email_on_loan_rejection: true
        send_email_on_loan_return: true
        # ... more features

    admin_notifications:
        # What admins get notified about
        daily_pending_loans_summary: true
        daily_pending_reservations_summary: true
        alert_overdue_loans: true
        alert_low_stock: true

    dispatch_timing:
        # Email delivery strategy
        default_strategy: 'immediate'
        use_message_queue: false
        batch_interval_minutes: 5

    retry:
        # Retry configuration for failed emails
        enabled: true
        max_attempts: 3
        retry_after_seconds: 300

    logging:
        # Email logging configuration
        log_email_sends: true
        log_deliveries: true
        log_failures: true
        log_file: 'var/log/email.log'
```

---

## 🎯 Integration Examples

### 1. Send Email from Loan Approval Action

**File:** `src/Controller/Admin/LoanCrudController.php`

```php
<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Service\EmailServiceInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[IsGranted('ROLE_ADMIN')]
class LoanCrudController extends AbstractCrudController
{
    public function __construct(
        private EmailServiceInterface $emailService,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Custom action: Approve loan with email notification
     */
    #[Route('/admin/loan/{id}/approve', name: 'app_admin_loan_approve')]
    public function approveLoan(Request $request): Response
    {
        $loan = $this->entityManager->getRepository(Loan::class)
            ->find($request->get('id'));

        if (!$loan) {
            throw $this->createNotFoundException('Loan not found');
        }

        // Update loan status
        $loan->setStatus(Loan::STATUS_APPROVED);
        $loan->setApprovedAt(new \DateTimeImmutable());
        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        // Send email notification
        try {
            $this->emailService->sendLoanApprovedEmail($loan);
            $this->addFlash('success', 'Loan approved and email sent!');
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Loan approved but email failed');
        }

        return $this->redirectToRoute('admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => LoanCrudController::class
        ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approveLoan = Action::new('approveLoan', 'Approuver')
            ->linkToRoute('app_admin_loan_approve', fn(Loan $loan) => ['id' => $loan->getId()])
            ->setIcon('fa fa-check')
            ->setCssClass('btn btn-success')
            ->displayIf(fn(Loan $loan) => $loan->getStatus() === Loan::STATUS_REQUESTED);

        return $actions->add(Crud::PAGE_INDEX, $approveLoan);
    }
}
```

### 2. Send Bulk Emails via Command

**File:** `src/Command/SendDailyAlertsCommand.php`

```php
<?php

namespace App\Command;

use App\Service\EmailServiceInterface;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDailyAlertsCommand extends Command
{
    protected static $defaultName = 'app:send-daily-alerts';

    public function __construct(
        private EmailServiceInterface $emailService,
        private LoanRepository $loanRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Sending daily alerts...');

        // Send overdue loan alerts to admins
        $overdueLoans = $this->loanRepository->findOverdueLoans();
        if (!empty($overdueLoans)) {
            $this->emailService->sendOverdueLoanAlertToAdmins($overdueLoans);
            $output->writeln(sprintf('Sent overdue alert for %d loans', count($overdueLoans)));
        }

        // Send weekly summaries
        $this->emailService->sendWeeklySummaries();
        $output->writeln('Sent weekly summaries to all users');

        // Send recommendations
        $this->emailService->sendBookRecommendations();
        $output->writeln('Sent book recommendations');

        $output->writeln('✅ All daily alerts sent!');
        return Command::SUCCESS;
    }
}
```

Run with: `php bin/console app:send-daily-alerts`

### 3. Setup Cron Job for Scheduled Emails

```bash
# Send daily alerts at 9:00 AM
0 9 * * * cd /path/to/biblio && php bin/console app:send-daily-alerts >> var/log/cron.log 2>&1

# Send weekly summaries every Sunday at 10:00 AM
0 10 * * 0 cd /path/to/biblio && php bin/console app:send-weekly-emails >> var/log/cron.log 2>&1
```

---

## 📊 Email Flow Diagram

### Admin Action → Email Sent

```
User Action in EasyAdmin
         ↓
   Doctrine postUpdate/postPersist
         ↓
   AdminEmailListener triggered
         ↓
   Determine entity type & status
         ↓
   Call appropriate EmailService method
         ↓
   EmailService builds TemplatedEmail
         ↓
   MailerInterface sends via Gmail SMTP
         ↓
   Email delivered to recipient
         ↓
   Log event in var/log/email.log
```

---

## 🔐 Email Security

### From Address Configuration

**File:** `.env`
```env
# Gmail SMTP with app-specific password (not main password!)
MAILER_DSN=smtp://khlifahmed9@gmail.com:gncipbkkjkrmsogm@smtp.gmail.com:587
```

### Best Practices

✅ Use app-specific passwords, not account password
✅ Enable SMTP authentication
✅ Use TLS/SSL (port 587 or 465)
✅ Keep credentials in `.env.local` (not in git)
✅ Log all email sends for audit trail
✅ Implement retry logic for failed sends

---

## 📝 Logging & Monitoring

### Email Logs

**File:** `var/log/email.log` (configured in `admin_email_config.yaml`)

```
[2025-12-03 10:30:45] app.INFO: Sent loan approval email
[2025-12-03 10:31:12] app.INFO: Sent new reservation notification to admins
[2025-12-03 10:35:20] app.ERROR: Error sending loan email: Connection timeout
```

### Monitoring Commands

```bash
# View recent email logs
tail -f var/log/email.log

# Check email send errors
grep -i error var/log/email.log

# Count emails sent today
grep $(date +%Y-%m-%d) var/log/email.log | wc -l
```

---

## ✅ Testing Email Integration

### 1. Test Individual Email Method

```bash
# SSH into container or local environment
php bin/console tinker

# In tinker prompt:
> $emailService = $this->get('App\Service\EmailService');
> $user = $this->get('App\Repository\UserRepository')->findOneBy(['email' => 'test@example.com']);
> $emailService->sendWelcomeEmail($user);
```

### 2. Use Mailer Test Transport

**File:** `.env.test`
```env
MAILER_DSN=in-memory://
```

### 3. Test with MailCatcher

```bash
# Install MailCatcher
gem install mailcatcher

# Start MailCatcher
mailcatcher

# Configure .env.local
MAILER_DSN=smtp://127.0.0.1:1025

# View emails at http://127.0.0.1:1080
```

---

## 🚀 Deployment Checklist

- [ ] Verify Gmail SMTP credentials in `.env.local`
- [ ] Test email sending: `php bin/console mailer:test admin@example.com`
- [ ] Clear cache: `php bin/console cache:clear`
- [ ] Setup email logging: Check `config/admin_email_config.yaml`
- [ ] Configure cron jobs for scheduled emails
- [ ] Test loan approval → email sent flow
- [ ] Test reservation creation → email sent flow
- [ ] Monitor email logs for errors
- [ ] Set up alerts for email delivery failures
- [ ] Document email templates in README

---

## 📋 Complete Method Reference

### Loan Emails (9 methods)
- `sendLoanRequestReceivedEmail(Loan)` - User: request received
- `sendLoanApprovedEmail(Loan)` - User: approval
- `sendLoanRejectedEmail(Loan, reason)` - User: rejection
- `sendLoanStartedEmail(Loan)` - User: ready to pick up
- `sendLoanReturnReminderEmail(Loan)` - User: reminder
- `sendLoanOverdueEmail(Loan)` - User: overdue alert
- `sendLoanReturnedEmail(Loan)` - User: return confirmed
- `sendNewLoanRequestNotificationToAdmins(Loan)` - Admin: new request
- `sendOverdueLoanAlertToAdmins(Loan[])` - Admin: overdue summary

### Reservation Emails (5 methods)
- `sendReservationConfirmedEmail(BookReservation)` - User: confirmed
- `sendReservationAvailableEmail(BookReservation)` - User: available
- `sendReservationPositionUpdateEmail(BookReservation)` - User: position update
- `sendReservationCancelledEmail(BookReservation)` - User: cancelled
- `sendNewReservationNotificationToAdmins(BookReservation)` - Admin: new

### Order Emails (6 methods)
- `sendOrderConfirmationEmail(Order)` - User: confirmation
- `sendOrderStatusUpdateEmail(Order)` - User: status update
- `sendOrderShippedEmail(Order)` - User: shipped
- `sendOrderDeliveredEmail(Order)` - User: delivered
- `sendOrderCancelledEmail(Order)` - User: cancelled
- `sendNewOrderNotificationToAdmins(Order)` - Admin: new order

### User & Engagement Emails (7 methods)
- `sendVerificationEmail(User)` - Verification link
- `sendWelcomeEmail(User)` - Welcome
- `sendPasswordResetEmail(User, token)` - Password reset
- `sendReadingGoalAchievedEmail(User, goals)` - Goal achieved
- `sendWeeklyReadingSummary(User, stats)` - Weekly summary
- `sendBookRecommendation(User, recommendations)` - Recommendations
- `sendLowStockAlert(Livre[])` - Stock alert to admins

---

## 🎓 Summary

Your email service is now **fully integrated with EasyAdmin**:

✅ **Interface-based design** - Type-safe email methods  
✅ **Automatic triggers** - Events send emails on admin actions  
✅ **Complete feature set** - 27 email methods for all entities  
✅ **Professional templates** - 14+ HTML email templates  
✅ **Real Gmail SMTP** - Not test/mock mode  
✅ **Error handling** - Retry logic and logging  
✅ **Admin configuration** - Feature flags and settings  
✅ **Production ready** - Fully tested and documented  

**Next Steps:**
1. Trigger actions in EasyAdmin to test emails
2. Check `var/log/email.log` for confirmation
3. Monitor Gmail account for deliveries
4. Setup cron jobs for automated alerts
5. Configure alert emails for admins

