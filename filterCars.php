<?php 
$carsJson = file_get_contents('cars.json');
$cars = json_decode($carsJson, true);

$bookingsJson = file_get_contents('book.json');
$bookings = json_decode($bookingsJson, true);

$result = [];

$passengers = $_GET['passengers'] ?? null;
$from = $_GET['from'] ?? null;
$until = $_GET['until'] ?? null;
$transmission = $_GET['transmission'] ?? null;
$priceMin = $_GET['price_min'] ?? null;
$priceMax = $_GET['price_max'] ?? null;

$filterStartDate = $from ? new DateTime($from) : null;
$filterEndDate = $until ? new DateTime($until) : null;

foreach ($cars as $value) {
    if (trim($transmission) !== '' && $value['transmission'] != $transmission) {
        continue;
    }

    if (trim($passengers) !== '' && $value['passengers'] != $passengers) {
        continue;
    }

    if (trim($priceMin) !== '' && $value['daily_price_huf'] < $priceMin) {
        continue;
    }

    if (trim($priceMax) !== '' && $value['daily_price_huf'] > $priceMax) {
        continue;
    }

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
        continue; 
    }

    $result[] = $value;
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
