<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

// Fetch client data
$stmt = $conn->prepare("SELECT client_id, client_name, client_email, client_phone, client_address FROM client");
$stmt->execute();
$result = $stmt->get_result();
$clients = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients</title>
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
                Manage Clients
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
        <span class="item" style="font-family: Neuropol;">Manage Existing Clients</span>
    </div>
    <!-- Nav Bar ends -->
    <!-- table begins -->
    <table class="ui celled striped compact padded blue table">
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clients) > 0): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['client_id']; ?></td>
                        <td><?php echo $client['client_name']; ?></td>
                        <td><?php echo $client['client_email']; ?></td>
                        <td><?php echo $client['client_phone']; ?></td>
                        <td><?php echo $client['client_address']; ?></td>
                        <td>
                            <div class="ui animated button" tabindex="0"><a href="edit_client.php?id=<?php echo $client['client_id']; ?>">
                                    <div class="visible content">Edit</div>
                                    <div class="hidden content">
                                        <i class="edit outline icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui vertical animated button" tabindex="0"><a href="delete_client.php?id=<?php echo $client['client_id']; ?>" onclick="return confirm('Are you sure you want to delete this client?')">
                                    <div class="visible content">Delete</div>
                                    <div class="hidden content">
                                        <i class="archive icon"></i>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No clients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- table ends -->
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>