# JSMF Loan Services Website

A complete loan services website built with HTML, CSS, PHP, and JavaScript for traditional web hosting platforms like Hostinger.

## 🌟 Features

- **Responsive Design**: Mobile-friendly design that works on all devices
- **Complete Loan Services**: Home loans, personal loans, business loans, car loans, education loans, and more
- **EMI Calculator**: Interactive loan EMI calculator with sliders
- **Admin Panel**: Simple admin dashboard to manage applications and inquiries
- **Contact Forms**: Contact form and loan application form with email notifications
- **Professional Design**: Modern, clean, and professional user interface
- **SEO Optimized**: Search engine optimized structure and content

## 📁 Project Structure

```
/
├── index.html                 # Homepage
├── about.html                 # About us page
├── contact.html              # Contact page
├── apply.html                # Loan application page
├── emi-calculator.html       # EMI calculator
├── home-loans.html           # Home loans details
├── contact-handler.php       # Contact form handler
├── loan-application-handler.php # Loan application handler
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   └── main.js           # JavaScript functionality
│   └── images/
│       └── banks/            # Bank logos directory
├── admin/
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Admin dashboard
│   ├── view-application.php  # View application details
│   └── data/                 # Data storage directory
└── README.md                 # This file
```

## 🚀 Installation & Setup

### Requirements
- Web hosting with PHP support (PHP 7.0 or higher)
- MySQL/MariaDB (optional - currently uses JSON file storage)
- Email functionality (for form notifications)

### Step 1: Upload Files
1. Download and extract the project files
2. Upload all files to your hosting public_html directory via FTP/File Manager
3. Ensure all files maintain their directory structure

### Step 2: Set Permissions
Set the following directory permissions:
```bash
chmod 755 admin/data/
chmod 644 admin/data/*.json
```

### Step 3: Configure Email (Optional)
Edit the email settings in:
- `contact-handler.php`
- `loan-application-handler.php`

Update the email addresses:
```php
$to = 'your-email@domain.com'; // Replace with your email
```

### Step 4: Admin Access
- URL: `http://yoursite.com/admin/login.php`
- Username: `admin`
- Password: `admin123`

**Important**: Change the admin credentials in `admin/login.php` for security.

## 📧 Email Configuration

The contact and application forms send email notifications. Configure your hosting email settings:

1. **Shared Hosting**: Usually works automatically
2. **VPS/Dedicated**: Configure postfix or use SMTP

For SMTP configuration, modify the mail() functions to use PHPMailer or similar.

## 🔧 Customization

### Update Company Information
Edit the following in all HTML files:
- Company name: "JSMF Loan Services"
- Phone: "+91 62620 79180"
- Email: "costumercare@jsmf.in"
- Address: Update the office address

### Update Interest Rates
Edit interest rates in:
- `home-loans.html`
- `assets/js/main.js` (EMI calculator)

### Add Bank Logos
Add bank logo images to `assets/images/banks/` directory with names:
- sbi.png
- hdfc.png
- icici.png
- axis.png
- kotak.png
- pnb.png

## 📱 Features Overview

### Public Features
- **Homepage**: Hero section, services overview, EMI calculator, testimonials
- **Loan Services**: Detailed pages for each loan type
- **EMI Calculator**: Interactive calculator with sliders
- **Application Form**: Complete loan application with validation
- **Contact Form**: Contact form with multiple inquiry types

### Admin Features
- **Dashboard**: Overview of applications and inquiries
- **Application Management**: View and update application status
- **Contact Management**: View contact form submissions
- **Data Export**: Export functionality for applications

## 🛡️ Security Features

- Form validation (client-side and server-side)
- XSS protection with htmlspecialchars()
- Basic authentication for admin panel
- File upload restrictions (if implemented)
- Input sanitization

## 📊 Data Storage

Currently uses JSON file storage for simplicity:
- `admin/data/applications.json` - Loan applications
- `admin/data/contacts.json` - Contact form submissions

### Upgrading to MySQL
To use MySQL database instead of JSON files:

1. Create database tables
2. Update PHP files to use PDO/MySQLi
3. Replace JSON file operations with SQL queries

## 🌐 SEO Optimization

- Meta tags for all pages
- Semantic HTML structure
- Alt text for images
- Sitemap ready structure
- Google Analytics ready

## 📞 Support

For customization and support:
- Email: costumercare@jsmf.in
- Phone: +91 62620 79180

## 📄 License

This project is proprietary software for JSMF Loan Services.

## 🔄 Version History

- **v1.0.0** - Initial release with complete loan services website
- Responsive design
- Admin panel
- Form handlers
- EMI calculator

---

**Note**: Remember to update admin credentials and email settings before going live!