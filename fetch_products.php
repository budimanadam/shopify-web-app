<?php

// Note: the file is separated since it will be used to get All Products

// Set the URL of the REST API
$apiUrl = "https://sanda-dev.myshopify.com/admin/api/2024-10/products.json";

// Initialize cURL session
$ch = curl_init();

// Set the headers and its token
$headers = [
    'X-Shopify-Access-Token: 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',
];

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);  // API URL
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // API URL Headers
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
curl_setopt($ch, CURLOPT_HTTPGET, true);  // Use GET method

// Execute the cURL session and get the response
$response = curl_exec($ch);

// Check if there was an error during the cURL request
if ($response === false) {
    // If there is an error, return an error message
    echo json_encode(['error' => 'Unable to fetch data']);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response into a PHP array
$data = json_decode($response, true);

// Check if the data is valid and return it, else send an error
if (isset($data) && is_array($data)) {
    echo json_encode($data);  // Send the data back as JSON
} else {
    echo json_encode(['error' => 'No data found']);
}
?>
