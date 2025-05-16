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
    background: linear-gradient(135deg, #1c2b3a, #4a6279);
    font-family: 'Roboto', sans-serif;
    color: #333;
    display: flex;
    justify-content: center; 
    align-items: center; 
    min-height: 100vh;
    margin: 0;
    flex-direction: column; 
}

.container {
    display: flex;
    flex-direction: row;
    width: 100%;
    max-width: 1200px; 
    height: auto; 
    min-height: 80vh; 
    flex-direction: column;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.sidebar {
    width: 250px;
    background: linear-gradient(to bottom, #2c3e50, #34495e);
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 10;
    text-align: center;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    padding-top: 20px;
    border-radius: 0 10px 10px 0;
    transition: width 0.3s ease;
}

.sidebar:hover {
    width: 270px;
}

.sidebar .logo {
    width: 150px;
    height: auto;
    margin-bottom: 30px;
    transition: transform 0.3s ease;
}

.sidebar .logo:hover {
    transform: rotate(10deg);
}

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
    padding: 10px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #3498db;
    color: white;
    transform: translateX(10px);
}

.main-content {
    margin-left: 260px;
    padding: 30px;
    width: calc(100% - 260px);
    min-height: 100vh;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    overflow-y: auto;
    transition: margin-left 0.3s ease;
}

.main-content header h2 {
    font-size: 32px;
    color: #34495e;
    font-weight: 500;
    margin-bottom: 20px;
}

#dashboard-overview {
    margin-top: 30px;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 40px;
}

.card {
    background-color: #ecf0f1;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #dcdcdc;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.card h3 {
    font-size: 22px;
    color: #2c3e50;
    margin-bottom: 15px;
}

.card p {
    font-size: 24px;
    font-weight: bold;
    color: #27ae60;
}

/* Footer */
footer {
    width: 100%;
    background-color: #34495e;
    color: white;
    text-align: center;
    padding: 20px;
    position: relative;
    bottom: 0;
    margin-top: auto;
}

footer a {
    color: #ecf0f1;
    text-decoration: none;
    font-weight: bold;
}

footer a:hover {
    text-decoration: underline;
}

/* Responsive Design for Small Screens */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        position: static;
        margin-bottom: 20px;
        box-shadow: none;
        border-radius: 0;
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <a href="index.php">
                <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
            </a>
            <ul>
                <li>
                    <a href="manage-menu.php">
                        <i class="fas fa-utensils nav-icon"></i> Manage Food Menu
                    </a>
                </li>
                <li>
                    <a href="manage-orders.php">
                        <i class="fas fa-receipt nav-icon"></i> Manage Customer Orders
                    </a>
                </li>
                <li>
                    <a href="manage-history.php">
                        <i class="fas fa-history nav-icon"></i> Manage Order History
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt nav-icon"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
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

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Food Tiger Admin Dashboard. All Rights Reserved.</p>
       
    </footer>
</body>
</html>
