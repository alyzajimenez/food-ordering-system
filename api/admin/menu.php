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
                $data = json_decode(file_get_contents("php://input"), true);

                if (!isset($data['action'])) {
                    respondWithError('Missing action type');
                }

                $data['description'] = $data['description'] ?? '';
                $data['category'] = $data['category'] ?? '';

                if ($data['action'] === 'add') {
                    if (!isset($data['name'], $data['price'], $data['availability'])) {
                        respondWithError('Missing required fields for add');
                    }

                    $stmt = $conn->prepare("INSERT INTO menu (name, description, price, category, availability, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    if (!$stmt) {
                        respondWithError('Failed to prepare SQL statement: ' . $conn->error);
                    }

                    $stmt->bind_param(
                        "ssdsi",
                        $data['name'],
                        $data['description'],
                        $data['price'],
                        $data['category'],
                        $data['availability']
                    );

                    if ($stmt->execute()) {
                        echo json_encode(['message' => 'Menu item added']);
                    } else {
                        respondWithError('Failed to execute SQL: ' . $stmt->error);
                    }
                    $stmt->close();

                } elseif ($data['action'] === 'update') {
                    if (!isset($data['name'], $data['description'], $data['price'], $data['category'], $data['availability'], $data['menu_id'])) {
                        respondWithError('Missing required fields for update');
                    }

                    $stmt = $conn->prepare("UPDATE menu SET name=?, description=?, price=?, category=?, availability=? WHERE menu_id=?");
                    if (!$stmt) {
                        respondWithError('Failed to prepare SQL statement: ' . $conn->error);
                    }

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