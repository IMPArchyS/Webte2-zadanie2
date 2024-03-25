<?php
// Function to fetch data using cURL
function fetchData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
// Define HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Set character encoding to UTF-8

// Extract endpoint from request URI
$requestUri = explode('/', $_SERVER['REQUEST_URI']);
$endpoint = end($requestUri);
$parsed_url = parse_url($endpoint);
$endpoint = $parsed_url['path'];

if ($endpoint === 'themes') {
    // Handle requests based on HTTP method
    switch ($method) {
        case 'GET':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($data);
            break;
        case 'POST':
            break;
        case 'PUT':
            break;
        case 'DELETE':
            break;
        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(array("message" => "Method not allowed."));
            break;
    }
} else {
    // Invalid endpoint
    http_response_code(404); // Not Found
    echo json_encode(array("message" => "Requested URI: " . $endpoint));
    echo json_encode(array("message" => "Endpoint not found."));
}

?>