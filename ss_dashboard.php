<?php
include("connection.php");
session_start() ;
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
          WHERE o.current_status = 'approved by w and p'";



$result3 = mysqli_query($conn, $query);
if (isset($_POST['approve_security']) && isset($_POST['approve_id'])) {
    $oid = intval($_POST['approve_id']);

    // Get ss_id from session
    $ss_id = $_SESSION['ss_id']; // Assuming this is set at login

    // Use placeholders for both variables
    $update_query = "UPDATE outpass 
                     SET current_status = 'approved by p ,w ,ss', ss_id = ?
                     WHERE oid = ?";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $ss_id, $oid); // Bind two integers: ss_id and oid

    if ($stmt->execute()) {
        echo "<script>alert('Outpass marked as approved.'); window.location.href='ss_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to approve outpass.');</script>";
    }
}



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
                <li><a href="ss_dashboard2.php" >Checked Out Outpasses</a></li>
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
    <p style="margin:10px; font-size:35px;font-family:Times New Roman ;font-weight:700; color:white;">APPROVED OUTPASS</p>
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
                                        <th>Action</th>

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
                                            <td>
                                                    <form method="post" action="ss_dashboard.php" onsubmit="return confirm('Approve this outpass?');">
                                                        <input type="hidden" name="approve_id" value="<?= $row['oid'] ?>">
                                                        <input type="submit" name="approve_security" value="Check out" class="approve-btn">
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

    <script>function logout() {
        sessionStorage.clear();
        alert('Logged Out');
        window.location.href = '/hostel/';
    }</script>
</body>
</html>