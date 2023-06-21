<?php

session_start();

require_once dirname(__FILE__) . '/../../db/config.php';


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
    <style>
        .table-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #ccc;
            padding: 8px;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../../templates/navbar.php'; ?>
    </header>

    <main class="main">
        <div class="table-container">
            <table>
                <center>
                    <h1>All Users</h1>
                </center>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <form action="../../db/actions/klantwijzigen.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>" required>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><input type="text" name="username" value="<?php echo $user['username']; ?>"></td>
                                <td><input type="text" name="password" placeholder="<?php echo $user['password']; ?>"></td>
                                <td><input type="text" name="address" value="<?php echo $user['address']; ?>"</td>
                                <td><input type="email" name="email" value="<?php echo $user['email']; ?>"</td>
                                <td><input type="text" name="phone" value="<?php echo $user['phone']; ?>"</td>
                                <td>
                                    <input type="submit" name="submit" value="Update">
                                    <input type="submit" name="submit" value="Delete">
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
    <!-- Add this JavaScript code after the PHP code -->
<script>
    // Check if the URL contains the success parameter
    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');

    if (successMessage) {
        // Show the success alert
        alert(successMessage);
    }
</script>
</body>

</html>
