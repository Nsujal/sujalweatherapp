<?php
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
    } else {
        // Check if the email is already registered
        $usersData = json_decode(file_get_contents("users.json"), true);
        foreach ($usersData as $user) {
            if ($user["email"] == $email) {
                $errors["email"] = "Email is already registered";
                break;
            }
        }
    }

    // Validate password strength
    $password = test_input($_POST["password"]);
    if (empty($password) || strlen($password) < 8 || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])/", $password)) {
        $errors["password"] = "Password must be at least 8 characters long and include uppercase letters, numbers, and special characters";
    }

    $confirmPassword = test_input($_POST["confirm_password"]);
    if ($password !== $confirmPassword) {
        $errors["confirm_password"] = "Passwords do not match";
    }

    if (empty($errors)) {
        // ... (remaining code remains the same)
    }
}

// ... (remaining code remains the same)
?>
