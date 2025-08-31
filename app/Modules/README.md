
# Council ERP - Modular Architecture

This directory contains the modular organization of the Council ERP system. Each module is self-contained with its own controllers, models, views, and routes.

## Module Structure

Each module follows this structure:
```
ModuleName/
├── Controllers/
├── Models/
├── Views/
├── Routes/
├── Services/
├── Requests/
├── Resources/
└── Migrations/
```

## Available Modules

1. **Finance** - Complete financial management with IPSAS compliance
2. **Housing** - Property management and stands allocation
3. **Water** - Water utility management with smart metering
4. **Committee** - Democratic governance and meeting management
5. **Administration** - Customer relationship and internal management
6. **Property** - Property records and tax assessment
7. **Utilities** - Municipal service delivery and asset management
8. **Health** - Public health oversight and emergency response
9. **Survey** - Land surveying and urban development planning
10. **Licensing** - Business registration and compliance
11. **Parking** - Smart parking enforcement and revenue collection
12. **Markets** - Municipal market operations and vendor management
13. **Inventory** - Supply chain and asset management
14. **HR** - Staff management and payroll system
15. **Emergency** - Crisis management and disaster response

## Module Loading

Modules are automatically loaded through the ModuleServiceProvider.
