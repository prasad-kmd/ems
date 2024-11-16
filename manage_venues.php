<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Venues</title>
</head>
<body>
    <h2>Manage Venues</h2>

    <a href="add_venue.php">Add New Venue</a> <hr>


    <h3>Existing Venues</h3>

    <?php
    $venuesStmt = $conn->prepare("SELECT venue_id, venue_name, venue_address, venue_capacity FROM venue");
    $venuesStmt->execute();
    $venuesStmt->bind_result($venueId, $venueName, $venueAddress, $venueCapacity);
    ?>
    <table>
      <thead>
          <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Address</th>
              <th>Capacity</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
          <?php while ($venuesStmt->fetch()): ?>
            <tr>
                <td><?php echo $venueId; ?></td>
                <td><?php echo $venueName; ?></td>
                <td><?php echo $venueAddress; ?></td>
                <td><?php echo $venueCapacity; ?></td>
                <td>
                    <a href="edit_venue.php?id=<?php echo $venueId; ?>">Edit</a> |
                    <a href="delete_venue.php?id=<?php echo $venueId; ?>" onclick="return confirm('Are you sure you want to delete this venue?')">Delete</a>
                </td>
            </tr>
          <?php endwhile; ?>
      </tbody>
    </table>

    <?php
    $venuesStmt->close();
    $conn->close();
    ?>
</body>
</html>