<?php
session_start();

include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Total customers
$result = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
$totalCustomers = $result->fetch_row()[0];

// Total orders
$result = $conn->query("SELECT COUNT(*) FROM orders");
$totalOrders = $result->fetch_row()[0];

// Orders this month
$result = $conn->query("SELECT COUNT(*) FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$monthlyOrders = $result->fetch_row()[0];

// Orders this year
$result = $conn->query("SELECT COUNT(*) FROM orders WHERE YEAR(created_at) = YEAR(CURRENT_DATE())");
$yearlyOrders = $result->fetch_row()[0];

// Revenue this month
$result = $conn->query("SELECT SUM(total_price) FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$monthlyRevenue = $result->fetch_row()[0] ?? 0;

// Revenue this year
$result = $conn->query("SELECT SUM(total_price) FROM orders WHERE YEAR(created_at) = YEAR(CURRENT_DATE())");
$yearlyRevenue = $result->fetch_row()[0] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* Global Styles */
        body {
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            color: #333;
            display: flex;
            flex-direction: row;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 10;
            text-align: center;
        }

        /* Logo Styling */
        .sidebar .logo {
            width: 150px;
            height: auto;
            margin-bottom: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Sidebar Links */
        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
            text-align: center;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #3498db;
        }

        /* Main Content */
        .main-content {
            margin-left: 100%;
            padding: 30px;
            width: 100%;
            min-height: 100vh;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        /* Main Header */
        header h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #34495e;
        }

        /* Main Message Section */
        #main-message h3 {
            font-size: 36px;
            color: #2c3e50;
            margin-top: 20px;
        }

        #main-message p {
            font-size: 18px;
            color: #7f8c8d;
            margin-top: 10px;
            line-height: 1.6;
        }

        #dashboard-overview {
            margin-top: 30px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }

        /* Adjustments for Small Screens (Mobile Devices) */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
                border-radius: 0;
                box-shadow: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <a href="index.php">
                <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
            </a>
            <ul>
                <li>
                    <a href="manage-menu.php">
                        <i class="fas fa-utensils nav-icon"></i>
                        Manage Food Menu
                    </a>
                </li>
                <li>
                <a href="manage-orders.php">
                        <i class="fas fa-receipt nav-icon"></i>
                        Manage Customer Orders
                    </a>
                </li>
                <li>
                    <a href="manage-history.php">
                        <i class="fas fa-history nav-icon"></i>
                        Manage Order History
                    </a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>

        <main class="main-content">
            <header>
                <h2>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</h2>
            </header>

            <section id="dashboard-overview">
                <div class="dashboard-cards">
                    <div class="card">
                        <h3>Total Customers</h3>
                        <p><?php echo $totalCustomers; ?></p>
                    </div>
                    <div class="card">
                        <h3>Total Orders</h3>
                        <p><?php echo $totalOrders; ?></p>
                    </div>
                    <div class="card">
                        <h3>Orders This Month</h3>
                        <p><?php echo $monthlyOrders; ?></p>
                    </div>
                    <div class="card">
                        <h3>Orders This Year</h3>
                        <p><?php echo $yearlyOrders; ?></p>
                    </div>
                    <div class="card">
                        <h3>Revenue This Month</h3>
                        <p>₱<?php echo number_format($monthlyRevenue, 2); ?></p>
                    </div>
                    <div class="card">
                        <h3>Revenue This Year</h3>
                        <p>₱<?php echo number_format($yearlyRevenue, 2); ?></p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
