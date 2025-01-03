<?php
session_start();

$cars = json_decode(file_get_contents("cars.json"), true);

$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$year = $_POST['year'] ?? '';
$transmission = $_POST['transmission'] ?? '';
$fuel_type = $_POST['fuel_type'] ?? '';
$passengers = $_POST['passengers'] ?? '';
$daily_price_huf = $_POST['daily_price_huf'] ?? '';
$image = $_POST['image'] ?? '';

$errors = [];

if (count($_POST) > 0) {
    if (trim($brand) === '') {
        $errors['brand'] = 'Brand is required!';
    }
    if (trim($model) === '') {
        $errors['model'] = 'Model is required!';
    }
    if (trim($year) === '' || !is_numeric($year) || $year < 1886 || $year > date("Y")) {
        $errors['year'] = 'Enter a valid year!';
    }
    if (trim($transmission) === '' || !in_array($transmission, ['Manual', 'Automatic'])) {
        $errors['transmission'] = 'Select a valid transmission type!';
    }
    if (trim($fuel_type) === '' || !in_array($fuel_type, ['Petrol', 'Diesel'])) {
        $errors['fuel_type'] = 'Select a valid fuel type!';
    }
    if (trim($passengers) === '' || !is_numeric($passengers) || $passengers <= 0) {
        $errors['passengers'] = 'Enter a valid number of passengers!';
    }
    if (trim($daily_price_huf) === '' || !is_numeric($daily_price_huf) || $daily_price_huf <= 0) {
        $errors['daily_price_huf'] = 'Enter a valid daily price!';
    }
    if (trim($image) === '' || !filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = 'Enter a valid image URL!';
    }

    // If no errors, save the data
    if (count($errors) == 0) {
        $newCar = [
            'id' => count($cars) + 1,
            'brand' => $brand,
            'model' => $model,
            'year' => (int)$year,
            'transmission' => $transmission,
            'fuel_type' => $fuel_type,
            'passengers' => (int)$passengers,
            'daily_price_huf' => (int)$daily_price_huf,
            'image' => $image
        ];

        $cars[] = $newCar;
        file_put_contents('cars.json', json_encode($cars, JSON_PRETTY_PRINT));

        $_SESSION['success'] = 'Car added successfully!';
        header('Location: add.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
      <h1>iKarRental</h1>
      <div>
          <a href="logout.php">Logout</a>
          <a class="btn" href="index.php">Home</a>
          <a href="profile.php" class="btn">Profile</a>
      </div>
    </nav>
  </header>
    <h1>Add a New Car</h1>
    <?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    <form method="POST" action="add.php" class="form-Cars">
        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required><span style="color: red;"><?= $errors['brand'] ?? '' ?></span><br>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><span style="color: red;"><?= $errors['model'] ?? '' ?></span><br>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><span style="color: red;"><?= $errors['year'] ?? '' ?></span><br>
        <div>
        <label>Transmission:</label>
        <input type="radio" id="manual" name="transmission" value="Manual" required>
        <label for="manual">Manual</label>
        <input type="radio" id="automatic" name="transmission" value="Automatic" required>
        <label for="automatic">Automatic</label><br>
        <span style="color: red;"><?= $errors['transmission'] ?? '' ?></span><br>
        </div>
        <div>
        <label>Fuel Type:</label>
        <input type="radio" id="petrol" name="fuel_type" value="Petrol" required>
        <label for="petrol">Petrol</label>
        <input type="radio" id="diesel" name="fuel_type" value="Diesel" required>
        <label for="diesel">Diesel</label><br>
        <span style="color: red;"><?= $errors['fuel_type'] ?? '' ?></span>
        </div>
        <label for="passengers">Passengers:</label>
        <input type="number" id="passengers" name="passengers" required><?= $errors['passengers'] ?? '' ?><br>

        <label for="daily_price_huf">Daily Price (HUF):</label>
        <input type="number" id="daily_price_huf" name="daily_price_huf" required><?= $errors['daily_price_huf'] ?? '' ?><br>

        <label for="image">Image URL:</label>
        <input type="url" id="image" name="image" required><?= $errors['image'] ?? '' ?><br>

        <button class="btn" type="submit">Add Car</button>
    </form>
</body>
</html>
