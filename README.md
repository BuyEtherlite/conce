
# Council ERP System

> A comprehensive Enterprise Resource Planning (ERP) system designed specifically for City Councils and Municipal organizations. Built with Laravel 11 and modern web technologies.

## 🚀 Quick Start on Replit

1. **Fork this Repl** or import the repository
2. **Click the Run button** to start automated installation
3. **Follow the setup wizard** at your Repl URL
4. **Configure your council** and create admin account

The system will automatically:
- Install dependencies with Composer
- Set up PostgreSQL database
- Run all migrations
- Configure your environment

## ✨ What is Council ERP?

Council ERP is a complete digital transformation solution for municipal governments. It integrates all aspects of city council operations into one secure, scalable platform with role-based access control and department-specific modules.

### 🎯 Key Benefits

- **Complete Municipal Management** - Housing, finance, utilities, licensing, and more
- **Role-Based Security** - Granular permissions for different user levels
- **Department Organization** - Separate modules for different municipal departments
- **IPSAS Compliance** - International Public Sector Accounting Standards
- **Mobile Responsive** - Works on desktop, tablet, and mobile devices
- **Cloud Ready** - Optimized for Replit deployment

## 🏛️ Core Modules

### 💰 Finance & Accounting
Complete financial management with IPSAS compliance:
- General Ledger & Chart of Accounts
- Budgets & Variance Analysis
- Accounts Payable & Receivable
- Multi-Currency Support
- POS Integration & FDMS Receipts
- Fixed Asset Management
- Bank Reconciliation

### 🏠 Housing Management
Comprehensive housing services:
- Property Portfolio Management
- Waiting List & Application Processing
- Tenant Management & Services
- Allocation System with Audit Trail
- Maintenance Request Tracking

### 🏢 Administration & CRM
Customer relationship management:
- Comprehensive Customer Database
- Service Request Tracking
- Communication Management
- Inter-Department Workflows

### 💧 Water Management
Complete water utility management:
- Connection & Meter Management
- Automated Billing Integration
- Water Quality Testing
- Infrastructure Asset Tracking

### 🏛️ Committee Administration
Governance and meeting management:
- Committee Structure & Membership
- Meeting Scheduling & Agendas
- Minutes Recording & Resolutions
- Public Access Portal

### 🏗️ Engineering Services
Infrastructure and project management:
- Municipal Asset Tracking
- Work Order System
- Project Coordination
- Building Inspections
- Facility Management

### 📋 Licensing & Permits
Business and operational permits:
- Business License Management
- Operating Permits
- Shop Permits
- Event Authorization
- Automated Workflows

### Additional Modules
- **Parking Management** - Zones, permits, violations
- **Market Management** - Stall allocation, vendor tracking
- **Utilities** - Electricity, gas, waste collection
- **Health Services** - Inspections, permits, facilities
- **Property Management** - Valuations, leases, tax
- **Cemetery Management** - Plot allocation, burial records
- **Survey Services** - Land surveying, boundaries
- **Inventory Management** - Stock control, procurement
- **HR Management** - Staff records, payroll, attendance

## 🔐 Security & Access Control

### User Roles
- **Super Admin** - Complete system access
- **Council Admin** - Administrative oversight via `/council-admin`
- **Department Admin** - Department-level management
- **Manager** - Module-level management
- **User** - Operational access
- **POS Operator** - Point-of-sale operations only

### Security Features
- Multi-layer authentication with separate admin portal
- CSRF protection and SQL injection prevention
- Data encryption for sensitive information
- Complete audit trail and activity logging
- Session management and timeout controls

## 🛠️ Installation & Configuration

### System Requirements
- **PHP** 8.2 or higher
- **Database** PostgreSQL 12+ (recommended) or MySQL 8.0+
- **Memory** Minimum 2GB RAM for production
- **Storage** SSD recommended for optimal performance

### Manual Installation (if not using Replit)

```bash
# Clone and install
git clone <repository-url>
cd council-erp
composer install --no-dev --optimize-autoloader

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database (edit .env with your credentials)
php artisan migrate

# Create super admin
php artisan create:admin

# Start application
php artisan serve --host=0.0.0.0 --port=5000
```

### Configuration

1. **Access Admin Portal** at `/council-admin`
2. **Configure Modules** - Enable/disable per department
3. **Set Up Users** - Create department admins and users
4. **Configure Workflows** - Set approval processes

## 📊 Built-in Diagnostics

The system includes comprehensive diagnostic tools:

```bash
php artisan debug:system        # System health check
php artisan debug:system --fix  # Auto-repair issues
php artisan debug:system --deep # Detailed analysis
```

## 🚀 Deployment on Replit

### Quick Deploy
1. Click the **"Publish"** button in top right
2. Configure auto-scale deployment settings
3. Set environment variables if needed
4. Click **"Deploy"** - takes 1-5 minutes

### Production Settings
- **Resources**: 1 CPU, 1GB RAM per instance
- **Scaling**: Start with 6 machines max
- **SSL**: Automatic HTTPS provided
- **Database**: PostgreSQL recommended for production

## 📈 Performance & Monitoring

### Database Optimization
- Advanced indexing for query performance
- Connection pooling for efficiency
- Automated backup procedures
- Migration system for schema management

### Built-in Analytics
- Executive dashboard with KPIs
- Financial reporting and analysis
- Service delivery metrics
- Revenue forecasting and trends

## 🎯 Why Choose Council ERP?

### For Municipal Governments
- **Complete Integration** - All departments in one system
- **Compliance Ready** - IPSAS standards built-in
- **Citizen Services** - Improved public service delivery
- **Financial Control** - Multi-level authorization workflows

### Technical Excellence
- **Modern Framework** - Laravel 11 with PHP 8.2+
- **Secure by Design** - Enterprise-grade security
- **Cloud Native** - Optimized for Replit deployment
- **Mobile First** - Responsive design for all devices

## 📞 Support & Maintenance

### Diagnostic Commands
```bash
php artisan cache:clear         # Clear caches
php artisan config:clear        # Clear configuration
php artisan route:clear         # Clear routes
composer dump-autoload         # Refresh autoloader
```

### Getting Help
- **System Diagnostics** - Built-in health monitoring
- **Error Logging** - Detailed logs in `storage/logs/`
- **Configuration Check** - Automated setup validation

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🏛️ Built for Municipal Excellence

*Empowering local government with comprehensive, secure, and scalable technology solutions*

**Technical Stack:** Laravel 11 • PHP 8.2+ • PostgreSQL • Bootstrap • Replit Cloud

**Enterprise Features:** Multi-tenancy • API Integration • Audit Compliance • Disaster Recovery • Real-time Monitoring

This comprehensive ERP system represents a complete digital transformation solution for municipal governments, providing integrated management of all council operations while maintaining the highest standards of security, compliance, and user experience.
