<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_auth.php");
    exit();
}

// Database connection details
$host = 'localhost';
$dbname = 'db_ems';
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

$staff_id = $_SESSION['staff_id'];
$staff_role = $_SESSION['staff_role'];

// Fetch staff details
$stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
$stmt->execute(['staff_id' => $staff_id]);
$staff = $stmt->fetch();

// Fetch staff list
$stmt = $pdo->prepare("SELECT * FROM staff");
$stmt->execute();
$staff_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Default profile picture
$profile_photo = $staff['staff_photo'] ? $staff['staff_photo'] : 'default_profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            height: 100vh;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px 0;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
        .card h2 {
            margin-top: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-photo">
            <p>Welcome, <?php echo $staff['staff_name']; ?></p>
            <a href="manage_clients.php" <?php if ($staff_role != 'Manager' && $staff_role != 'System Administrator') echo 'style="display:none;"'; ?>>Manage Clients</a>
            <a href="manage_staff.php" <?php if ($staff_role != 'Manager' && $staff_role != 'System Administrator') echo 'style="display:none;"'; ?>>Manage Staff</a>
            <a href="manage_events.php">Manage Events</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="manage_payments.php">Manage Payments</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Manage Staff</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff_list as $s) : ?>
                        <tr>
                            <td><?php echo $s['staff_id']; ?></td>
                            <td><?php echo $s['staff_name']; ?></td>
                            <td><?php echo $s['staff_email']; ?></td>
                            <td><?php echo $s['staff_phone']; ?></td>
                            <td><?php echo $s['staff_role']; ?></td>
                            <td>
                                <a href="edit_staff.php?staff_id=<?php echo $s['staff_id']; ?>">Edit</a> |
                                <a href="delete_staff.php?staff_id=<?php echo $s['staff_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>