<?php
require_once "themes.php";
// Define HTTP method
$method = $_SERVER['REQUEST_METHOD'];

$requestUri = explode('/', $_SERVER['REQUEST_URI']);
$endpoint = end($requestUri);
$parsed_url = parse_url($endpoint);
$endpoint = $parsed_url['path'];

if ($endpoint === 'themes') {
    // Handle requests based on HTTP method
    switch ($method) {
        case 'GET':
            $ustav = $_GET['ustav'];
            $typ = $_GET['typ'];
            if (isset($ustav) && isset($typ)) {
                $res = fetchTable($ustav); // Fetch data using fetchAll function
                $res2 = parseDataFromTable($res, $typ); // Parse the fetched data
                echo json_encode($res2);
                //echo json_encode($res2);
                //echo json_encode($typ);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "Invalid request."));
            }
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