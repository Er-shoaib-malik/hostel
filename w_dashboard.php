<?php
include("connection.php");
session_start();

$warden_name = isset($_SESSION['w_name']) ? $_SESSION['w_name'] : 'Warden';


// Fetch pending outpass requests approved by parents but awaiting warden approval
$query = "SELECT o.oid, s.sname, o.reason, o.address, o.dfrom, o.dto, o.w_status, o.p_status, o.current_status
          FROM outpass o
          JOIN student s ON o.sid = s.s_id
          WHERE o.p_status = 'approved' 
            AND o.w_status = 'pending' 
            AND o.current_status NOT LIKE 'cancelled'
            AND hostel_no LIKE '%" . $_SESSION["h_no"] . "'
          ORDER BY o.dfrom DESC";
$result3 = mysqli_query($conn, $query);

// Handle form submission for approving/rejecting outpass
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oid = isset($_POST['oid']) ? mysqli_real_escape_string($conn, $_POST['oid']) : '';
    $wstatus = isset($_POST['wstatus']) ? mysqli_real_escape_string($conn, $_POST['wstatus']) : '';

    if (!empty($oid) && !empty($wstatus)) {
        $query2 = "UPDATE outpass
           SET w_status = '$wstatus',
               current_status = CASE 
                                   WHEN '$wstatus' = 'Approved' THEN 'approved by w and p'
                                   ELSE current_status
                                END
           WHERE oid = '$oid'";

        $result4 = mysqli_query($conn, $query2);
    }

    // Redirect to avoid resubmission
    header("Location: /hostel/W_dashboard.php");
}



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
<body>


    <div class="navbar" id="#navbar">
        <div class="logo" style=""> <img class="login_image" src="./assets/login_logo.png"  > Warden Dashboard</div>
            <ul class="nav-links">
                <li><a href="w_dashboard.php" >Home</a></li>
                <li><a href="w_dashboard2.php">Edit Students</a></li>
                <li><a href="student_details.php" >Students Details</a></li>
                <li><a href="#navbar" >Outpass Requests</a></li>
                <li><a href="w_dashboard3.php">Outpass History</a></li>
                <li><a href="logout.php" class="button">Logout</a></li>
                </ul>
    </div>

<?php  
$warden_name = isset($_SESSION['w_name']) ? $_SESSION['w_name'] : 'Warden';
?>
<div style="padding: 15px; font-size: 35px; font-family: Georgia, serif; font-weight: bold; color: #3E2723;">
    Welcome, <?= htmlspecialchars($warden_name) ?>!
</div>


    <div class="outpass_requests tab-content" id="outpass_requests">
    <p style="margin:10px; font-size:35px;font-family:Times New Roman ;font-weight:700;">OUTPASS REQUESTS</p>

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
                                    <th>Warden Status</th>
                                    <th>Current Status</th>
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
                                        <td><?= ucfirst($row['p_status']) ?></td>
                                        <td><?= ucfirst($row['w_status']) ?></td>
                                        <td><?= ucfirst($row['current_status']) ?></td>
                                        <td>            
                                            <form action="w_dashboard.php" method="post" class="approval_form">
                                                <input type="number" name="oid" hidden value="<?= $row['oid']?>"></input>
                                                <select  name="wstatus">
                                                <option value="approved" style="color:green ;" selected>Approve</option>
                                                <option value="rejected" style="color:red ;">Reject</option>
                                                </select>
                                                <input type="submit" class="btn" name="submit_"></input>

                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No outpass request!</p>
                    <?php endif; ?>
        </div>


    </div>




<script>

function logout() {
        // Clear sessionStorage (and optionally localStorage if used)
        sessionStorage.clear();
        localStorage.clear();

        // Redirect to PHP logout script
        window.location.href = '/hostel/logout.php';
    }
    
    document.querySelectorAll('.nav-links a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function(e) {
        e.preventDefault();
        const targetId = this.getAttribute("href").substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});

window.addEventListener("beforeunload", function () {
    sessionStorage.clear();
});
</script>



</body>
</html>