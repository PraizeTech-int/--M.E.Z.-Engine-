<?php
include "connect.php"; // Database connection

// === SIGN-UP ===
if (isset($_POST['signUp'])) {
    $firstName = $conn->real_escape_string($_POST['fName'] ?? '');
    $lastName = $conn->real_escape_string($_POST['lName'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($firstName && $lastName && $email && $password) {
        // Check if email already exists
        $checkEmail = "SELECT * FROM info WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "Email Address Already Exists!";
        } else {
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO info (fName, lName, email, password)
                            VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
            if ($conn->query($insertQuery) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Please fill in all sign-up fields.";
    }
}

// === SIGN-IN ===
if (isset($_POST['signIn'])) {
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Retrieve user by email
        $sql = "SELECT * FROM info WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['email'] = $row['email'];
                header("Location: homepage.php");
                exit();
            } else {
                echo "Incorrect Email or Password!";
            }
        } else {
            echo "Incorrect Email or Password!";
        }
    } else {
        echo "Please enter both email and password.";
    }
}
?>