<?php
include("connection.php");
session_start() ;

$parent_name = isset($_SESSION['p_name']) ? $_SESSION['p_name'] : 'Parent';


$query3 = "SELECT oid, reason, address, dfrom, dto, w_status, p_status ,sid ,current_status
           FROM outpass 
           WHERE p_status = 'pending' AND w_status = 'pending' AND current_status = 'Applied' AND sid = " . $_SESSION['sid'] . ";";


$result3 = mysqli_query($conn, $query3);


$query6 = "SELECT oid, reason, address, dfrom, dto, w_status, p_status ,sid ,current_status
           FROM outpass 
           WHERE sid = " . $_SESSION["sid"] . ";";
$result6 = mysqli_query($conn,$query6) ;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: /hostel/p_dashboard.php");
    $oid = isset($_POST['oid']) ? mysqli_real_escape_string($conn, $_POST['oid']) : '';
    $pstatus = isset($_POST['pstatus']) ? mysqli_real_escape_string($conn, $_POST['pstatus']) : '';


    $query2 = "UPDATE outpass
    SET p_status = '$pstatus',
        current_status = CASE 
                            WHEN '$pstatus' = 'Approved' THEN 'approved by p'
                            ELSE current_status
                         END
    WHERE oid = '$oid'";

        $result4 = mysqli_query($conn, $query2);
    
}

$filter1 = isset($_GET['filter1']) ? mysqli_real_escape_string($conn, $_GET['filter1']) : 'All outpass';

if ($filter1 == 'All outpass') {
    $query10 = "SELECT o.oid, s.sname, o.reason, o.address, o.dfrom, o.dto, o.p_status, o.w_status, o.current_status
               FROM outpass o
               JOIN student s ON o.sid = s.s_id
               where o.sid = " . $_SESSION["sid"] . "
               ORDER BY o.dfrom DESC";
} else {
    $query10 = "SELECT o.oid, s.sname, o.reason, o.address, o.dfrom, o.dto, o.p_status, o.w_status, o.current_status
               FROM outpass o
               JOIN student s ON o.sid = s.s_id
               WHERE o.current_status = '$filter1' AND o.sid = " . $_SESSION["sid"] . "
               ORDER BY o.dfrom DESC";
}

$result10 = mysqli_query($conn, $query10);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="p_dashboard.css">
</head>
<body>


<div class="navbar" id="#navbar">
        <div class="logo" style=""> <img class="login_image" src="./assets/login_logo.png"  >Parents Dashboard</div>
            <ul class="nav-links">
                <li><a href="p_dashboard.php" >Home</a></li>
                <!-- <li><a class="check_status">Outpass Requests</a></li>
                <li><a class="show_history">All Outpass</a></li> -->
                <li><a class="check_status" >All Outpass</a></li>
                <li><a class="apply_out"  >Outpass Requests</a></li>

                <li><button onclick="logout()" class="button">Logout</button></li>
            </ul>
</div>

<?php  
$parent_name = isset($_SESSION['p_name']) ? $_SESSION['p_name'] : 'Parent';
?>
<div style="padding: 15px; font-size: 35px; font-family: Georgia, serif; font-weight: bold; color: #3E2723; text-align:center;">
    Welcome, <?= htmlspecialchars($parent_name) ?>!
</div>

    <div class="BOX">



   <div class="div1 request_click" style="display:none;">
   <p style="font-size:35px; color:white;">OUTPASS REQUESTS</p>

    <div class="outpass_list ">
    <?php if (mysqli_num_rows($result3) > 0): ?>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Outpass ID</th>
                                    <th>Reason</th>
                                    <th>Address</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Parents Status</th>
                                    <th>Warden Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                                    <tr>
                                        <td><?= $row['oid'] ?></td>
                                        <td><?= $row['reason'] ?></td>
                                        <td><?= $row['address'] ?></td>
                                        <td><?= $row['dfrom'] ?></td>
                                        <td><?= $row['dto'] ?></td>
                                        <td><?= ucfirst($row['p_status']) ?></td>
                                        <td><?= ucfirst($row['w_status']) ?></td>
                                        <td>            
                                            <form action="p_dashboard.php" method="post" class="approval_form">
                                                <input type="number" name="oid" hidden value="<?= $row['oid']?>"></input>
                                                <select  name="pstatus" default="Approved">
                                                <option value="Approved" style="color:green ;">Approve</option>
                                                <option value="rejected" style="color:red ;">Reject</option>
                                                </select>
                                                <input type="submit" class="btn" name="submit"></input>

                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No outpass history found!</p>
                    <?php endif; ?>
    </div>




    </div>


    <div  class="status_click" >
        <p style="font-size:35px; color:white;">OUTPASS LIST ALL</p>

    <div class="outpass_list_All">

        <div class="sorting-bar">
                <form method="GET" action="p_dashboard.php" style="display: flex; align-items: center; gap: 10px;">
                    <label for="filter1" style="color: #5D4037; font-weight: bold;">View:</label>
                    <select name="filter1" id="filter1" onchange="this.form.submit()" style="padding: 6px; border-radius: 5px;">
                        <option value="All outpass" <?= (isset($_GET['filter1']) && $_GET['filter1'] == 'All outpass') ? 'selected' : '' ?>>All outpass</option>
                        <option value="Applied" <?= (isset($_GET['filter1']) && $_GET['filter1'] == 'Applied') ? 'selected' : '' ?>>Applied</option>
                        <option value="approved by p" <?= (isset($_GET['filter1']) && $_GET['filter1'] == 'approved by p') ? 'selected' : '' ?>>approved by p</option>
                        <option value="approved by w and p" <?= (isset($_GET['filter1']) && $_GET['filter1'] == 'approved by w and p') ? 'selected' : '' ?>>approved by w and p</option>
                    </select>
                </form>

        </div>
            <?php if (mysqli_num_rows($result10) > 0): ?>
                            
                            <table>
                                <thead>
                                    <tr>
                                        <th>Outpass ID</th>
                                        <th>Reason</th>
                                        <th>Address</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Parents Status</th>
                                        <th>Warden Status</th>
                                        <th>Current Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result10)): ?>
                                        <tr>
                                            <td><?= $row['oid'] ?></td>
                                            <td><?= $row['reason'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td><?= $row['dfrom'] ?></td>
                                            <td><?= $row['dto'] ?></td>
                                            <td ><?= ucfirst($row['p_status']) ?></td>
                                            <td ><?= ucfirst($row['w_status']) ?></td>
                                            <td><?= $row['current_status'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No outpass history found!</p>
                        <?php endif; ?>
    </div>

</div>


    <script src="s_dash.js">function logout() {
        sessionStorage.clear();
        alert('Logged Out');
        window.location.href = '/hostel/';
    }</script>

</body>
</html>