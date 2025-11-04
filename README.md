# Joanne's Boutique — PHP Web Application

## About
Joanne's Boutique is a full-featured, mobile-friendly web platform for managing a boutique specializing in gowns, suits, and event packages. It includes an online gallery, booking and payment flows, reviews, and a robust admin dashboard for product, user, and testimonial management.

---

## Features
- **Public Website (Customer):**
  - Product gallery with image zoom, search, filter, and categories
  - Package catalog with inclusions, pricing, and background images
  - Advanced product search with instant results
  - Customer registration, authentication, and secure sessions
  - Customer booking for fittings, rentals, events (date/time selection, confirmation)
  - Shopping cart and checkout for products/services
  - Payment upload: GCash and others (manual/offline confirmation)
  - Testimonial/review submission and moderation
  - Order and booking history in profile
  - Responsive UI with TailwindCSS and Alpine.js/modal effects
- **Admin Dashboard:**
  - Secure login (role-based)
  - Analytics home/dashboard
  - Product CRUD (including image upload, inline gallery)
  - Package CRUD (features, inclusions, image)
  - Booking and order verification flows
  - Payment/proof management
  - Testimonial moderation (approve/reject/delete)
  - Customer management: view details, suspend, delete accounts
  - Role separation: admin/customer
  - Session notifications and error/success feedback

---

## Tools and Technologies Used
- **Language:** PHP 8.1+
- **Database:** MySQL or MariaDB
- **Frontend:** Tailwind CSS, Alpine.js, Font Awesome
- **Backend Framework:** Custom, with:
  - PSR-4 Autoloading via Composer
  - Custom Auth/session handling (`src/Utils/Auth.php`)
  - CSRF protection utility (`src/Utils/CSRF.php`)
  - Database abstraction (`src/Utils/Database.php`)
- **Admin Tools:**
  - Database migration (`database/migrate.php`)
  - Seeder scripts (`database/seed.php`)
  - DB checker (`tools/check_db.php`)
- **Other:**
  - XAMPP/Apache or PHP’s built-in server
  - Works under subdirectories (handles `BASE_URL`)

---

## High-Level System Flow (For Diagrams)

### Customer/User Operations
1. **Browse**: View homepage, products, packages
2. **Search/Filter**: Use instant search or filter by category
3. **Register/Login**: Create customer account or login
4. **Book**: Schedule fitting/event or book a package (date selection)
5. **Shop**: Add products to cart, checkout
6. **Pay**: Upload GCash/other payment proof (manual confirmation)
7. **Review**: Leave product/shop reviews (appear after admin moderation)
8. **History**: View order and booking history, profile management

### Admin Operations xc
1. **Login**: Authenticate as admin
2. **Dashboard**: Overview of business analytics/metrics
3. **Products/Packages**: Add, edit, delete, image upload for new items/packages
4. **Orders**: View/approve orders, verify payment and booking
5. **Bookings**: View, confirm, or reject booking requests
6. **Testimonials**: Approve/reject/delete public reviews (moderation)
7. **Users**: View, suspend (block login), or delete customers
8. **Payments**: Review payment proofs before verifying orders/bookings
---

## Setup / Installation
1. Clone the repository
2. Copy `.env.example` to `.env` and set your DB credentials
3. Run `composer install`
4. Run DB migration: `php database/migrate.php`
5. (Optional) Seed data: `php database/seed.php`
6. Serve: `php -S localhost:8000 -t public` (or use XAMPP and access under `/public`)

---

## Scope and Limitations
**Scope:**
- E-commerce + event booking system for a single boutique
- Manual offline payment confirmation (proof/image, not integrated payment gateway)
- Admin moderation for bookings, payments, testimonials
- Customer registration/authentication, password change, session management
- Image upload for products/packages

**Limitations:**
- No in-app private messaging or notifications system
- No real-time messaging, order status automation, two-factor authentication
- Single-tenant: Not designed for multivendor/multishop
- No online payment gateway integration out of the box
- Customer cannot edit or delete testimonials once submitted
- Basic roles (admin/customer) only, no advanced permission matrix
- Not optimized for massive scale/cloud or DDoS

---

## Database and Assets
- Database schema and migrations in `/database/`
- Product and package assets in `/public/uploads/`, static assets in `/public/assets/`

---

## Extending / Customizing
- Add new features (e.g., new roles, new payment options) by extending models/controllers in `src/`
- Frontend can be customized via Tailwind CSS utility classes

---

## Contact / Support
For support or help customizing, contact `info@joannesgowns.com` or contribute to this repo.
