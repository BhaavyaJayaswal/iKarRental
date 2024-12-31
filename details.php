<?php
session_start();

require_once 'storage.php'; // Assuming Storage, JsonIO are in this file

// Initialize data source
$filename = 'cars.json'; // JSON file containing car data
$storage = new Storage(new JsonIO($filename));

// Get the car ID from the URL
$carId = $_GET['id'] ?? null;

// Retrieve car details
$car = $storage->findById($carId);

// Handle case where car is not found
$current_user = [];
if (isset($_SESSION['user'])) {
  $current_user = $_SESSION['user'];
}
var_dump($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?> - Details</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
<header>
    <nav>
      <h1>iKarRental</h1>
        <div>
            <a href="index.php">Home</a>
            <?php if($current_user == null):?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn">Registration</a>
            <?php endif; ?>
            <?php if($current_user != null):?>
            <a href="logout.php">Logout</a>
            <a href="profile.php" class="btn">Profile</a>
            <?php endif; ?>
        </div>
    </nav>
  </header>

    <main>
        <div class="car-display">
            <div class="car-info">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" class="car-image">
                <div class="car-details">
                    <h2><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h2>
                    <p>Fuel: <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                    <p>Shifter: <?php echo htmlspecialchars($car['transmission']); ?></p>
                    <p>Year of manufacture: <?php echo htmlspecialchars($car['year']); ?></p>
                    <p>Number of seats: <?php echo htmlspecialchars($car['passengers']); ?></p>
                    <h3><?php echo number_format($car['daily_price_huf'], 0, '.', ','); ?> HUF/day</h3>
                </div>
            </div>
            <div class="actions">
                <button class="btn">Select a date</button>
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="book.php" class="btn">Book</a>
                <?php else:?>
                    <a href="login.php" class="btn">Book</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
