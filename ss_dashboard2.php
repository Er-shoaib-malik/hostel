<?php
include("connection.php");
session_start() ;
$ss_id = $_SESSION['ss_id']; // Get security staff ID from session

$query = "SELECT 
             o.oid, 
             o.reason, 
             o.address, 
             o.dfrom, 
             o.dto, 
             o.w_status, 
             o.p_status, 
             o.sid,
             s.sname
          FROM outpass o
          JOIN student s ON o.sid = s.s_id
          WHERE o.current_status = 'approved by p ,w ,ss' AND o.ss_id = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ss_id);
$stmt->execute();
$result3 = $stmt->get_result();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ss_dashboard.css" >
</head>
<body>

<div class="navbar" id="#navbar">
        <div class="logo" style=""> <img class="login_image" src="./assets/login_logo.png"  > Security Dashboard</div>
            <ul class="nav-links">
                <li><a href="ss_dashboard.php" >Home</a></li>
                <li><a href="ss_dashboard.php" >Checked Out Outpasses</a></li>
                <li><button onclick="logout()" class="button">Logout</button></li>
            </ul>
</div>

<?php  
$security_name = isset($_SESSION['ss_name']) ? $_SESSION['ss_name'] : 'security';
?>
<div style="padding: 15px; font-size: 35px; font-family: Georgia, serif; font-weight: bold; color: #3E2723; text-align:center;">
    Welcome, <?= htmlspecialchars($security_name) ?>!
</div>

<div class="box">
    <p style="margin:10px; font-size:35px;font-family:Times New Roman ;font-weight:700; color:white;">CHECKED OUT OUTPASSES</p>
    <div class="outpass_list">
            <?php if ($result3 && mysqli_num_rows($result3) > 0): ?>
                            
                            <table>
                                <thead>
                                    <tr>
                                        <th>Outpass ID</th>
                                        <th>Name</th>
                                        <th>Reason</th>
                                        <th>Address</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Parents Status</th>
                                        <th>warden Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                                        <tr>
                                            <td><?= $row['oid'] ?></td>
                                            <td><?= $row['sname'] ?></td>
                                            <td><?= $row['reason'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td><?= $row['dfrom'] ?></td>
                                            <td><?= $row['dto'] ?></td>
                                            <td style="color:lightgreen;"><?= ucfirst($row['p_status']) ?></td>
                                            <td style="color:lightgreen;"><?= ucfirst($row['w_status']) ?></td>

                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No outpass history found!</p>
                        <?php endif; ?>
    </div>

</div>

    <script>function logout() {
        sessionStorage.clear();
        alert('Logged Out');
        window.location.href = '/hostel/';
    }</script>
</body>
</html>