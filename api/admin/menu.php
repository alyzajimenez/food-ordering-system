<?php
require_once '../../includes/db.php';

ini_set('display_errors', 0); //disable html error
ini_set('log_errors', 1);     //log errors
error_reporting(E_ALL);       //report errors
header("Content-Type: application/json");

function respondWithError($message) {
    echo json_encode(['message' => $message]);
    exit;
}

require_once '../../includes/db.php';
if (!$conn) {
    respondWithError('Database connection failed.');
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $result = $conn->query("SELECT * FROM menu");
        $menu = [];
        while ($row = $result->fetch_assoc()) {
            //availability column as boolean
            $row['availability'] = (bool) $row['availability'];
            $menu[] = $row;
        }
        echo json_encode(['menu' => $menu]);
        break;

    case 'POST':
        try {
            // Detect content type
            $isJson = strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false;

            if ($isJson) {
                $data = json_decode(file_get_contents("php://input"), true);
                $action = $data['action'] ?? '';
                $name = $data['name'] ?? '';
                $description = $data['description'] ?? '';
                $price = $data['price'] ?? '';
                $category = $data['category'] ?? '';
                $availability = $data['availability'] ?? 1;
                $menu_id = $data['menu_id'] ?? null;
                $imageName = null; 
            } else {
                $action = $_POST['action'] ?? '';
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $category = $_POST['category'] ?? '';
                $availability = $_POST['availability'] ?? 1;
                $menu_id = $_POST['menu_id'] ?? null;

                // Handle image upload
                $imageName = null;
                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = '../assets/menu/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $imageName;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        respondWithError('Failed to upload image');
                    }
                }
            }

            if (!$action) {
                respondWithError('Missing action type');
            }

            if ($action === 'add') {
                if (!$name || !$price || $availability === '') {
                    respondWithError('Missing required fields for add');
                }

                $stmt = $conn->prepare("INSERT INTO menu (name, description, price, category, availability, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                if (!$stmt) {
                    respondWithError('Failed to prepare SQL statement: ' . $conn->error);
                }

                $stmt->bind_param("ssdsss", $name, $description, $price, $category, $availability, $imageName);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Menu item added']);
                } else {
                    respondWithError('Failed to execute SQL: ' . $stmt->error);
                }
                $stmt->close();

            } elseif ($action === 'update') {
                if (!$name || $description === '' || !$price || $category === '' || $availability === '' || !$menu_id) {
                    respondWithError('Missing required fields for update');
                }

                // Keep existing image if not updated
                if (!$imageName && $menu_id) {
                    $res = $conn->query("SELECT image FROM menu WHERE menu_id = $menu_id");
                    $row = $res->fetch_assoc();
                    $imageName = $row['image'] ?? null;
                }

                $stmt = $conn->prepare("UPDATE menu SET name=?, description=?, price=?, category=?, availability=?, image=? WHERE menu_id=?");
                if (!$stmt) {
                    respondWithError('Failed to prepare SQL statement: ' . $conn->error);
                }

                $stmt->bind_param("ssdsisi", $name, $description, $price, $category, $availability, $imageName, $menu_id);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Menu item updated']);
                } else {
                    respondWithError('Failed to update menu item: ' . $stmt->error);
                }
                $stmt->close();

            } else {
                respondWithError('Invalid action value');
            }
        } catch (Throwable $e) {
            respondWithError('Unexpected server error: ' . $e->getMessage());
        }
        break;

            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);

                if (isset($data['name'], $data['description'], $data['price'], $data['category'], $data['availability'], $data['menu_id'])) {
                    $stmt = $conn->prepare("UPDATE menu SET name=?, description=?, price=?, category=?, availability=? WHERE menu_id=?");
                    $stmt->bind_param(
                        "ssdsii", 
                        $data['name'], 
                        $data['description'], 
                        $data['price'], 
                        $data['category'], 
                        $data['availability'], 
                        $data['menu_id']
                    );

                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Menu item updated']);
                    } else {
                        echo json_encode(['message' => 'Failed to update menu item', 'error' => $stmt->error]);
                    }
                    $stmt->close();
                } else {
                    echo json_encode(['message' => 'Missing required fields']);
                }
                break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        
        if (isset($data['menu_id'])) {
            $stmt = $conn->prepare("DELETE FROM menu WHERE menu_id=?");
            $stmt->bind_param("i", $data['menu_id']);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Menu item deleted']);
            } else {
                echo json_encode(['message' => 'Failed to delete menu item', 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['message' => 'Missing menu_id']);
        }
        break;

    default:
        http_response_code(405); //not allowed method
        echo json_encode(['message' => 'Unsupported request method']);
        break;
}
?>