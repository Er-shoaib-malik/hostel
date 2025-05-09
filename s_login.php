

<?php
session_start();
$message = "";
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $useremail = trim($_POST['semail']);
    $password = trim($_POST['spwd']);

    if (!empty($useremail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT sname, spwd , s_id , hostel_no ,room_no FROM student WHERE semail = ?");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($sname, $spwd,$sid,$hostel_no,$room_no);
            $stmt->fetch();

            if ($password === $spwd) {
                $_SESSION['semail'] = $useremail;
                $_SESSION['sname'] = $sname;
                $_SESSION['s_id'] = $sid ;
                $_SESSION['hostel_no'] = $hostel_no;
                $_SESSION['room_no'] = $room_no ;
                header("Location: /hostel/s_dashboard.php");

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
    <link rel="stylesheet" href="s_login.css" >

</head>
<body>

    <div class="container">

        <img src="./assets/student.png" alt="">

        <div class="form">

            <p class="slogin">STUDENT LOGIN</p>
            <p class="line">Hey enter your details to login in to your account </p>

            <form action="s_login.php" method="POST">
                <input type="email" class="form_control" id="email" name="semail" placeholder="Enter your Email"  required>

                <input type="password" class="form_control" id="password" name="spwd"  placeholder="Enter Your Password" required>

                <?php if (!empty($message)): ?>
                    <div class="message"><?= $message ?></div>
            <?php endif; ?>

                <input type="submit" class="btn" name="submit"></input>
            </form>


        </div>

    </div>
</body>
</html>