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
  <title>Admin - Manage Menu</title>
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
            margin-left: 150px;
            padding: 30px;
            width: calc(100% - 50px);
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
                padding: 10px;
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
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>

    <main class="main-content">
        <h2>Manage Menu</h2>

        <!-- Add menu form -->
        <form id="menuForm" class="row g-3 my-4">
            <input type="hidden" id="menuId" name="menu_id">

            <div class="col-md-3">
                <input type="text" id="name" name="name" class="form-control" placeholder="Item Name" required>
            </div>
            <div class="col-md-3">
                <input type="text" id="description" name="description" class="form-control" placeholder="Description" required>
            </div>
            <div class="col-md-1">
                <input type="number" id="price" name="price" class="form-control" placeholder="Php" step="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="text" id="category" name="category" class="form-control" placeholder="Category" required>
            </div>
            <div class="col-md-2">
                <select id="availability" name="availability" class="form-select" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" id="menuFormSubmit" class="btn btn-primary w-100">Add</button>
            </div>
        </form>

        <!-- View menu list -->
        <table class="table table-bordered">
        <thead>
            <tr>
            <th>Name</th><th>Description</th><th>Price</th><th>Category</th><th>Available</th><th>Actions</th>
            </tr>
        </thead>
        <tbody id="menuTable"></tbody>
        </table>
    </div>
    </main>
  <script>
    const form = document.getElementById('menuForm');
    const table = document.getElementById('menuTable');

    function loadMenu() {
      fetch('../api/admin/menu.php')
        .then(res => res.json())
        .then(data => {
          table.innerHTML = '';
          data.menu.forEach(item => {
            table.innerHTML += `
              <tr>
                <td>${item.name}</td>
                <td>${item.description}</td>
                <td>₱${item.price}</td>
                <td>${item.category}</td>
                <td>${item.availability == 1 ? 'Yes' : 'No'}</td>
                <td>
                  <button class="btn btn-warning btn-sm" onclick="editItem(${item.menu_id}, '${item.name}', '${item.description}', ${item.price}, '${item.category}', ${item.availability})">Edit</button>
                  <button class="btn btn-danger btn-sm" onclick="deleteItem(${item.menu_id})">Delete</button>
                </td>
              </tr>
            `;
          });
        });
    }

    form.addEventListener('submit', e => {
        e.preventDefault();

        const menuId = form.menu_id.value;
        const data = {
            name: form.name.value,
            description: form.description.value,
            price: parseFloat(form.price.value),
            category: form.category.value,
            availability: parseInt(form.availability.value),
            action: menuId ? 'update' : 'add' 
        };

        if (menuId) {
            data.menu_id = parseInt(menuId);
        }

        fetch('../api/admin/menu.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            console.log(res.message);
            form.reset();
            document.getElementById('menuFormSubmit').innerText = 'Add';
            loadMenu();
        })
        .catch(err => console.error('Error:', err));
    });


    function deleteItem(id) {
        if (confirm("Are you sure you want to delete this menu item?")) {
            fetch('../api/admin/menu.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `menu_id=${id}`
            }).then(loadMenu);
        }
    }

    function editItem(id, name, description, price, category, availability) {
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        document.getElementById('price').value = price;
        document.getElementById('category').value = category;
        document.getElementById('availability').value = availability;

        document.getElementById('menuId').value = id;

        document.getElementById('menuFormSubmit').innerText = 'Edit';
    }

    loadMenu();
  </script>
</body>
</html>