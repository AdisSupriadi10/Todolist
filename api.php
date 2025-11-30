<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once "controllers/todocontroller.php";

$controller = new TodoController();
$action = $_GET['action'] ?? null;

// Baca input JSON
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {

    case "list":
        echo json_encode($controller->index());
        break;

    case "add":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["message" => "Use POST method"]);
            exit;
        }
        $task = $data["task"] ?? null;
        $controller->add($task);
        echo json_encode(["message" => "Todo added"]);
        break;

    case "complete":
        if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
            echo json_encode(["message" => "Use PUT method"]);
            exit;
        }
        $id = $data["id"] ?? null;
        $controller->markAsCompleted($id);
        echo json_encode(["message" => "Todo completed"]);
        break;

    case "delete":
        if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
            echo json_encode(["message" => "Use DELETE method"]);
            exit;
        }
        $id = $data["id"] ?? null;
        $controller->delete($id);
        echo json_encode(["message" => "Todo deleted"]);
        break;

    default:
        echo json_encode(["message" => "Invalid action"]);
}
