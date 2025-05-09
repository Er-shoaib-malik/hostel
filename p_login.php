

<?php
session_start();
$message = "";
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $useremail = trim($_POST['p_email']);
    $password = trim($_POST['p_pwd']);

    if (!empty($useremail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT p_name, p_pwd, sid FROM parents WHERE p_email = ?");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($p_name, $p_pwd, $sid);
            $stmt->fetch();

            if ($password === $p_pwd) {
                $_SESSION['p_email'] = $useremail;
                $_SESSION['p_name'] = $p_name;
                $_SESSION['sid'] = $sid ;
                $message = "Welcome, " . htmlspecialchars($p_name) . "!";
                header("Location: /hostel/p_dashboard.php");

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
    <link rel="stylesheet" href="p_login.css" >

</head>
<body>

    <div class="container">

        <img src="./assets/parent.png" alt="">

        <div class="form">

            <p class="slogin">PARENT LOGIN</p>
            <p class="line">Hey enter your details to login in to your account </p>

            <form action="p_login.php" method="POST">
                <input type="email" class="form_control" id="email" name="p_email" placeholder="Enter your Email"  required>

                <input type="password" class="form_control" id="password" name="p_pwd"  placeholder="Enter Your Password" required>

                <?php if (!empty($message)): ?>
                    <div class="message"><?= $message ?></div>
            <?php endif; ?>

                <input type="submit" class="btn" name="submit"></input>


            </form>


        </div>

    </div>
</body>
</html>