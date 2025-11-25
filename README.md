==============================================================
Inventory & Stock Management System
==============================================================

A complete inventory management solution with stock tracking, reporting, analytics, and alerts.
This project is developed as part of the Internship Project Round.
The system manages products, categories, stock IN/OUT entries, low-stock alerts, history logs, and report exports.
A professional dashboard summarizes real-time inventory status.

==============================================================
Tech Stack
==============================================================

| Component | Technology                                  |
| --------- | ------------------------------------------- |
| Frontend  | HTML, CSS, Bootstrap (SB Admin 2), jQuery   |
| Backend   | PHP (OOP-Based PHP Architecture)            |
| Database  | MySQL                                       |
| Security  | AES-256 ID Encryption, Login Authentication |

==============================================================
Backend Architecture (OOP PHP)
==============================================================

This project is built using a Clean OOP PHP Architecture.
✔ Single Centralized Inventory Class

All major operations (Category, Product, Stock IN/OUT, Ledger, Reports, Dashboard) are written inside one structured PHP Class, making the system modular and maintainable.

==============================================================
Features Implemented
==============================================================

** Core Required Features (as per assignment) **

1. Category Management
2. Product Management (SKU, HSN, price, category, min stock)
3. Stock IN (Purchase Entry)
4. Stock OUT (Usage/Sales Entry)
5. Stock Ledger (Full IN/OUT history)
6. Low Stock Alerts
7. Category-wise Product Listing
8. Historical Tracking (previous & new stock always logged)

** Extra Features (Beyond Requirements) **

1. Dashboard with Analytics
2. Reports Module
3. Security Enhancements

==============================================================
Installation
==============================================================

1. Clone Repository :
   git clone https://github.com/Shashank-1904/Inventory-and-stock-management-system.git
   Move it to: xampp/htdocs/
   Replace the relative path from the inlcudes/sidebar for the redirection

2. Import Database :
   Open phpMyAdmin
   Import database.sql (present in project root)
   Update database credentials in : invconfig.php

3. Access Application :
   Open in browser : http://localhost/<your-folder-name>/home/
   Login using provided admin credentials
   - email : demo@gmail.com
   - pass : 12345
