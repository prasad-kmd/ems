<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 400px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .tab-buttons {
            display: flex;
            border-bottom: 2px solid #ddd;
        }
        .tab-buttons button {
            flex: 1;
            padding: 10px 20px;
            border: none;
            background: #f4f4f9;
            cursor: pointer;
            font-size: 16px;
        }
        .tab-buttons button.active {
            background: #ffffff;
            font-weight: bold;
            border-bottom: 3px solid #007BFF;
        }
        .form-content {
            padding: 20px;
            display: none;
        }
        .form-content.active {
            display: block;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tab-buttons">
            <button class="tab-link active" onclick="switchTab('signin')">Sign In</button>
            <button class="tab-link" onclick="switchTab('signup')">Sign Up</button>
        </div>
        <div id="signin" class="form-content active">
            <form action="signin.php" method="POST">
                <h2>Sign In</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div id="signup" class="form-content">
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="text" name="address" placeholder="Address">
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="file" name="photo" accept=".jpg, .jpeg, .png, .gif, .webp">
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.form-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab-link').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
