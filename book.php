<?php
session_start();
$users = json_decode(file_get_contents("users.json"), true);
$book = json_decode(file_get_contents("book.json"), true);
$cars = json_decode(file_get_contents("cars.json"), true);
$customer = $_SESSION['user'];
var_dump($users[$customer]['EmailAddress']);

$selectedDates = $_SESSION['selected_dates'] ?? null;
$carId = $selectedDates['car_id']; 
$startDate = $selectedDates['start_date'];
$endDate = $selectedDates['end_date'];

$car = null;
foreach ($cars as $item) {
    if ($item['id'] == $carId) {
        $car = $item;
        break;
    }
}
// Check if the car is available in the selected date range
$isAvailable = true;

foreach ($book as $booking) {
    if (
        $booking['CarID'] == $carId &&
        $startDate <= $booking['EndDate'] &&
        $endDate >= $booking['StartDate']
    ) {
        $isAvailable = false;
        break;
    }
}

if ($isAvailable) {
    $book = json_decode(file_get_contents('book.json'), true);
    $book[$carId] = [
        'StartDate' => $startDate,
        'EndDate' => $endDate,
        'UserEmail' => $users[$customer]['EmailAddress'],
        'CarID' => $carId
    ];
   
    var_dump(json_decode(file_get_contents("book.json"), true));

    file_put_contents('book.json', json_encode($book, JSON_PRETTY_PRINT));
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
        <h1>iKarRental</h1>
        <div>
            <a href="logout.php">Logout</a>
            <a href="profile.php" class="btn">Profile</a>
        </div>
        </nav>
  </header>
    <main>
    <div class="result">
        <?php if($isAvailable):?>
            <h2>Booking Successful</h2>
            <img src="successful.jpg" alt="Green tick" width="auto" height="100">
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
                <p><strong>Model:</strong> <?php echo htmlspecialchars($car['model']); ?></p>
                <p><strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                <p><strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?></p>
                <p><strong>Seats:</strong> <?php echo htmlspecialchars($car['passengers']); ?></p>
                <p><strong>Price per day:</strong> <?php echo number_format($car['daily_price_huf'], 0, '.', ','); ?> HUF</p>
                <p><strong>Booking Dates:</strong> <?php echo htmlspecialchars($startDate) . ' to ' . htmlspecialchars($endDate); ?></p>
            <?php else:?>
                <h2>Booking Failed</h2>
                <p>Car is not available in the selected date range.</p>
                <a class="btn" href="index.php">Home</a>
        <?php endif;?>
            </div>
    </main>
</body>
</html>