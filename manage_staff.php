<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

// Fetch staff data
$stmt = $conn->prepare("SELECT staff_id, staff_name, staff_email, staff_phone, staff_role FROM staff");
$stmt->execute();
$result = $stmt->get_result();
$staffMembers = $result->fetch_all(MYSQLI_ASSOC);


$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Staff</title>
</head>
<body>
    <h2>Manage Staff</h2>

    <table>
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
            <?php if (count($staffMembers) > 0) : ?>
                <?php foreach ($staffMembers as $staff): ?>
                    <tr>
                        <td><?php echo $staff['staff_id']; ?></td>
                        <td><?php echo $staff['staff_name']; ?></td>
                        <td><?php echo $staff['staff_email']; ?></td>
                        <td><?php echo $staff['staff_phone']; ?></td>
                        <td><?php echo $staff['staff_role']; ?></td>
                        <td>
                            <a href="edit_staff.php?id=<?php echo $staff['staff_id']; ?>">Edit</a> |
                            <a href="delete_staff.php?id=<?php echo $staff['staff_id']; ?>" onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="6">No staff members found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>