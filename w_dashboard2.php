<?php

include("connection.php");
session_start() ;
if (isset($_POST['add_student'])) {
    $new_sname = mysqli_real_escape_string($conn, $_POST['new_sname']);
    $new_semail = mysqli_real_escape_string($conn, $_POST['new_semail']);
    $new_year = mysqli_real_escape_string($conn, $_POST['new_year']);
    $new_roll_no = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $new_hostel_no = mysqli_real_escape_string($conn, $_POST['new_hostel_no']);
    $new_room_no = mysqli_real_escape_string($conn, $_POST['new_room_no']);
    $new_contact_no = mysqli_real_escape_string($conn, $_POST['new_contact_no']);
    $new_address = mysqli_real_escape_string($conn, $_POST['new_address']);
    $new_batch = mysqli_real_escape_string($conn, $_POST['new_batch']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    



        $query_add = "INSERT INTO student (sname, semail, year, roll_no, hostel_no, room_no, contact_no, address, batch, spwd) 
                      VALUES ('$new_sname', '$new_semail', $new_year, '$new_roll_no', '$new_hostel_no', '$new_room_no', '$new_contact_no', '$new_address', '$new_batch', '$new_password')";
        mysqli_query($conn, $query_add);
        header("Location: w_dashboard2.php"); 

    
}

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

if (isset($_POST['delete_student'])) {
    $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    $delete_query = "DELETE FROM student WHERE s_id = '$delete_id'";
    mysqli_query($conn, $delete_query);
    header("Location: w_dashboard2.php");
    exit();
}


if (isset($_POST['update_student'])) {
    $id = mysqli_real_escape_string($conn, $_POST['edit_s_id']);
    $name = mysqli_real_escape_string($conn, $_POST['edit_sname']);
    $email = mysqli_real_escape_string($conn, $_POST['edit_semail']);
    $year = mysqli_real_escape_string($conn, $_POST['edit_year']);
    $hostel_no = mysqli_real_escape_string($conn, $_POST['edit_hostel_no']);
    $room_no = mysqli_real_escape_string($conn, $_POST['edit_room_no']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['edit_contact_no']);

    $query = "UPDATE student 
              SET sname='$name', semail='$email', year=$year, hostel_no='$hostel_no', room_no='$room_no', contact_no='$contact_no'
              WHERE s_id=$id";
    mysqli_query($conn, $query);
    header("Location: w_dashboard2.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="w_dashboard2.css" >
    <link rel="stylesheet" href="w_dashboard.css" >

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
<div class="navbar">
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


    
    <div class="all_details1" >
        <div class="searchbox">
        <form action="w_dashboard2.php" method="post" style="display:flex;">
                <input type="text" name="kuchbhi" placeholder="Search by name"  style="width:650px; height:30px ; border-radius:10px 0 0 10px ;"></input>
                <button type="submit" class="button" style="width:50px; height:30px ;border:1px solid white; border-radius:0 10px 10px 0 ;" ><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        </div>

        <div class="table" style="width;900px; font-size:20px;">
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
                                                <td style="display:flex;">
                                                    <form method="post" action="w_dashboard2.php" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                        <input type="hidden" name="delete_id" value="<?= $row['s_id'] ?>">
                                                        <button type="submit" name="delete_student" class="btn button">Delete</button>
                                                    </form>
                                                    <button class="btn button" onclick='editStudent(<?= json_encode($row) ?>)' style="background-color:green;" onsubmit="return confirm('Are you sure you want to update this student?');">Edit</button>

                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No Student found!</p>
                            <?php endif; ?>
        </div>
    </div>


    <div class="add_student">
        <div class="addstudent">
            <h2>Add New Student</h2>
            <form action="w_dashboard2.php" method="post" class="add_student_form" >
                <input type="text" name="new_sname" placeholder="Student Name" style="width:350px; height:23px;" required >
                <input type="email" name="new_semail" placeholder="Student Email" style="width:350px; height:23px;" required>
                <input type="number" name="new_year" placeholder="Year" min="1" max="4" style="width:350px; height:23px;" required>
                <input type="text" name="roll_no" placeholder="Student Roll no" style="width:350px; height:23px;" required>
                <input type="text" name="new_hostel_no" placeholder="Hostel No." style="width:350px; height:23px;" required>
                <input type="text" name="new_room_no" placeholder="Room No." style="width:350px; height:23px;" required>
                <input type="text" name="new_contact_no" placeholder="Contact No." style="width:350px; height:23px;" required>
                <input type="text" name="new_address" placeholder="Address" required>
                <input type="text" name="new_batch" placeholder="Batch (e.g., 24A12)" style="width:350px; height:23px;" required>
                <input type="password" name="new_password" placeholder="Password" style="width:350px; height:23px;" required>
                <button type="submit" name="add_student" class="btn button" style="position:static; width:350px; height:23px;">Add Student</button>
            </form>
        </div>
</div>
<div id="editFormDiv" style="display:none; border:1px solid #ccc; padding:20px; margin-top:20px; position:fixed ; top:100px;">
    <h2>Edit Student</h2>
    <form action="w_dashboard2.php" method="post" id="editFormDivform" onsubmit="return confirm('Are you sure you want to update this student?');">
        <input type="hidden" name="edit_s_id" id="edit_s_id">
        <input type="text" name="edit_sname" id="edit_sname" placeholder="Student Name" required>
        <input type="email" name="edit_semail" id="edit_semail" placeholder="Student Email" required>
        <input type="number" name="edit_year" id="edit_year" placeholder="Year" min="1" max="4" required>
        <input type="text" name="edit_hostel_no" id="edit_hostel_no" placeholder="Hostel No." required>
        <input type="text" name="edit_room_no" id="edit_room_no" placeholder="Room No." required>
        <input type="text" name="edit_contact_no" id="edit_contact_no" placeholder="Contact No." required>
        <button type="submit" name="update_student" class="btn button">Update</button>
        <button type="button" class="btn button" onclick="hideEditForm()">Cancel</button>
    </form>
</div>

<script>
function logout() {
        sessionStorage.clear();
        alert('Logged Out');
        window.location.href = '/hostel/';
    }

function back() {
    window.location.href = '/hostel/w_dashboard2.php';
}

function editStudent(data) {
    document.getElementById('edit_s_id').value = data.s_id;
    document.getElementById('edit_sname').value = data.sname;
    document.getElementById('edit_semail').value = data.semail;
    document.getElementById('edit_year').value = data.year;
    document.getElementById('edit_hostel_no').value = data.hostel_no;
    document.getElementById('edit_room_no').value = data.room_no;
    document.getElementById('edit_contact_no').value = data.contact_no;
    document.getElementById('editFormDiv').style.display = 'block';
}

function hideEditForm() {
    document.getElementById('editFormDiv').style.display = 'none';
}
</script>


</body>
</html>