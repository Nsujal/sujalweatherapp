<?php
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);

    $usersData = json_decode(file_get_contents("users.json"), true);

    $authenticated = false;

    foreach ($usersData as $user) {
        if ($user["email"] == $email && password_verify($password, $user["password"])) {
            $authenticated = true;
            break;
        }
    }

    if ($authenticated) {
        // Successful login, redirect or display a success message
        echo "<div class='success'>Login successful!</div>";
    } else {
        $errors["login"] = "Invalid email or password";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content remains the same -->
</head>
<body>
    <h2>User Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Email: <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        <br>

        Password: <input type="password" name="password">
        <span class="error"><?php echo isset($errors["login"]) ? $errors["login"] : ''; ?></span><br>

        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
