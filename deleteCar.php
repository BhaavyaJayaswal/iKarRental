<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header("Location: index.php?error=unauthorized");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php?error=missingid");
    exit;
}

$carId = $_GET['id'];

$carsFile = 'cars.json';
$cars = json_decode(file_get_contents($carsFile), true);

$carFound = false;
foreach ($cars as $index => $car) {
    if ($car['id'] == $carId) {
        unset($cars[$index]);
        $carFound = true;
        break;
    }
}

if ($carFound) {
    file_put_contents($carsFile, json_encode(array_values($cars), JSON_PRETTY_PRINT));
    header('location: index.php');
}
?>
