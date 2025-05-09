<?php

include("connection.php");
session_start() ;
if (isset($_POST['kuchbhi'])) {

$search = mysqli_real_escape_string($conn, $_POST["kuchbhi"]);

// Search by student name
$query3 = "SELECT s_id, sname, semail, year, hostel_no, room_no, contact_no
           FROM student
           WHERE sname LIKE '%$search%' AND hostel_no LIKE '%" . $_SESSION["h_no"] . "'
           ORDER BY s_id";
} else {
// Show students from the same hostel as the warden
$query3 = "SELECT s_id, sname, semail, year, hostel_no, room_no, contact_no
           FROM student 
           WHERE hostel_no LIKE '%" . $_SESSION["h_no"] . "'
           ORDER BY s_id";
}

$result5 = mysqli_query($conn, $query3);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="w_dashboard.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body style="display: flex; justify-content:center; align-items:center; margin-top:30px;">

<div class="navbar" >
    <div class="logo"> <img class="login_image" src="./assets/login_logo.png" > Warden Dashboard</div>
    <ul class="nav-links">
        <li>
            <a href="w_dashboard.php">Home</a></li>
        <li><a href="w_dashboard2.php">Edit Students</a></li>
        <li><a href="student_details.php" >Students Details</a></li>
        <li><a href="w_dashboard.php" >Outpass Requests</a></li>
        <li><a href="w_dashboard.php">Outpass History</a></li>

        <li><button onclick="logout()" class="button">Logout</button></li>
    </ul>
</div>
    
    <div class="all_details" style="top:50%; left:50% ;">
        <div class="searchbox">
        <form action="student_details.php" method="post" style="display:flex;">
                <input type="text" name="kuchbhi" placeholder="Search by name"  style="width:650px; height:30px ; border-radius:10px 0 0 10px ;"></input>
                <button type="submit" class="button" style="width:50px; height:30px ;border:1px solid white; border-radius:0 10px 10px 0 ; position: static;" ><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        </div>

        <div class="table" style="    position: absolute ;top:200px ;width;900px; font-size:20px;">
            <?php if ($result5 && mysqli_num_rows($result5) > 0): ?>
                                
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Year</th>
                                            <th>Hostel no.</th>
                                            <th>Room no.</th>
                                            <th>Contact no.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result5)): ?>
                                            
                                            <tr>
                                                <td><?= $row['s_id'] ?></td>
                                                <td><?= $row['sname'] ?></td>
                                                <td><?= $row['semail'] ?></td>
                                                <td><?= $row['year'] ?></td>
                                                <td><?= $row['hostel_no'] ?></td>
                                                <td><?= $row['room_no'] ?></td>
                                                <td><?= $row['contact_no'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No Student found!</p>
                            <?php endif; ?>
        </div>
    </div>
</body>
</html>