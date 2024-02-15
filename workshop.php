<?php
// Step 2: Form validation
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $name = test_input($_POST["name"]);
    if (empty($name)) {
        $errors["name"] = "Name is required";
    }

    // Validate email
    $email = test_input($_POST["email"]);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Valid email is required";
    }

    // Validate password
    $password = test_input($_POST["password"]);
    if (empty($password) || strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long";
    }

    // Confirm password
    $confirmPassword = test_input($_POST["confirm_password"]);
    if ($password !== $confirmPassword) {
        $errors["confirm_password"] = "Passwords do not match";
    }

    // If no validation errors, proceed with registration
    if (empty($errors)) {
        // Step 4: Read existing users from "users.json"
        $usersFile = "users.json";
        $usersData = json_decode(file_get_contents($usersFile), true);

        // Step 5: Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Step 6: Add new user's data to the array
        $newUser = array(
            "name" => $name,
            "email" => $email,
            "password" => $hashedPassword
        );

        // Step 7: Update the array and write it back to "users.json"
        $usersData[] = $newUser;
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        // Step 8: Provide user feedback
        echo "<div class='success'>Registration successful!</div>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h2>User Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Name: <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
        <span class="error"><?php echo isset($errors["name"]) ? $errors["name"] : ''; ?></span><br>

        Email: <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        <span class="error"><?php echo isset($errors["email"]) ? $errors["email"] : ''; ?></span><br>

        Password: <input type="password" name="password">
        <span class="error"><?php echo isset($errors["password"]) ? $errors["password"] : ''; ?></span><br>

        Confirm Password: <input type="password" name="confirm_password">
        <span class="error"><?php echo isset($errors["confirm_password"]) ? $errors["confirm_password"] : ''; ?></span><br>

        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
