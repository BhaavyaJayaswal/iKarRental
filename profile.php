<?php
session_start();

// Load data
$cars = json_decode(file_get_contents("cars.json"), true);
$users = json_decode(file_get_contents("users.json"), true);
$bookings = json_decode(file_get_contents("book.json"), true);

$current_user = [];
$isAdmin = false;

if (isset($_SESSION['user'])) {
    $current_user = $_SESSION['user'];
    $isAdmin = $_SESSION['isAdmin'];
} 

$userBookings = [];
if ($isAdmin) {
    $userBookings = $bookings; // Admin sees all bookings
} else {
    foreach ($bookings as $id => $booking) {
        if ($booking['UserEmail'] === $users[$current_user]['EmailAddress']) {
            $userBookings[$id] = $booking;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    </head>
<body>
<header>
    <nav>
      <h1>iKarRental</h1>
        <div>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
            <a href="profile.php" class="btn">Profile</a>
        </div>
    </nav>
  </header>

    <h1>Welcome, <?= htmlspecialchars($users[$current_user]['FullName']) ?></h1>
    <h2>Your Bookings:</h2>

    <?php if (!empty($userBookings)): ?>
        <?php foreach ($userBookings as $id => $booking): ?>
            <?php
            $carId = $booking['CarID'];
            $carDetails = array_filter($cars, fn($car) => $car['id'] == $carId);
            $carDetails = reset($carDetails); 
            ?>
            <div class="booking-container">
                <img src="<?= htmlspecialchars($carDetails['image']) ?>" alt="Car Image" class="booking-image">
                <div class="booking-info">
                    <p><strong>Car:</strong> <?= htmlspecialchars($carDetails['brand'] . ' ' . $carDetails['model']) ?></p>
                    <p><strong>Year:</strong> <?= htmlspecialchars($carDetails['year']) ?></p>
                    <p><strong>Transmission:</strong> <?= htmlspecialchars($carDetails['transmission']) ?></p>
                    <p><strong>Booking Dates:</strong> <?= htmlspecialchars($booking['StartDate']) ?> to <?= htmlspecialchars($booking['EndDate']) ?></p>
                </div>
                <?php if ($isAdmin): ?>
                    <form method="POST" action="delete_booking.php">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($id) ?>">
                        <input type="hidden" name="start_date" value="<?= htmlspecialchars($booking['StartDate']) ?>">
                        <input type="hidden" name="end_date" value="<?= htmlspecialchars($booking['EndDate']) ?>">
                        <input type="hidden" name="user_email" value="<?= htmlspecialchars($booking['UserEmail']) ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</body>
</html>
