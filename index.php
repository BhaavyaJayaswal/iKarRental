<?php
session_start();

$cars = json_decode(file_get_contents("cars.json"), true);
$reg = json_decode(file_get_contents("users.json"), true);
$current_user = [];
$isAdmin = false;
if (isset($_SESSION['user'])) {
  $current_user = $_SESSION['user'];
  $isAdmin = $_SESSION['isAdmin'];
  //var_dump($_SESSION['isAdmin']);
}
//var_dump($_SESSION);
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
    <section class="hero">
      <h2>Rent cars easily!</h2>
      <?php if($current_user == null):?>
        <a href="register.php" class="btn">Registration</a>
      <?php endif; ?>
    </section>

    <?php if($isAdmin): ?>
      <section class="addCars">
        <a href="add.php" class="btn">Add Cars</a>
      </section>
    <?php endif; ?>

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
            <?php if ($isAdmin): ?>
            <a href="deleteCar.php?id=<?= htmlspecialchars($car['id']) ?>" class="btn delete-button">Delete</a>
            <a href="modifyCar.php?id=<?= htmlspecialchars($car['id']) ?>" class="btn modify-button">Modify</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
    <script src="refreshCars.js"></script>
  </main>

  <footer>
    <p>&copy; 2025 iKarRental</p>
  </footer>
</body>
</html>
