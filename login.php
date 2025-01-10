<?php
    session_start();
    
    if(!isset($_SESSION['user']) && count($_POST) > 0){
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $users = json_decode(file_get_contents("users.json"), true);
        $name = array_keys(array_filter($users, fn($u) => $u['FullName'] === $username));

        if (!empty($name)) {
            $name = $name[0];
            if ($password === $users[$name]['Password']) {
                $_SESSION['user'] = $name;
                if ($users[$name]['AdminStatus'] == true){
                    $_SESSION['isAdmin'] = true;
                }
                    else
                    $_SESSION['isAdmin'] = false;
                header('location: index.php');
            } else {
                $_SESSION['loginerror'] = 2; // Incorrect password
            }
        } else {
            $_SESSION['loginerror'] = 1; // Username not found
        }
    }
    $loginerror = '';
    //$current_user = [];
    if (isset($_SESSION['loginerror'])) {
        switch ($_SESSION['loginerror']) {
            case 1:
                $loginerror = 'The username is incorrect';
                break;
            case 2:
                $loginerror = 'Wrong Password';
                break;
        }
    }
    //var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
      <h1>iKarRental</h1>
      <div>
        <a href="register.php" class="btn">Registration</a>
        <a href="logout.php" class="btn">Logout</a>
      </div>
    </nav>
  </header>
    <form action="login.php" method="post" class="login">
        <label for="username">Username: </label><input type="text" name="username" id="username"><br>
        <label for="password">Password: </label><input type="password" name="password" id="password"><br>
        <button class="btn" type="submit">Login</button>
        <span style='color:red'><?= $loginerror ?? '' ?></span>
        <?php if (isset($_SESSION['loginerror'])): ?>
            <?php unset($_SESSION['loginerror'])?>
        <?php endif; ?>
        </form>

</body>
</html>