<?php
session_start();

//require_once 'storage.php'; // Assuming Storage, JsonIO are in this file
$cars = json_decode(file_get_contents("cars.json"), true);

// Initialize data source
//$filename = 'cars.json'; // JSON file containing car data
//$storage = new Storage(new JsonIO($filename));
$selected = false;
// Get the car ID from the URL
$carId = $_GET['id'] ?? null;
$car = null;
foreach ($cars as $item) {
    if ($item['id'] == $carId) {
        $car = $item;
        break;
    }
}
// Retrieve car details
//$car = $storage->findById($carId);
var_dump($carId);
var_dump($car);
// Handle case where car is not found
$current_user = [];
if (isset($_SESSION['user'])) {
  $current_user = $_SESSION['user'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    // Validate the dates
    if ($start_date && $end_date && strtotime($start_date) && strtotime($end_date) && $start_date <= $end_date) {
        // Store the dates in the session
        $_SESSION['selected_dates'] = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'car_id' => $carId
        ];
        $selected = true;
    } else {
        $error = "Invalid date range. Please try again.";
    }
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
            <div class="actions book">
            <form class="dates" method="post">
                <label for="start_date">from:</label>
                <input type="date" name="start_date" required>
                <label for="end_date">to:</label>
                <input type="date" name="end_date" required>
                <button type="submit" class="btn">Select these Dates</button>
            </form>
                <?php if($selected == true): ?>
                    <span style="color: #ffc107; text-align: center;">
                        Dates selected!
                    </span>
                <?php endif; ?>
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="book.php" class="btn">Book</a>
                <?php else:?>
                    <a href="login.php" class="btn">Book</a>
                    <span style="color: #ffc107; text-align: center;">
                        (You're not logged in, and will be redirected to the login page first.)
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
