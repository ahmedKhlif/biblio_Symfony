# 📚 Biblio - Library Management System

A comprehensive, full-featured library management system built with Symfony 7, featuring dual admin interfaces (EasyAdmin + Custom SB Admin 2), complete loan & reservation management, user authentication, messaging system, payment integration, and real-time analytics.

## ✨ Features

### 🎯 Core Library Management
- **Complete CRUD Operations** for Books, Authors, Categories, Publishers
- **Advanced Search & Filtering** with autocomplete suggestions
- **Sorting & Pagination** with visual indicators
- **Dual View System** (Table & Grid views for books)
- **Stock Management** with availability badges
- **Image Upload** for book covers (JPEG, PNG, GIF)
- **PDF Document Management** with upload, viewing, and download
- **Inline PDF Viewer** with modal full-screen option
- **Drag-and-Drop File Upload** for images and PDFs

### 📖 Loan & Reservation System
- **Book Reservations** - Users can reserve available books
- **Loan Management** - Track borrowed books with due dates
- **Admin Approval Workflow** - Approve/reject reservations and loans
- **Overdue Tracking** - Monitor late returns with notifications
- **Reading Progress** - Track user reading activity
- **Reading Goals** - Set and monitor reading targets

### 👥 User Management
- **User Registration & Authentication** with email verification
- **Role-Based Access Control** (Admin, User roles)
- **User Profiles** with avatar uploads
- **Password Reset** functionality
- **Activity Logging** - Track user actions

### 💬 Message Center
- **Internal Messaging System** between users
- **Inbox & Sent Messages** management
- **Real-time Unread Count** in navbar
- **Message Notifications** with AJAX updates

### 🛒 E-Commerce Features
- **Shopping Cart** for book purchases
- **Order Management** with order history
- **Stripe Payment Integration** for secure payments
- **Checkout Process** with order confirmation

### 🎨 Banner Management
- **Dynamic Banner System** for announcements
- **Admin Banner Controls** - Create, edit, schedule banners
- **User Banner Preferences** - Dismissible banners

### 📊 Dashboard & Analytics
- **Real-time Statistics** with key metrics
- **Interactive Charts** (Chart.js) - Bar & Pie charts
- **Library Insights** with activity tracking
- **Admin Dashboard** with system overview
- **User Dashboard** with personal statistics

### 🔐 Security & Authentication
- **Symfony Security** with firewall configuration
- **Email Verification** for new accounts
- **CSRF Protection** on all forms
- **Role-Based Permissions** (ROLE_ADMIN, ROLE_USER)

### 🎨 User Interface
- **Dual Admin Interface**:
  - EasyAdmin Bundle for quick CRUD operations
  - Custom SB Admin 2 pages for advanced features
- **SB Admin 2 Template** - Professional Bootstrap 4 design
- **Font Awesome Icons** throughout
- **Responsive Design** for all devices
- **Teal Theme** with modern styling

### 🔧 Technical Features
- **Symfony 7** framework with PHP 8.2+
- **Doctrine ORM** for database management
- **EasyAdmin 4** for admin CRUD
- **KnpPaginator** for pagination
- **Mailer Component** for email notifications
- **Stripe SDK** for payment processing

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Symfony CLI
- MySQL/PostgreSQL database
- Node.js & npm (for assets)
- Stripe Account (for payments - optional)

### Step-by-Step Installation

1. **Clone the repository**
    ```bash
    git clone https://github.com/ahmedKhlif/biblio_Symfony.git
    cd biblio_Symfony
    ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env .env.local
   ```
   Edit `.env.local` with your credentials:
   ```env
   # Database
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/biblio_db"
   
   # Mailer (for email verification & notifications)
   MAILER_DSN=smtp://user:pass@smtp.example.com:587
   
   # Stripe (for payments - optional)
   STRIPE_PUBLIC_KEY=pk_test_your_public_key
   STRIPE_SECRET_KEY=sk_test_your_secret_key
   ```

5. **Create Database**
   ```bash
   php bin/console doctrine:database:create
   ```

6. **Run Migrations**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

7. **Load Sample Data** (Optional)
   ```bash
   php bin/console doctrine:fixtures:load
   ```

8. **Create Upload Directories**
    ```bash
    mkdir -p public/uploads/images
    mkdir -p public/uploads/pdfs
    mkdir -p public/uploads/avatars
    ```

9. **Install Assets**
   ```bash
   php bin/console assets:install
   npm run build
   ```

10. **Start Development Server**
    ```bash
    symfony serve
    ```
    Or use PHP's built-in server:
    ```bash
    php -S localhost:8000 -t public
    ```

## 📖 Usage

### Accessing the Application

| Route | Description |
|-------|-------------|
| `/` | Landing page (public) |
| `/backoffice` | Main admin dashboard |
| `/admin` | EasyAdmin CRUD interface |
| `/livre` | Books management |
| `/auteur` | Authors management |
| `/categorie` | Categories management |
| `/editeur` | Publishers management |
| `/admin/loans` | Loan management (custom) |
| `/admin/reservations` | Reservation management (custom) |
| `/messages` | Message center |
| `/search` | Search with autocomplete |
| `/cart` | Shopping cart |
| `/checkout` | Payment checkout |
| `/profile` | User profile |
| `/login` | User login |
| `/register` | User registration |

### Default Admin Account
After loading fixtures, use:
- **Email**: `admin@biblio.com`
- **Password**: `admin123`

### Key Features Guide

#### 📚 Book Management
- **Add Books**: Upload cover images and PDF documents
- **PDF Management**: Upload, view, and download PDF documents (up to 10MB)
- **Inline PDF Viewer**: View PDFs directly in browser
- **Drag-and-Drop Upload**: Easy file upload with validation
- **View Modes**: Table view (detailed) and Grid view (visual cards)
- **Stock Status**: Green badges for available, red for out of stock

#### 📖 Loan & Reservation System
- **Reserve Books**: Users can reserve available books
- **Admin Approval**: Admins approve/reject reservations from `/admin/reservations`
- **Convert to Loan**: Approved reservations become active loans
- **Track Returns**: Monitor due dates and overdue books at `/admin/loans`
- **Mark Returned**: Process book returns and update inventory

#### 💬 Message Center
- **Send Messages**: Communicate with other users
- **Inbox**: View received messages with read/unread status
- **Notifications**: Real-time unread count in navbar dropdown
- **Access**: Navigate to `/messages` or use navbar dropdown

#### 🔍 Search System
- **Global Search**: Search across books, authors, categories
- **Autocomplete**: Real-time suggestions as you type
- **Results Page**: Organized results by entity type
- **Quick Access**: Use search bar in navbar

#### 🛒 Shopping & Payments
- **Add to Cart**: Browse books and add to cart
- **Checkout**: Secure payment via Stripe
- **Order History**: View past orders in profile

#### 📊 Admin Dashboards
- **Backoffice** (`/backoffice`): Charts, statistics, quick actions
- **EasyAdmin** (`/admin`): Quick CRUD for all entities
- **Loan Management** (`/admin/loans`): Dedicated loan tracking
- **Reservation Management** (`/admin/reservations`): Handle reservation requests

## 🗂️ Project Structure

```
biblio/
├── assets/                     # Frontend assets (JS, CSS)
│   ├── app.js                  # Main JavaScript entry
│   ├── styles/                 # Custom CSS styles
│   └── controllers/            # Stimulus controllers
├── bin/                        # Console commands
│   └── console                 # Symfony console
├── config/                     # Configuration files
│   ├── packages/               # Bundle configurations
│   ├── routes/                 # Route definitions
│   └── services.yaml           # Service definitions
├── migrations/                 # Database migrations
├── public/                     # Public web directory
│   ├── index.php               # Front controller
│   ├── css/                    # Compiled CSS
│   ├── js/                     # Compiled JavaScript
│   ├── img/                    # Static images
│   └── uploads/                # User uploads
│       ├── images/             # Book cover images
│       ├── pdfs/               # PDF documents
│       └── avatars/            # User avatars
├── src/                        # Application source code
│   ├── Controller/             # HTTP controllers
│   │   ├── Admin/              # EasyAdmin CRUD controllers
│   │   ├── Api/                # API endpoints
│   │   ├── LoanManagementController.php
│   │   ├── ReservationManagementController.php
│   │   ├── MessageController.php
│   │   ├── SearchController.php
│   │   └── ...
│   ├── Entity/                 # Doctrine entities
│   │   ├── User.php            # User entity with roles
│   │   ├── Livre.php           # Book entity
│   │   ├── Loan.php            # Loan tracking
│   │   ├── BookReservation.php # Reservations
│   │   ├── Message.php         # Internal messages
│   │   ├── Order.php           # Purchase orders
│   │   ├── Cart.php            # Shopping cart
│   │   └── ...
│   ├── Repository/             # Doctrine repositories
│   ├── Form/                   # Form types
│   ├── Service/                # Business logic services
│   ├── Security/               # Security voters & authenticators
│   └── EventSubscriber/        # Event listeners
├── templates/                  # Twig templates
│   ├── backendofficebase.html.twig  # Admin base template
│   ├── base.html.twig          # Public base template
│   ├── admin/                  # EasyAdmin templates
│   ├── dashboard/              # Dashboard templates
│   ├── loan_management/        # Loan admin pages
│   ├── reservation_management/ # Reservation admin pages
│   ├── message/                # Message center templates
│   ├── search/                 # Search results
│   └── ...
├── tests/                      # PHPUnit tests
├── translations/               # Translation files
├── .env                        # Environment template
├── .env.local                  # Local environment (git-ignored)
├── composer.json               # PHP dependencies
└── package.json                # Node.js dependencies
```

## 🛠️ Technologies Used

### Backend
| Technology | Purpose |
|------------|---------|
| **Symfony 7** | PHP web framework |
| **Doctrine ORM** | Database abstraction & migrations |
| **EasyAdmin 4** | Admin CRUD interface |
| **Twig** | Template engine |
| **KnpPaginator** | Pagination bundle |
| **Symfony Security** | Authentication & authorization |
| **Symfony Mailer** | Email notifications |
| **Stripe PHP SDK** | Payment processing |

### Frontend
| Technology | Purpose |
|------------|---------|
| **SB Admin 2** | Admin dashboard template |
| **Bootstrap 4** | CSS framework |
| **Font Awesome 5** | Icon library |
| **Chart.js 4** | Interactive charts |
| **jQuery 3** | JavaScript utilities |
| **Stimulus** | JavaScript framework |

### Database
- **MySQL 8** / **PostgreSQL** - Production database
- **SQLite** - Development/testing option

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node.js package manager
- **Symfony CLI** - Development server & tools
- **PHPUnit** - Testing framework
- **Git** - Version control

## 🔒 Security Features

- **Authentication System** with login/logout
- **Email Verification** for new registrations
- **Password Reset** via email token
- **Role-Based Access Control** (ROLE_USER, ROLE_ADMIN)
- **CSRF Protection** on all forms
- **Input Validation** and sanitization
- **File Upload Security** with type and size restrictions
  - Images: JPEG, PNG, GIF up to 1MB
  - PDFs: up to 10MB with validation
- **SQL Injection Prevention** via Doctrine ORM
- **XSS Protection** through Twig auto-escaping
- **Secure Payment Processing** via Stripe
- **Activity Logging** for audit trails

## 📱 Responsive Design

The application is fully responsive and works seamlessly on:
- **Desktop Computers** (1920px+)
- **Laptops** (1024px - 1920px)
- **Tablets** (768px - 1024px)
- **Mobile Phones** (320px - 768px)

## 🌍 Browser Support

- **Chrome** (recommended)
- **Firefox**
- **Safari**
- **Edge**
- **Opera**

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Symfony** team for the excellent framework
- **SB Admin 2** creators for the beautiful admin template
- **Font Awesome** for the comprehensive icon set
- **Bootstrap** team for the responsive framework
- **Chart.js** for the charting capabilities

## 📞 Support

If you have any questions or need help:
1. Check the [Issues](https://github.com/ahmedKhlif/biblio_Symfony/issues) page
2. Create a new issue with detailed information
3. Contact the maintainer

---

## 📋 Version History

### v3.0.0 (December 2024) - Latest
- ✅ **Message Center** - Full internal messaging system with real-time notifications
- ✅ **Search with Autocomplete** - Global search across all entities with suggestions
- ✅ **Chart.js Integration** - Fixed charts in EasyAdmin and backoffice dashboards
- ✅ **Custom Loan Management** - Dedicated admin pages at `/admin/loans`
- ✅ **Custom Reservation Management** - Dedicated admin pages at `/admin/reservations`
- ✅ **Banner System** - Dynamic announcement banners with admin controls
- ✅ **Reading Progress Tracking** - Monitor user reading activity
- ✅ **Activity Logging** - Track all user actions

### v2.0.0
- ✅ **PDF Document Management** - Upload, view, and download PDFs
- ✅ **Enhanced File Upload** - Drag-and-drop interface
- ✅ **Inline PDF Viewer** - View PDFs in browser
- ✅ **Improved Security** - Enhanced file validation

### v1.0.0
- ✅ Initial release with basic CRUD operations
- ✅ SB Admin 2 integration
- ✅ Book, Author, Category, Publisher management

---

## 📸 Screenshots

### Admin Dashboard
![Dashboard](public/img/screenshots/dashboard.png)

### Book Management
![Books](public/img/screenshots/books.png)

### Loan Management
![Loans](public/img/screenshots/loans.png)

---

**Made with ❤️ by Ahmed Khlif using Symfony 7 & SB Admin 2**

[![GitHub](https://img.shields.io/badge/GitHub-ahmedKhlif-blue?style=flat&logo=github)](https://github.com/ahmedKhlif)
[![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=flat&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-4.x-7952B3?style=flat&logo=bootstrap)](https://getbootstrap.com)