<?php 
// Read car data and booking data from JSON files
$carsJson = file_get_contents('cars.json');
$cars = json_decode($carsJson, true);

$bookingsJson = file_get_contents('book.json');
$bookings = json_decode($bookingsJson, true);

$result = [];

// Extract filters from query parameters
$passengers = $_GET['passengers'] ?? null;
$from = $_GET['from'] ?? null;
$until = $_GET['until'] ?? null;
$transmission = $_GET['transmission'] ?? null;
$priceMin = $_GET['price_min'] ?? null;
$priceMax = $_GET['price_max'] ?? null;

// Convert filter dates to DateTime objects for comparison
$filterStartDate = $from ? new DateTime($from) : null;
$filterEndDate = $until ? new DateTime($until) : null;

foreach ($cars as $value) {
    // Check transmission filter
    if (trim($transmission) !== '' && $value['transmission'] != $transmission) {
        continue;
    }

    // Check passengers filter
    if (trim($passengers) !== '' && $value['passengers'] != $passengers) {
        continue;
    }

    // Check priceMin filter
    if (trim($priceMin) !== '' && $value['daily_price_huf'] < $priceMin) {
        continue;
    }

    // Check priceMax filter
    if (trim($priceMax) !== '' && $value['daily_price_huf'] > $priceMax) {
        continue;
    }

    // Check if the car is booked during the filter date range
    $isAvailable = true;
    foreach ($bookings as $booking) {
        if ($booking['CarID'] == $value['id']) {
            $bookingStartDate = new DateTime($booking['StartDate']);
            $bookingEndDate = new DateTime($booking['EndDate']);

            // Check for overlapping dates
            if (
                ($filterStartDate && $filterEndDate) &&
                (
                    ($filterStartDate <= $bookingEndDate && $filterEndDate >= $bookingStartDate) || // Overlap
                    ($filterStartDate <= $bookingEndDate && !$filterEndDate) || // Open-ended end date
                    (!$filterStartDate && $filterEndDate >= $bookingStartDate) // Open-ended start date
                )
            ) {
                $isAvailable = false;
                break;
            }
        }
    }

    if (!$isAvailable) {
        continue; // Skip this car if it is not available
    }

    // If all filters are passed, add the car to the result
    $result[] = $value;
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
