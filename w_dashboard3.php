<?php
include("connection.php");
session_start();

$warden_name = isset($_SESSION['w_name']) ? $_SESSION['w_name'] : 'Warden';






// Fetch full outpass history

$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'All outpass';

if ($filter == 'All outpass') {
    $query8 = "SELECT o.oid, s.sname, o.reason, o.address, o.dfrom, o.dto, o.p_status, o.w_status, o.current_status
               FROM outpass o
               JOIN student s ON o.sid = s.s_id
               where s.hostel_no LIKE '%" . $_SESSION["h_no"] . "'
               ORDER BY o.dfrom DESC";
} else {
    $query8 = "SELECT o.oid, s.sname, o.reason, o.address, o.dfrom, o.dto, o.p_status, o.w_status, o.current_status
               FROM outpass o
               JOIN student s ON o.sid = s.s_id
               WHERE o.current_status = '$filter' AND s.hostel_no LIKE '%" . $_SESSION["h_no"] . "'
               ORDER BY o.dfrom DESC";
}

$result8 = mysqli_query($conn, $query8);



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
                <li><a href="w_dashboard.php" >Outpass Requests</a></li>
                <li><a href="#outpass_list_All">Outpass History</a></li>
                <li><a href="logout.php" class="button">Logout</a></li>
                </ul>
    </div>

<?php  
$warden_name = isset($_SESSION['w_name']) ? $_SESSION['w_name'] : 'Warden';
?>
<div style="padding: 15px; font-size: 35px; font-family: Georgia, serif; font-weight: bold; color: #3E2723;">
    Welcome, <?= htmlspecialchars($warden_name) ?>!
</div>





<div class="outpass_list_All" id="outpass_list_All">
<p style="margin:10px; font-size:35px;font-family:Times New Roman ;font-weight:700;"> ALL OUTPASS DETAILS</p>

    <div class="outpass_list">
    <div class="sorting-bar">
    <form method="GET" action="w_dashboard3.php" style="display: flex; align-items: center; gap: 10px;">
        <label for="filter" style="color: #5D4037; font-weight: bold;">View:</label>
        <select name="filter" id="filter" onchange="this.form.submit()" style="padding: 6px; border-radius: 5px;">
            <option value="All outpass" <?= (isset($_GET['filter']) && $_GET['filter'] == 'All outpass') ? 'selected' : '' ?>>All outpass</option>
            <option value="Applied" <?= (isset($_GET['filter']) && $_GET['filter'] == 'Applied') ? 'selected' : '' ?>>Applied</option>
            <option value="approved by p" <?= (isset($_GET['filter']) && $_GET['filter'] == 'approved by p') ? 'selected' : '' ?>>approved by p</option>
            <option value="approved by w and p" <?= (isset($_GET['filter']) && $_GET['filter'] == 'approved by w and p') ? 'selected' : '' ?>>approved by w and p</option>
            <option value="approved by p, w, ss" <?= (isset($_GET['filter']) && $_GET['filter'] == 'approved by p, w, ss') ? 'selected' : '' ?>>approved by p, w, ss</option>
            <option value="cancelled" <?= (isset($_GET['filter']) && $_GET['filter'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
        </select>
    </form>
</div>




            <?php if ($result8 && mysqli_num_rows($result8) > 0): ?>
                            
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
                                    <?php while ($row = mysqli_fetch_assoc($result8)): ?>
                                        <tr>
                                            <td><?= $row['oid'] ?></td>
                                            <td><?= $row['sname'] ?></td>
                                            <td><?= $row['reason'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td><?= $row['dfrom'] ?></td>
                                            <td><?= $row['dto'] ?></td>
                                            <td style="color:lightgreen;"><?= ucfirst($row['p_status']) ?></td>
                                            <td style="color:lightgreen;"><?= ucfirst($row['w_status']) ?></td>
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