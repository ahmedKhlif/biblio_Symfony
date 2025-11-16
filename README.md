# 📚 Bibliothèque Management System

A comprehensive library management system built with Symfony 7, featuring a modern admin interface using SB Admin 2 template. Manage books, authors, categories, and publishers with advanced CRUD operations, image uploads, PDF document management, and real-time statistics.

## ✨ Features

### 🎯 Core Functionality
- **Complete CRUD Operations** for all entities (Books, Authors, Categories, Publishers)
- **Advanced Search & Filtering** across all data
- **Sorting & Pagination** with visual indicators
- **Dual View System** (Table & Grid views for books)
- **Stock Management** with availability badges
- **Image Upload** for book covers (JPEG, PNG, GIF)
- **PDF Document Management** with upload, viewing, and download (PDF files up to 10MB)
- **Inline PDF Viewer** with modal full-screen option
- **Drag-and-Drop File Upload** for both images and PDFs
- **Responsive Design** for all devices

### 📊 Dashboard & Analytics
- **Real-time Statistics** dashboard with key metrics
- **Interactive Charts** (Bar chart for entity distribution, Pie chart for categories)
- **Library Insights** with activity tracking
- **System Health** monitoring
- **Quick Actions** for common tasks

### 🎨 User Interface
- **SB Admin 2 Template** - Professional admin interface
- **Font Awesome Icons** throughout the application
- **Bootstrap Components** for responsive design
- **Dark/Light Theme** support
- **Mobile-First** responsive layout

### 🔧 Technical Features
- **Symfony 7** framework with modern PHP 8.2+
- **Doctrine ORM** for database management
- **KnpPaginator** for advanced pagination
- **File Upload** with validation and security
- **Form Validation** and error handling
- **CSRF Protection** on all forms
- **Slugger Service** for safe file naming

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Symfony CLI
- MySQL/PostgreSQL database
- Node.js & npm (for assets)

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
   Edit `.env.local` with your database credentials:
   ```env
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/bibliotheque_db"
   ```

5. **Create Database**
   ```bash
   php bin/console doctrine:database:create
   ```

6. **Run Migrations**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

7. **Load Sample Data**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

8. **Create Upload Directories**
    ```bash
    mkdir -p public/uploads/images
    mkdir -p public/uploads/pdfs
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
    php bin/console cache:clear
    php -S localhost:8000 -t public
    ```

## 📖 Usage

### Accessing the Application
- **Dashboard** (Default): `http://localhost:8000/` (redirects to backoffice)
- **Backoffice Dashboard**: `http://localhost:8000/backoffice`
- **Books Management**: `http://localhost:8000/livre`
- **Authors**: `http://localhost:8000/auteur`
- **Categories**: `http://localhost:8000/categorie`
- **Publishers**: `http://localhost:8000/editeur`

### Key Features Guide

#### 📚 Book Management
- **Add Books**: Upload cover images and PDF documents, set relationships with authors/categories/publishers
- **PDF Management**: Upload, view, and download PDF documents (up to 10MB)
- **Inline PDF Viewer**: View PDFs directly in the browser with full-screen modal option
- **Drag-and-Drop Upload**: Easy file upload for both images and PDFs with validation
- **View Modes**: Switch between table view (detailed data) and grid view (visual cards)
- **Stock Status**: Green badges for available books, red for out of stock
- **Search & Sort**: Find books by title, author, category, or ISBN

#### 👥 Author Management
- **Complete Profiles**: Store author names, biographies
- **Book Relationships**: See all books by each author
- **Search Functionality**: Find authors by name or biography content

#### 🏷️ Category Management
- **Organize Books**: Create categories for better organization
- **Hierarchical Structure**: Support for category descriptions
- **Statistics**: See how many books are in each category

#### 🏢 Publisher Management
- **Publisher Details**: Store contact information and locations
- **Book Associations**: Track which books come from which publishers
- **Global Reach**: Support for international publishers

#### 📊 Dashboard Insights
- **Real-time Metrics**: Current counts of all entities
- **Visual Charts**: Distribution charts and trend analysis
- **Activity Feed**: Recent additions and updates
- **System Status**: Health monitoring and quick actions

## 🗂️ Project Structure

```
bibliotheque-management/
├── assets/                    # Frontend assets (JS, CSS, images)
├── bin/                       # Console commands
├── config/                    # Symfony configuration
├── migrations/                # Database migrations
├── public/                    # Public web directory
│   ├── uploads/images/        # Uploaded book images
│   └── uploads/pdfs/          # Uploaded PDF documents
├── src/                       # Application source code
│   ├── Controller/            # Symfony controllers
│   ├── Entity/                # Doctrine entities
│   ├── Form/                  # Form types
│   ├── Repository/            # Doctrine repositories
│   └── DataFixtures/          # Sample data fixtures
├── templates/                 # Twig templates
│   ├── backoffice/            # Admin templates
│   ├── livre/                 # Book templates
│   ├── auteur/                # Author templates
│   ├── categorie/             # Category templates
│   ├── editeur/               # Publisher templates
│   └── base.html.twig         # Main layout
├── tests/                     # Test files
├── translations/              # Translation files
├── composer.json              # PHP dependencies
├── package.json               # Node.js dependencies
└── symfony.lock               # Symfony lock file
```

## 🛠️ Technologies Used

### Backend
- **Symfony 7** - PHP web framework
- **Doctrine ORM** - Database abstraction layer
- **Twig** - Template engine
- **KnpPaginator** - Pagination bundle
- **Symfony Form** - Form handling
- **Symfony Security** - Security components

### Frontend
- **SB Admin 2** - Admin template
- **Bootstrap 4** - CSS framework
- **Font Awesome** - Icon library
- **Chart.js** - Chart library
- **jQuery** - JavaScript library

### Database
- **MySQL/PostgreSQL** - Relational database
- **Doctrine Migrations** - Database versioning

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node.js package manager
- **Symfony CLI** - Development tools
- **Git** - Version control

## 🔒 Security Features

- **CSRF Protection** on all forms
- **Input Validation** and sanitization
- **File Upload Security** with type and size restrictions (Images: JPEG, PNG, GIF up to 1MB; PDFs up to 10MB)
- **PDF File Validation** ensuring only legitimate PDF documents are accepted
- **SQL Injection Prevention** via Doctrine ORM
- **XSS Protection** through Twig escaping
- **Secure File Storage** with safe naming and organized directory structure

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

If you have any questions or need help, please:
1. Check the [Issues](https://github.com/yourusername/bibliotheque-management/issues) page
2. Create a new issue with detailed information
3. Contact the maintainers

---

## 📋 Recent Updates (v2.0.0)

- ✅ **PDF Document Management**: Upload, view, and download PDF documents for books
- ✅ **Enhanced File Upload**: Drag-and-drop interface for both images and PDFs
- ✅ **Inline PDF Viewer**: View PDFs directly in browser with modal full-screen option
- ✅ **Backoffice as Default**: Root URL (/) now redirects to backoffice dashboard
- ✅ **Improved Security**: Enhanced file validation and secure PDF handling

**Made with ❤️ using Symfony 7 & SB Admin 2**