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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Orders</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 270px);
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

        /* Image Styling */
        .image-item {
            width: 100%;
            max-width: 400px;
            height: auto;
            object-fit: cover;
            margin-top: 20px;
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
<body class="p-4">
  <div class="container">
    <!--sidebar-->
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
                    <a href="logout.php">
                      <i class="fas fa-sign-out-alt nav-icon"> </i>
                      Logout</a>
                </li>
            </ul>
        </nav>

    <main class="main-content">
    <h2>Customer Orders</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer ID</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="orderTable"></tbody>
    </table>
  </div>

  <script>
    function loadOrders() {
      fetch('../api/admin/orders.php')
        .then(res => res.json())
        .then(data => {
          console.log(data.orders);
          const table = document.getElementById('orderTable');
          table.innerHTML = '';

          data.orders.forEach(order => {
            console.log('Order ID:', order.order_id, 'Items:', order.items);

            const filteredItems = order.items.filter(item => item.name && item.quantity > 0);

            const itemsHTML = filteredItems.map(item => `
              <div>${item.name} x${item.quantity} - ₱${item.price}</div>
            `).join('');

            const isCompleted = order.status === 'completed';

            let statusClass = '';
              if (order.status === 'completed') {
                statusClass = 'bg-success text-white'; 
              } else if (order.status === 'preparing') {
                statusClass = 'bg-warning text-dark'; 
              } else {
                statusClass = 'bg-secondary text-white'; 
              }

            table.innerHTML += `
              <tr>
                <td>${order.order_id}</td>
                <td>${order.user_id}</td>
                <td>${itemsHTML}</td>
                <td>₱${order.total_price}</td>
                <td>
                  <span class="badge ${statusClass} px-3 py-2 rounded-pill" style="font-size: 0.9rem;">
                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-sm" 
                          onclick="updateStatus(${order.order_id}, 'preparing')" 
                          ${isCompleted ? 'disabled' : ''}>
                    Preparing
                  </button>
                  <button class="btn btn-warning btn-sm" 
                          onclick="updateStatus(${order.order_id}, 'completed')" 
                          ${isCompleted ? 'disabled' : ''}>
                    Complete
                  </button>
                </td>
              </tr>
            `;
          });
        });
    }

    function updateStatus(orderId, status) {
      fetch('../api/admin/orders.php', {
        method: 'PUT',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `order_id=${orderId}&status=${status}`
      }).then(loadOrders);
    }

    loadOrders();
  </script>
</body>
</html>