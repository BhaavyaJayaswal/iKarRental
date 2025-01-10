<?php
session_start();

$cars = json_decode(file_get_contents("cars.json"), true);
$carId = $_GET['id'] ?? null;

$car = null;
foreach ($cars as $c) {
    if ($c['id'] == $carId) {
        $car = $c;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car['brand'] = $_POST['brand'];
    $car['model'] = $_POST['model'];
    $car['year'] = $_POST['year'];
    $car['transmission'] = $_POST['transmission'];
    $car['fuel_type'] = $_POST['fuel_type'];
    $car['passengers'] = $_POST['passengers'];
    $car['daily_price_huf'] = $_POST['daily_price_huf'];
    $car['image'] = $_POST['image'];

    $cars[array_search($carId, array_column($cars, 'id'))] = $car;
    file_put_contents('cars.json', json_encode($cars, JSON_PRETTY_PRINT));

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modify Car</title>
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
    <h2>Modify Car Details</h2>

    <form method="POST" class="modify">
      <label for="brand">Brand</label>
      <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>

      <label for="model">Model</label>
      <input type="text" id="model" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>

      <label for="year">Year</label>
      <input type="number" id="year" name="year" value="<?= htmlspecialchars($car['year']) ?>" required>

      <label for="transmission">Transmission</label>
      <select id="transmission" name="transmission">
        <option value="Automatic" <?= $car['transmission'] == 'Automatic' ? 'selected' : '' ?>>Automatic</option>
        <option value="Manual" <?= $car['transmission'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
      </select>

      <label for="fuel_type">Fuel Type</label>
      <input type="text" id="fuel_type" name="fuel_type" value="<?= htmlspecialchars($car['fuel_type']) ?>" required>

      <label for="passengers">Passengers</label>
      <input type="number" id="passengers" name="passengers" value="<?= htmlspecialchars($car['passengers']) ?>" required>

      <label for="daily_price_huf">Daily Price (HUF)</label>
      <input type="number" id="daily_price_huf" name="daily_price_huf" value="<?= htmlspecialchars($car['daily_price_huf']) ?>" required>

      <label for="image">Image URL</label>
      <input type="url" id="image" name="image" value="<?= htmlspecialchars($car['image']) ?>" required>

      <button type="submit" class="btn">Save Changes</button>
    </form>
  </main>

  <footer>
    <p>&copy; 2025 iKarRental</p>
  </footer>

</body>
</html>
