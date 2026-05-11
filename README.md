# FlowerCaféXpress ☕🌸

FlowerCaféXpress is a smart, robust, and intuitive web-based food ordering system tailored for university cafés. It streamlines the connection between customers and café staff, enabling seamless digital menu browsing, order placement, and operations management.

## 🌟 Features

### For Customers
* **Digital Menu**: Browse the latest menu with real-time availability and prices.
* **Shopping Cart**: Interactive cart to add/remove items and calculate subtotals before checkout.
* **Profile Management**: View past orders and manage contact information.
* **Dark Mode**: Built-in toggle for a comfortable viewing experience at night.

### For Staff
* **Order Management**: Monitor incoming orders in real-time.
* **Status Updates**: Quickly update order statuses (Pending, Preparing, Completed, Cancelled).

### For Admins
* **Full Control Panel**: A dedicated administrative dashboard.
* **Menu Management**: Add, update, or remove food and drink items.
* **User Management**: Manage customers and staff members, and assign roles.
* **Reports**: Generate historical sales and order reports.

## 💻 Tech Stack
* **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 4, jQuery
* **Backend**: Vanilla PHP (8.0+)
* **Database**: MySQL (using `mysqli` with prepared statements)
* **Authentication**: Session-based with secure `password_hash()` encryption.

## 🚀 Getting Started

### Prerequisites
* A local web server stack like [XAMPP](https://www.apachefriends.org/index.html), [WAMP](https://www.wampserver.com/en/), or [MAMP](https://www.mamp.info/).
* PHP 8.0 or higher.
* MySQL / MariaDB.

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/LynxPlayz24/FlowerCafeXPress.git
   cd FlowerCafeXPress
   ```

2. **Move to Server Directory**
   Move the project folder into your web server's public directory:
   * For XAMPP: `C:\xampp\htdocs\`
   * For MAMP/macOS: `/Applications/MAMP/htdocs/`

3. **Database Setup**
   * Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
   * Create a new database named `flowercafe_db`.
   * Import the provided SQL schema from `database/schema.sql` to set up the tables and default administrator account.

4. **Configuration**
   Copy the `dbconn.example.php` file and rename it to `dbconn.php`. Open it and ensure the database credentials match your local setup:
   ```php
   $host = "localhost";
   $username = "root";  // default for XAMPP
   $password = "";      // default for XAMPP
   $database = "flowercafe_db";
   ```

5. **Run the Application**
   Open your browser and navigate to `http://localhost/FlowerCafeXPress`.

## 🔐 Default Admin Credentials
When you import the SQL schema, a default administrator account is provided:
* **Email**: `admin@flowercafe.com`
* **Password**: `admin123`

*(Note: Please change this password in the admin panel after your first login for security).*

## 🤝 Contributing
Contributions, issues, and feature requests are welcome!
Feel free to check the [issues page](https://github.com/LynxPlayz24/FlowerCafeXPress/issues).

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
