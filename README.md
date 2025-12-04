

# 🛒 SLU Bazaar

A professional auction and marketplace platform built with **Vanilla PHP 8.2**, structured using the **MVC Pattern**, **Dependency Injection**, and **AJAX** for seamless user interaction.

---

## 📋 Prerequisites

* **XAMPP** (or WAMP/MAMP) with PHP 8.1+
* **MySQL**
* **Git**

---

# ⚙️ Installation & Setup Guide

Follow these steps carefully to get the project running locally.

---

## 1️⃣ Clone the Repository

Open your terminal and go to your XAMPP `htdocs` folder:

```bash
cd C:\xampp\htdocs
git clone https://github.com/Hendrizzzz/SLUBazaar SLUBAZAAR
```

---

## 2️⃣ Apache Configuration (Required)

This project uses a **custom local domain** (`slubazaar.local`) and points directly to the `/public` folder for security.

### **A. Enable `mod_rewrite`**

1. Open **XAMPP Control Panel**

2. Apache → **Config** → `httpd.conf`

3. Find this line:

   ```apache
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```

4. **Uncomment it** (remove `#`)

---

### **B. Enable Virtual Hosts (Important)**

Virtual Hosts won’t work unless Apache loads the configuration file.

1. In `httpd.conf`, search for this line:

   ```apache
   #Include conf/extra/httpd-vhosts.conf
   ```

2. **Uncomment it**:

   ```apache
   Include conf/extra/httpd-vhosts.conf
   ```

3. Save and close.

---

### **C. Configure the Virtual Host**

1. Go to:

```
C:\xampp\apache\conf\extra\
```

2. Open: **`httpd-vhosts.conf`**

3. Add this block at the **bottom**:

```apache
# 1. Default XAMPP site
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs"
    ServerName localhost
</VirtualHost>

# 2. SLU Bazaar Local Domain
<VirtualHost *:80>
    ServerName slubazaar.local
    DocumentRoot "C:/xampp/htdocs/SLUBAZAAR/public"

    <Directory "C:/xampp/htdocs/SLUBAZAAR/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

### **D. Add Domain to Windows Hosts File**

1. Open Notepad as **Administrator**
2. Open the file:

```
C:\Windows\System32\drivers\etc\hosts
```

3. Add this line:

```
127.0.0.1   slubazaar.local
```

4. Save.

---

### **E. Restart Apache**

Open XAMPP → **Stop Apache** → **Start Apache**

---

## 3️⃣ Database Setup

1. Go to phpMyAdmin:
   **[http://localhost/phpmyadmin](http://localhost/phpmyadmin)**
2. Create database:

```
slubazaar
```

3. Import the SQL dump:

```
database/slubazaar.sql
```

4. Configure credentials:
   Go to `config/`, rename:

```
database.example.php → database.php
```

Edit:

```php
return [
    'host' => '127.0.0.1',
    'user' => '', // your username here boi
    'pass' => '', // your password boi        
    'name' => 'slubazaar' // name it this way boi
];
```

---

# 🚀 Running the Application

### **Open in browser:**

👉 [http://slubazaar.local](http://slubazaar.local)

If redirected to login, use the provided credentials or register normally.

---

## 🏃‍♂️ Start the Background Worker

This system includes a worker that closes expired auctions and notifies users.

1. Open terminal:

   ```bash
   cd C:\xampp\htdocs\SLUBAZAAR
   ```
2. Start the worker:

   ```bash
   php console.php auction:worker
   ```
3. Keep this window open while developing.

---

# 📂 Project Structure

```
SLUBAZAAR/
│
├── public/          # Front-facing directory
│   └── index.php    # Main router
│
├── src/
│   ├── controller/  # Handles HTTP requests
│   ├── service/     # Business logic
│   ├── repository/  # SQL logic
│   ├── model/       # Database entities
│   ├── dto/         # Clean data for views
│   └── view/        # HTML templates
│
├── config/          # Database credentials
├── console.php      # CLI worker command
└── database/        # SQL files
```

---

# 🛠 Troubleshooting

### ▶️ **404 on CSS/JS/Images**

Your VirtualHost `DocumentRoot` must point to:

```
.../SLUBAZAAR/public
```

### ▶️ **Database Connection Failed**

Verify `database.php` and confirm MySQL is running.

### ▶️ **Stuck on Login Page**

Clear session:

```
http://slubazaar.local/index.php?action=logout
```


