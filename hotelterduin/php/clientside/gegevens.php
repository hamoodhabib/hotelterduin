<?php
session_start();

require_once '../../db/config.php';

$db = new Db();
$PDO = $db->getPDO();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user information from the users table
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $PDO->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    // Redirect or handle the case when user doesn't exist
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve the updated information from the form
    $newUsername = $_POST['username'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $newAddress = $_POST['address'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];

    // Update user information in the users table
    $updateSql = "UPDATE users SET username = :username, password = :password, address = :address, email = :email, phone = :phone WHERE username = :currentUsername";
    $updateStmt = $PDO->prepare($updateSql);
    $updateStmt->bindParam(':username', $newUsername);
    $updateStmt->bindParam(':password', $newPassword);
    $updateStmt->bindParam(':address', $newAddress);
    $updateStmt->bindParam(':email', $newEmail);
    $updateStmt->bindParam(':phone', $newPhone);
    $updateStmt->bindParam(':currentUsername', $username);
    $updateStmt->execute();

    // Update the session variable with the new username
    $_SESSION['username'] = $newUsername;

    // Redirect to a success page or display a success message
    header("Location: gegevens.php?success=Profile updated successfully");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <!-- <link rel="stylesheet" href="../../css/gegevens.css"> -->
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>
    <br><br>
    <div class="formulier">
        <div class="profile-form">
            <h1>Edit Profile</h1>
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                <br><br>
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
                <br><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                <br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                <br><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                <br><br>
                <input type="submit" name="submit" value="Update Profile">
            </form>
            <br><br>
            <form action="delete.php" method="post">
                <input type="submit" name="submit" value="Delete Profile">
            </form>
        </div>
    </div>
</body>

</html>
