

<?php
session_start();
$message = "";
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $useremail = trim($_POST['w_email']);
    $password = trim($_POST['w_pwd']);

    if (!empty($useremail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT w_name, w_pwd ,h_no FROM warden WHERE w_email = ?");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($w_name, $w_pwd,$h_no);
            $stmt->fetch();

            if ($password === $w_pwd) {
                $_SESSION['h_no'] = $h_no ;
                $_SESSION['w_email'] = $useremail;
                $_SESSION['w_name'] = $w_name;
                $message = "Welcome, " . htmlspecialchars($w_name) . "!";
                header("Location: /hostel/w_dashboard.php");

            } else {
                $message = "Invalid credentials!";
            }
        } else {
            $message = "User not found!";
        }
        $stmt->close();
    } else {
        $message = "All fields are required!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="w_login.css" >

</head>
<body>

    <div class="container">

        <img src="./assets/warden.png" alt="">

        <div class="form">

            <p class="slogin">WARDEN LOGIN</p>
            <p class="line">Hey enter your details to login in to your account </p>

            <form action="w_login.php" method="POST">
                <input type="email" class="form_control" id="email" name="w_email" placeholder="Enter your Email"  required>

                <input type="password" class="form_control" id="password" name="w_pwd"  placeholder="Enter Your Password" required>

                <?php if (!empty($message)): ?>
                    <div class="message"><?= $message ?></div>
            <?php endif; ?>

                <input type="submit" class="btn" name="submit"></input>


            </form>


        </div>

    </div>
</body>
</html>