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

// Fetch all user information from the Users table
$sql = "SELECT * FROM users";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <h1>Edit Customer</h1>
        <?php foreach ($users as $user) { ?>
            <div class="formulier">
                <form action="../../db/actions/klantwijzigen.php" method="post">
                    <div class="formulier-text">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>" required>
                        <br>
                        <label for="username">Username:</label>
                        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                        <br>
                        <label for="password">Password:</label>
                        <input type="text" name="password" placeholder="<?php echo $user['password']; ?>" required>
                        <br>
                        <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                        <label for="address">Address:</label>
                        <input type="text" name="address" value="<?php echo $user['address']; ?>" required>
                        <br>
                        <label for="email">Email:</label>
                        <input type="text" name="email" value="<?php echo $user['email']; ?>" required>
                        <br>
                        <label for="phone">Phone:</label>
                        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

                        <br /><br />
                        <input type="submit" name="submit" value="Update Customer">
                    </div>
                </form>
                <br>
                <form action="../../db/actions/klantverwijderen.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                    <br><br /><br /><br /><br /><br /><br />
                    <input type="submit" name="submit" value="Delete Customer">
                </form>
            </div>
        <?php } ?>
    </main>
</body>

</html>
