<?php 

include("connection.php");
session_start();


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
</head>
<body>



<div class="all_details">

<div class="searchbox">
<form action="try.php" method="post" style="display:flex;">
        <input type="text" name="kuchbhi" placeholder="Search by name"  style="width:650px; height:30px ; border-radius:10px 0 0 10px ;"></input>
        <button type="submit" style="width:50px; height:30px ;border:1px solid white; border-radius:0 10px 10px 0 ; position: static;" ><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
</div>

<div class="table">
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