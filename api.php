<?php
// api.php

// 1. HEADER: Tentukan Content-Type sebagai JSON
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Izinkan CORS
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'controllers/todocontroller.php';

$controller = new TodoController();
$method = $_SERVER['REQUEST_METHOD']; // Dapatkan HTTP Method
$action = $_GET['action'] ?? null;    // Dapatkan parameter aksi (e.g., list, add)
$response = ['status' => 'error', 'message' => 'Invalid Request or Action Not Found'];

/**
 * Membaca input JSON dari body request (untuk POST/PUT)
 */
function getJsonBody()
{
    $data = file_get_contents("php://input");
    return json_decode($data, true);
}

try {
    switch ($method) {

        // --- READ (GET) ---
        case 'GET':
            if ($action === 'list') {
                $todos = $controller->index();
                $response = ['status' => 'success', 'data' => $todos];
                http_response_code(200); // 200 OK
            } else {
                http_response_code(404);
            }
            break;

        // --- CREATE (POST) ---
        case 'POST':
            if ($action === 'add') {
                $data = getJsonBody();
                $task = $data['task'] ?? null;

                if ($task) {
                    $result = $controller->add($task);
                    if ($result) {
                        $response = ['status' => 'success', 'message' => 'Todo added successfully'];
                        http_response_code(201); // 201 Created
                    } else {
                        http_response_code(500); // 500 Internal Error
                        $response['message'] = 'Failed to add todo to database';
                    }
                } else {
                    http_response_code(400); // 400 Bad Request
                    $response['message'] = 'Missing task parameter in JSON body';
                }
            } else {
                http_response_code(404);
            }
            break;

        // --- UPDATE (PUT/PATCH) ---
        // Menggunakan PUT untuk menandai selesai
        case 'PUT':
            if ($action === 'complete') {
                $data = getJsonBody();
                $id = $data['id'] ?? null;

                if ($id) {
                    $result = $controller->markAsCompleted($id);
                    if ($result) {
                        $response = ['status' => 'success', 'message' => "Todo ID {$id} marked as completed"];
                        http_response_code(200);
                    } else {
                        http_response_code(404); // 404 ID Not Found
                        $response['message'] = "Todo ID {$id} not found or update failed";
                    }
                } else {
                    http_response_code(400);
                    $response['message'] = 'Missing ID parameter in JSON body';
                }
            } else {
                http_response_code(404);
            }
            break;

        // --- DELETE (DELETE) ---
        case 'DELETE':
            if ($action === 'delete') {
                // Ambil ID dari URL (?id=...) atau JSON Body
                $id = $_GET['id'] ?? getJsonBody()['id'] ?? null;

                if ($id) {
                    $result = $controller->delete($id);
                    if ($result) {
                        $response = ['status' => 'success', 'message' => "Todo ID {$id} deleted successfully"];
                        http_response_code(200);
                    } else {
                        http_response_code(404); // 404 ID Not Found
                        $response['message'] = "Todo ID {$id} not found or deletion failed";
                    }
                } else {
                    http_response_code(400);
                    $response['message'] = 'Missing ID parameter (in URL or JSON body)';
                }
            } else {
                http_response_code(404);
            }
            break;

        default:
            http_response_code(405); // 405 Method Not Allowed
            $response['message'] = 'Method Not Allowed';
            break;
    }
} catch (\Throwable $th) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Internal Server Error', 'details' => $th->getMessage()];
}

// 2. OUTPUT: Kembalikan respons dalam format JSON
echo json_encode($response);
