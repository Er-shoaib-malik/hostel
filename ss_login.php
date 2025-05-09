

<?php
session_start();
$message = "";
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $useremail = trim($_POST['ss_email']);
    $password = trim($_POST['ss_pwd']);

    if (!empty($useremail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT ss_name, ss_pwd, ss_id FROM security WHERE ss_email = ?");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($ss_name, $ss_pwd, $ss_id);
            $stmt->fetch();

            if ($password === $ss_pwd) {
                $_SESSION['ss_email'] = $useremail;
                $_SESSION['ss_name'] = $ss_name;
                $_SESSION['ss_id'] = $ss_id;
                $message = "Welcome, " . htmlspecialchars($ss_name) . "!";
                header("Location: /hostel/ss_dashboard.php");

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
    <link rel="stylesheet" href="ss_login.css" >

</head>
<body>

    <div class="container">

        <img src="./assets/security.png" alt="">

        <div class="form">

            <p class="slogin">SECURITY LOGIN</p>
            <p class="line">Hey enter your details to login in to your account </p>

            <form action="ss_login.php" method="POST">
                <input type="email" class="form_control" id="email" name="ss_email" placeholder="Enter your Email"  required>

                <input type="password" class="form_control" id="password" name="ss_pwd"  placeholder="Enter Your Password" required>

                <?php if (!empty($message)): ?>
                    <div class="message"><?= $message ?></div>
            <?php endif; ?>

                <input type="submit" class="btn" name="submit"></input>


            </form>


        </div>

    </div>
</body>
</html>