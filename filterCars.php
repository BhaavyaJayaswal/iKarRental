<?php
// Read car data from JSON file
$carsJson = file_get_contents('cars.json');
$cars = json_decode($carsJson, true);
$result = [];

// Extract filters from query parameters
$passengers = $_GET['passengers'] ?? null;
$from = $_GET['from'] ?? null;
$until = $_GET['until'] ?? null;
$transmission = $_GET['transmission'] ?? null;
$priceMin = $_GET['price_min'] ?? null;
$priceMax = $_GET['price_max'] ?? null;

foreach ($cars as $value) {
    if (trim($transmission) !== '' && $value['transmission'] != $transmission) {
        continue; // Skip this car if it doesn't match the transmission filter
    }

    // Check passengers filter
    if (trim($passengers) !== '' && $value['passengers'] != $passengers) {
        continue; // Skip this car if it doesn't match the passengers filter
    }

    // Check priceMin filter
    if (trim($priceMin) !== '' && $value['daily_price_huf'] < $priceMin) {
        continue; // Skip this car if it doesn't meet the priceMin filter
    }

    // Check priceMax filter
    if (trim($priceMax) !== '' && $value['daily_price_huf'] > $priceMax) {
        continue; // Skip this car if it doesn't meet the priceMax filter
    }

    // If all filters are passed, add the car to the result
    $result[] = $value;
}


echo json_encode($result, JSON_PRETTY_PRINT);
?>
