<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage EMS's Staff</title>
    <link rel="stylesheet" href="../assets/css/semantic.css" />
    <link rel="stylesheet" href="../assets/font/fonts.css" />
</head>

<body>
    <!-- Nav Bar -->
    <div class="ui inverted segment">
        <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
            <div class="item" width="50px">
                <img src="../assets/images/logo.webp" alt="Company Logo" width="50px">
            </div>
            <a class="active item">
                Manage Staff Members
            </a>
            <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
            <div class="right menu">
                <div class="item">
                    <a href="../admin_dashboard.php"><button class="ui right inverted teal labeled icon button">
                            <i class="asterisk loading icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Back to Dashboard</span>
                        </button></a>
                    <a href="../logout.php"><button class="ui right inverted secondary labeled icon button">
                            <i class="sign out alternate icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
                        </button></a>
                    <!-- &nbsp; -->
                </div>
            </div>
        </div>
    </div>
    <div class="ui fluid vertical menu" style="padding: 5px;">
        <span class="item" style="font-family: Neuropol;">Manage Existing Staff Members</span>
    </div>
    <!-- Nav Bar ends -->
    <!-- table begins -->
    <table class="ui celled striped compact padded yellow table">
        <thead>
            <tr>
                <th>Staff ID</th>
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
                            <div class="ui animated button" tabindex="0"><a href="edit_staff.php?id=<?php echo $staff['staff_id']; ?>">
                                    <div class="visible content">Edit</div>
                                    <div class="hidden content">
                                        <i class="edit outline icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui vertical animated button" tabindex="0"><a href="delete_staff.php?id=<?php echo $staff['staff_id']; ?>" onclick="return confirm('Are you sure you want to delete this staff member?')">
                                    <div class="visible content">Delete</div>
                                    <div class="hidden content">
                                        <i class="archive icon"></i>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">No staff members found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- table ends -->
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>