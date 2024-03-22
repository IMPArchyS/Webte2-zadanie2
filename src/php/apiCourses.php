<?php
require_once "../config.php";
require_once "course.php";

$course = new Course($conn);

// Define HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Extract endpoint from request URI
$requestUri = explode('/', $_SERVER['REQUEST_URI']);
$endpoint = end($requestUri);
$parsed_url = parse_url($endpoint);
$endpoint = $parsed_url['path'];


// Handle endpoint /courses
if ($endpoint === 'courses') {
    // Handle requests based on HTTP method
    switch ($method) {
        case 'GET':
            // Check if course ID is provided in the URL
            if (isset($_GET['id'])) {
                // Get course by ID
                $courseId = $_GET['id'];
                $result = $course->getCourseById($courseId);
                if ($result) {
                    echo json_encode($result);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Course not found."));
                }
            } else {
                // Get all courses
                $result = $course->getAllCourses();
                echo json_encode($result);
            }
            break;
        case 'POST':
            // Read incoming data
            $data = json_decode(file_get_contents("php://input"), true);

            // Add new course
            if ($course->addCourse($data)) {
                http_response_code(201);
                echo json_encode(array("message" => "Course created successfully."));
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to create course."));
            }
            break;
        case 'PUT':
            // Check if course ID is provided in the URL
            if (isset($_GET['id'])) {
                // Read incoming data
                $data = json_decode(file_get_contents("php://input"), true);
                $courseId = $_GET['id'];

                // Update course
                if ($course->updateCourse($courseId, $data)) {
                    echo json_encode(array("message" => "Course updated successfully."));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Unable to update course."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Missing course ID."));
            }
            break;
        case 'DELETE':
            // Check if course ID is provided in the URL
            if (isset($_GET['id'])) {
                $courseId = $_GET['id'];

                // Delete course
                if ($course->deleteCourse($courseId)) {
                    echo json_encode(array("message" => "Course deleted successfully."));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Unable to delete course."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Missing course ID."));
            }
            break;
        default:
            // Unsupported HTTP method
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