<?php
    session_start();
    $users = json_decode(file_get_contents("users.json"), true);

    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $pw1 = $_POST['pw1'] ?? '';
    $pw2 = $_POST['pw2'] ?? '';

    $errors = [];
    
    if (count($_POST)> 0) {

        if (trim($fullname) === '')
            $errors['fullname'] = 'Name field is required!';
        else if (count(explode(' ', trim($fullname))) < 2)
            $errors['fullname'] = 'The name should contain at least two words!';

         if (trim($email) === '')
            $errors['email'] = 'Email is required!';
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors['email'] = 'The e-mail address is not valid';

        if (trim($pw1) === '')
            $errors['pw1'] = 'Password is required!';
        else if (strlen(trim($pw1)) < 8)
            $errors['pw1'] = 'Password must be atleast 8 characters';
        if (trim($pw1) != trim($pw2))
        $errors['pw2'] = 'Passwords must match';
    }

    if (count($errors) == 0) {
        $users = json_decode(file_get_contents("users.json"), true);
        $users[$fullname] = [
            'FullName' => $fullname,
            'EmailAddress' => $email,
            'Password' => $pw1,
            'isAdmin' => false,
        ];
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
    }
    var_dump($_SESSION);
    //header('location: index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
      <h1>iKarRental</h1>
      <div>
        <a href="login.php" class="btn">Login</a>
      </div>
    </nav>
  </header>
  <div class="register">
        <form action="register.php" method="post" class="register">
            <label for="username">Username: </label><input type="text" name="fullname"><?= $errors['fullname'] ?? '' ?><br>
            <label for="email">Email: </label><input type="text" name="email"><?= $errors['email'] ?? '' ?><br>
            <label for="password">Password: </label><input type="password" name="pw1"><?= $errors['pw1'] ?? '' ?><br>
            <label for="password">Password Again: </label><input type="password" name="pw2"><?= $errors['pw2'] ?? '' ?><br>
            <button type="submit" class=btn>Save</button>
        </form>
        
        <?php if (count($_POST) > 0 && count($errors) == 0): ?>
            <span style="color: green;">Successfully saved!</span><br>
        <?php endif; ?>
    </div>
</body>
</html>