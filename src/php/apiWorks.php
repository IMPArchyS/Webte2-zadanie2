<?php
// Function to fetch data using cURL
function fetchData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// Endpoint for fetching thesis data
// Example URL: http://yourdomain.com/api/thesis/{pracovisko}/{type}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $pracovisko = $_GET['pracovisko'] ?? null;
    $type = $_GET['type'] ?? null;

    // Validate inputs
    if (!$pracovisko || !$type) {
        http_response_code(400);
        echo json_encode(array("message" => "Pracovisko and type parameters are required."));
        exit();
    }

    // Fetch data from the URL
    $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste={$pracovisko}";
    $htmlData = fetchData($url);

    // Parse HTML data to extract thesis information
    // Implement your logic here to parse HTML and extract thesis details

    // Dummy response
    $thesisData = array(
        array(
            "title" => "Thesis 1",
            "guarantor" => "John Doe",
            "program" => "Computer Science",
            "zameranie" => "Artificial Intelligence",
            "abstract" => "This is the abstract for Thesis 1."
        ),
        array(
            "title" => "Thesis 2",
            "guarantor" => "Jane Smith",
            "program" => "Electrical Engineering",
            "zameranie" => "Power Systems",
            "abstract" => "This is the abstract for Thesis 2."
        )
    );

    // Filter theses based on type
    $filteredTheses = array_filter($thesisData, function ($thesis) use ($type) {
        return strtolower($thesis['type']) == strtolower($type);
    });

    // Return JSON response
    echo json_encode($filteredTheses);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Method not allowed."));
}
?>
