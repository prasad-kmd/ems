<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Sign In</h1>
    </header>
    <main>
        <div class="form-container">
            <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'SignIn')">Sign In</button>
                <button class="tablinks" onclick="openTab(event, 'SignUp')">Sign Up</button>
            </div>
            <div id="SignIn" class="tabcontent">
                <form action="login_process.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Sign In</button>
                </form>
            </div>
            <div id="SignUp" class="tabcontent">
                <form action="signup_process.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </main>
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("defaultOpen").click();
    </script>
</body>
</html>