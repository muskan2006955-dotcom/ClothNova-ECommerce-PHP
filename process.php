<?php
include("db.php");

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate Email Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Email found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo "✅ Login successful! Welcome back, " . htmlspecialchars($email);
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);
        if ($stmt->execute()) {
            echo "🎉 Signup successful! Welcome, " . htmlspecialchars($email);
        } else {
            echo "❌ Error: " . $stmt->error;
        }
    }

    $check->close();
    $conn->close();
} else {
    echo "Form not submitted properly.";
}
?>
