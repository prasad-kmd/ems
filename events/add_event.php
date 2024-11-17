<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
  header("Location: admin_login.php");
  exit;
}
require_once '../db_config.php';


//Fetch venues for the dropdown
$venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
$venuesStmt->execute();
$venuesStmt->bind_result($venueId, $venueName);
$venues = [];
while ($venuesStmt->fetch()) {
  $venues[$venueId] = $venueName;
}
$venuesStmt->close();
$conn->close();

?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create an Event</title>
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
        Create an Event
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
          <a href="logout.php"><button class="ui right inverted secondary labeled icon button">
              <i class="sign out alternate icon"></i>
              <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
            </button></a>
          <!-- &nbsp; -->
        </div>
      </div>
    </div>
  </div>
  <div class="ui fluid vertical menu" style="padding: 5px;">
    <span class="item" style="font-family: Neuropol;">Create New Event</span>
  </div>
  <!-- Nav Bar ends -->
  <!-- form begins -->
  <form class="ui form" style="padding: 25px;" method="POST" action="process_add_event.php" enctype="multipart/form-data">
    <span style="font-family: 'Orbit';">
      <div class="required field">
        <label for="event_title">Event Title:</label>
        <input type="text" id="event_title" name="event_title" required placeholder="Event Title">
      </div>
      <div class="required field">
        <label for="event_description">Event Description:</label>
        <textarea rows="2" id="event_description" name="event_description" required placeholder="Event Description"></textarea>
      </div>
      <div class="file input field">
        <label for="event_title">Event Photo:</label>
        <input type="file" id="event_photo" name="event_photo" />
      </div>
      <div class="four fields">
        <div class="required field">
          <label for="event_price">Price (LKR) [Per Seat]:</label>
          <input type="number" id="event_price" name="event_price" step="0.01" required>
        </div>
        <div class="required field">
          <label for="event_date">Date:</label>
          <input type="date" id="event_date" name="event_date" required>
        </div>
        <div class="required field">
          <label for="event_start_time">Start Time:</label>
          <input type="time" id="event_start_time" name="event_start_time" required>
        </div>
        <div class="required field">
          <label for="event_end_time">End Time:</label>
          <input type="time" id="event_end_time" name="event_end_time" required>
        </div>
      </div>
      <div class="three fields">
        <div class="required field">
          <label for="venue_id">Venue:</label>
          <select id="venue_id" name="venue_id" required>
            <?php foreach ($venues as $id => $name): ?>
              <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="required field">
          <label for="event_capacity">Capacity:</label>
          <input type="number" id="event_capacity" name="event_capacity" placeholder="Capacity of the Event" required>
        </div>
        <div class="required field">
          <label for="event_type">Event Type:</label>
          <select id="event_type" name="event_type" required>
            <option value="Conferences">Conferences</option>
            <option value="Seminars">Seminars</option>
            <option value="Sports Events">Sports Events</option>
            <option value="Weddings">Weddings</option>
            <option value="Birthday Parties">Birthday Parties</option>
            <option value="Webinars">Webinars</option>
            <option value="Training Sessions / Workshops">Training Sessions / Workshops</option>
            <option value="Product Launches">Product Launches</option>
            <option value="Trade Shows / Exhibitions">Trade Shows / Exhibitions</option>
            <option value="Non-profit / Fundraising Events">Non-profit / Fundraising Events</option>
            <option value="Art and Cultural Events">Art and Cultural Events</option>
            <option value="Festivals">Festivals</option>
            <option value="Fairs / Carnivals">Fairs / Carnivals</option>
            <option value="VIP Events">VIP Events</option>
            <!-- Add more options as needed -->
          </select>
        </div>
      </div>
      <button class="ui button" type="submit" style="font-family: 'Neuropol';">Create Event</button>
    </span>
  </form>
  <!-- forms ends -->
  <script src="../assets/js/semantic.js"></script>
  <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>