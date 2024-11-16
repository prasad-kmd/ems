<!DOCTYPE html>
<html>
<head>
    <title>Sign In/Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="tabs">
            <button class="tab active" data-target="login">Sign In</button>
            <button class="tab" data-target="register">Sign Up</button>
        </div>
        <div class="tab-content">
            <div id="login" class="tab-pane active">
                <form action="login.php" method="post">
                    <label for="login_email">Email:</label>
                    <input type="email" id="login_email" name="login_email" required><br><br>
                    <label for="login_password">Password:</label>
                    <input type="password" id="login_password" name="login_password" required><br><br>
                    <input type="submit" value="Sign In">
                </form>
            </div>
            <div id="register" class="tab-pane">
                <form action="register.php" method="post" enctype="multipart/form-data">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address"></textarea><br><br>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required><br><br>
                    <label for="photo">Profile Photo:</label>
                    <input type="file" id="photo" name="photo"><br><br>
                    <input type="submit" value="Sign Up">
                </form>
            </div>
        </div>
    </div>
    <script>
        const tabs = document.querySelectorAll('.tab');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' class from all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Add 'active' class to the clicked tab and corresponding pane
                tab.classList.add('active');
                const targetPane = document.getElementById(tab.dataset.target);
                targetPane.classList.add('active');
            });
        });
    </script>
</body>

</html>