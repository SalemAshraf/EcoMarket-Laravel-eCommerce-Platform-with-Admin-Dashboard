# 🛒 EcoMarket

**EcoMarket** is a modern and scalable eCommerce platform built with **Laravel**, featuring a fully functional **Admin Dashboard** and **Customer Storefront**. It provides a robust foundation for any online marketplace with powerful features and clean architecture.

<img width="1920" height="4170" alt="screencapture-127-0-0-1-8000-2025-07-13-22_03_26" src="https://github.com/user-attachments/assets/74c110c1-f48f-4bfd-af3d-990a6859ef37" />


---

## ✨ Key Features

- 🛍️ Full-featured customer shop (browsing, cart, checkout)
- 🔐 Secure customer authentication (register/login)
- 📦 Product management with categories, tags, and stock tracking
- 💳 Order processing & payment integration ready
- 📈 Admin dashboard with analytics and CRUD management
- 📬 Email notifications (order status, account confirmation)
- 🌐 Multi-language and currency-ready
- 📱 Responsive UI for both desktop and mobile

---

## 🧰 Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Blade + Bootstrap / TailwindCSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze / Jetstream
- **Admin Panel:** Custom built or integrated with Voyager/Nova (optional)
- **APIs:** RESTful API for frontend or mobile apps

---

## 📁 Folder Structure (simplified)

```bash
EcoMarket/
│
├── app/                # Laravel core logic (models, controllers, services)
├── resources/views/    # Blade templates (admin + store)
├── routes/web.php      # Web routes
├── public/             # Public assets (images, CSS, JS)
├── database/           # Migrations and seeders
├── config/             # Laravel configuration files
├── .env                # Environment variables (ignored)
└── README.md           # Project documentation
```

---

## 🚀 Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/ecomarket.git
   cd ecomarket
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Setup `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Setup your database in `.env`, then run:
   ```bash
   php artisan migrate --seed
   ```

5. Start the local server:
   ```bash
   php artisan serve
   ```

---

## 👤 Admin Access

```text
URL: ego.revivsolutions.com/admin
Email: salemashraf2002@gmail.com
Password: 12345678
```

(You can modify seed data or create users via tinker or dashboard.)

---

## 👨‍💻 Author

**Salem Ashraf**  
Founder – Reviv Solutions LTD  
🔗 [LinkedIn](https://linkedin.com/in/salem-ashraf)  
🌐 [Company Website](https://salem.revivsolutions.com)

---

## 📄 License

This project is open-sourced under the MIT license.
