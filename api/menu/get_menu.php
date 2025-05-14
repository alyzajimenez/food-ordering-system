<?php
include('../includes/db.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'GET') {
    getMenuItems($conn);
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}

function getMenuItems($conn) {
    $query = "SELECT * FROM menu WHERE availability = 1";  
    $result = $conn->query($query);
    $menuItems = [];

    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }

    echo json_encode($menuItems);
}
?>