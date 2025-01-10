<?php
session_start();

// Ensure the user is logged in and has admin privileges
if (!isset($_SESSION['user']) || !isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    die("Unauthorized access. Only administrators can delete bookings.");
}

// Load the bookings file
$bookingsFile = 'book.json';
$bookings = json_decode(file_get_contents($bookingsFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (isset($_POST['booking_id'], $_POST['start_date'], $_POST['end_date'], $_POST['user_email'])) {
        $bookingId = $_POST['booking_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $userEmail = $_POST['user_email'];

        // Check if the booking exists and matches the provided details
        if (isset($bookings[$bookingId]) &&
            $bookings[$bookingId]['StartDate'] === $startDate &&
            $bookings[$bookingId]['EndDate'] === $endDate &&
            $bookings[$bookingId]['UserEmail'] === $userEmail) {

            unset($bookings[$bookingId]); // Remove the booking from the array

            // Save the updated bookings back to the file
            file_put_contents($bookingsFile, json_encode($bookings, JSON_PRETTY_PRINT));

        }
    }
} 
header('location: profile.php');
?>
