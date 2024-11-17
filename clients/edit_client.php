<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM client WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $conn->close(); //Close connection after data fetch
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Client</title>
            <link rel="stylesheet" href="../assets/css/semantic.css">
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
                        Edit a Client
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
            <!-- menu begins -->
            <div class="ui fluid vertical menu" style="padding: 5px;">
                <span class="item" style="font-family: Neuropol;">Edit a Existing Client</span>
            </div>
            <!-- menu ends -->
            <!-- Nav Bar ends -->
            <!-- form begins -->
            <form class="ui form" style="padding: 25px;" method="POST" action="process_edit_client.php" enctype="multipart/form-data">
                <span style="font-family: 'Orbit';">
                    <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="client_name">Client's Current Name</label>
                            <input type="text" id="client_name" name="client_name" value="<?php echo $client['client_name']; ?>" required />
                        </div>
                        <div class="required field">
                            <label for="client_email">Client's Current Email</label>
                            <input type="email" id="client_email" name="client_email" value="<?php echo $client['client_email']; ?>" required />
                        </div>
                    </div>
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="client_phone">Client's Current Phone</label>
                            <input type="text" id="client_phone" name="client_phone" value="<?php echo $client['client_phone']; ?>" required />
                        </div>
                        <div class="required field">
                            <label for="client_address">Client's Current Address</label>
                            <input type="text" id="client_address" name="client_address" value="<?php echo $client['client_address']; ?>" required />
                        </div>
                    </div>
                    <button class="ui fluid button" type="submit" style="font-family: 'Neuropol';">Upadate the Client Details</button>
                </span>
            </form>
            <div class="ui bottom attached warning message">
                <i class="icon help"></i>
                Administrator can only change critical client information like client's e-mail, phone & address. Rest of details can be only changed by <b><?php echo $client['client_name']; ?></b> 
            </div>
            <!-- form ends -->
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
        </body>

        </html>

<?php
    } else {
        echo "Client not found.";
    }
    $stmt->close();
} else {
    echo "Invalid client ID.";
}

?>