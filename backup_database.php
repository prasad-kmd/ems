<?php
session_start();
if (!isset($_SESSION['staff_id']) || $_SESSION['staff_role'] != 'System Administrator') {
    header("Location: admin_login.php"); // Or an access denied page
    exit;
}


require_once 'db_config.php';

//Get database credentials
$dbHost = $servername;     
$dbName = $dbname;
$dbUser = $username;
$dbPass = $password;



// Backup the database
$backupFileName = 'db_backup_' . date("YmdHis") . '.sql'; // Create a timestamped filename

$command = "mysqldump -h $dbHost -u $dbUser -p$dbPass $dbName > $backupFileName";


$output = shell_exec($command); //Execute shell command


// Set headers to force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $backupFileName . '"');
header('Content-Length: ' . filesize($backupFileName));

// Download the backup file
readfile($backupFileName);

//Delete the file from the server after download
unlink($backupFileName);



// Close the database connection (if you haven't already)
// $conn->close(); //This might not be needed if the connection is closed elsewhere



//Optional: Show error message if back up failed.
if ($output === null) {
   echo "Error creating database backup!";
}

exit; // Important: Stop further script execution
?>