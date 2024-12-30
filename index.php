<?php
// Read car data from JSON file
$carsJson = file_get_contents('cars.json');
$cars = json_decode($carsJson, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iKarRental</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <nav>
      <h1>iKarRental</h1>
      <div>
        <a href="login.php">Login</a>
        <a href="register.php" class="btn">Registration</a>
      </div>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h2>Rent cars easily!</h2>
      <a href="register.php" class="btn">Registration</a>
    </section>

    <section class="filters">
   <form id="filter-form">
    <input type="number" id="filter-passengers" name="passengers" min="0" placeholder="Seats">
    from <input type="date" id="filter-from" name="from">
    to <input type="date" id="filter-until" name="until">
    <select id="filter-transmission" name="transmission">
      <option value="">Gear type</option>
      <option value="Automatic">Automatic</option>
      <option value="Manual">Manual</option>
    </select>
    <input type="number" id="filter-price-min" name="price_min" placeholder="Min price">
    <input type="number" id="filter-price-max" name="price_max" placeholder="Max price">
    <button type="button" id="apply-filters" class="btn">Filter</button>
  </form>
</section>



    <section class="car-list" id="car-list">
      <?php foreach ($cars as $car): ?>
        <div class="car-card">
          <a href="details.php?id=<?= $car['id'] ?>">
          <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?>">
          </a>
          <div class="car-info">
            <h3><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h3>
            <p><?= htmlspecialchars($car['passengers']) ?> seats - <?= htmlspecialchars($car['transmission']) ?></p>
            <p><?= number_format($car['daily_price_huf'], 0, '.', ',') ?> Ft</p>
            <a href="details.php?id=<?= htmlspecialchars($car['id']) ?>" class="btn">Book</a>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
    <script src="refreshCars.js"></script>
  </main>

  <footer>
    <p>&copy; 2024 iKarRental</p>
  </footer>
</body>
</html>
