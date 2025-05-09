<?php
// Include the database connection file
include("connection.php");

// Start the session to access session variables
session_start();

if (isset($_POST['submit'])) {
    // Sanitize and fetch POST inputs
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $from = mysqli_real_escape_string($conn, $_POST['from']);
    $to = mysqli_real_escape_string($conn, $_POST['to']);

    // Convert 'from' date to timestamp and get today's date timestamp
    $fromDate = strtotime($_POST['from']);
    $today = strtotime(date('Y-m-d'));

    // Prepare query to fetch last approved outpass dates (not cancelled)
    $stmt = $conn->prepare("SELECT dfrom, dto
                            FROM outpass 
                            WHERE sid = ? AND current_status NOT LIKE 'cancelled'
                            ORDER BY dfrom DESC 
                            LIMIT 1");
    $stmt->bind_param("i", $_SESSION["s_id"]);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($dform, $dto);
        $stmt->fetch();

        // Store previously fetched dates in session for comparison
        $_SESSION["dfrom"] = strtotime($dform);
        $_SESSION["dto"] = strtotime($dto);
    }

    // Check if selected date is valid (in future and not overlapping with previous outpass)
    if ($fromDate >= $today && ($fromDate < $_SESSION["dfrom"] || $fromDate > $_SESSION["dto"])) {
        // Insert new outpass request
        $sql = "INSERT INTO outpass (sid, semail, reason, address, dfrom, dto, hno, rno)
                VALUES ('" . $_SESSION["s_id"] . "', '" . $_SESSION["semail"] . "', '$reason', '$address', '$from', '$to', '" . $_SESSION["hostel_no"] . "', '" . $_SESSION["room_no"] . "')";
        $result = mysqli_query($conn, $sql);

        // Show success message and redirect
        echo "
        <script>
        alert('Outpass request successful!');
        window.location.href='/hostel/s_dashboard.php';
        </script>
        ";
    } else {
        // Invalid date error message and redirect
        echo "
        <script>
        alert('Enter a valid date!');
        window.location.href='/hostel/s_dashboard.php';
        </script>
        ";
    }
}

// If student is logged in, fetch latest and all outpass requests
if (isset($_SESSION['s_id'])) {
    $sid = $_SESSION['s_id'];

    // Query to fetch latest valid (non-cancelled) outpass request
    $query = "SELECT oid, reason, address, dfrom, dto, w_status, p_status, current_status
              FROM outpass 
              WHERE sid = '$sid' AND current_status NOT LIKE 'cancelled'
              ORDER BY dfrom DESC 
              LIMIT 1";

    // Query to fetch all outpass requests of the student
    $query2 = "SELECT oid, reason, address, dfrom, dto, w_status, p_status, current_status
               FROM outpass 
               WHERE sid = '$sid'
               ORDER BY dfrom";

    $result = mysqli_query($conn, $query);
    $result1 = mysqli_query($conn, $query2);
}

// Cancel an existing outpass request if requested
if (isset($_POST['cancel_request']) && isset($_POST['cancel_id'])) {
    $cancel_id = intval($_POST['cancel_id']);
    $sid = $_SESSION['s_id'];

    // Update query to mark the request as 'cancelled'
    $sql_cancel = "UPDATE outpass 
    SET current_status = 'cancelled' 
    WHERE oid = $cancel_id 
    AND current_status IN ('applied','approved by p')";

    $status_of_cancel = mysqli_query($conn, $sql_cancel);

    // Feedback to user depending on success
    if ($status_of_cancel) {
        echo "
        <script>
        alert('Outpass request cancelled.');
        window.location.href='/hostel/s_dashboard.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Unable to cancel the outpass.');
        window.location.href='/hostel/s_dashboard.php';
        </script>
        ";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="s_dashboard.css" >
</head>
<body>

<div class="navbar">
    <div class="logo" style=""> <img class="login_image" src="./assets/login_logo.png"  > Student Dashboard</div>
    <ul class="nav-links">
        <li><a href="s_dashboard.php" >Home</a></li>
        <li><a class="apply_out" >Request Outpass</a></li>
        <li><a class="check_status" >Check Status</a></li>
        <li><a class="show_history"  >History</a></li>
        <li><button onclick="logout()" class="button">Logout</button></li>
    </ul>
</div>

    <div class="welcome">
        WELCOME ,
        <?= $_SESSION["sname"]?>
    </div>


    <div class="dialog_box">
        <div class="request_click"  style="display: none;">
        <p style="font-size:35px; color:white;">REQUEST OUTPASS</p>

            <form action="s_dashboard.php" method="post" class="request">

                <input type="text" name="reason" placeholder="REASON" required id="reason"><br>
                <input type="text" name="address" placeholder="ADDRESS" required id="address"><br>
                <input type="date" name="from" placeholder="FROM" required id="from">
                <input type="date" name="to" placeholder="TO" required id="to">
            <?php if (!empty($message1)): ?>
                    <div class="message"><?= $message1 ?></div>
            <?php endif; ?>
                <input type="submit" name="submit" placeholder="SUBMIT" id="submit">


            </form>
        </div>

        <div class="status_click" >
            <p style="font-size:35px; color:white;">OUTPASS STATUS</p>
            <div class="status">

                <?php if ($result && mysqli_num_rows($result) > 0): $row = mysqli_fetch_assoc($result); ?>
                    
                    
                    <table>
                    <tr>
                        <td>Outpass ID :</td>
                        <td><?= $row['oid'] ?> </td>
                    </tr>
                    <tr>
                        <td>Reason : </td>
                        <td><?=$row['reason'] ?>
                    </tr>
                    <tr>
                        <td>Address :</td>
                        <td><?=$row['address']?></td>
                    </tr>
                    <tr>
                        <td>Date From :</td>
                        <td><?=$row['dfrom']?></td>
                    </tr>                <tr>
                        <td>Date To :</td>
                        <td><?=$row['dto']?></td>
                    </tr>                <tr>
                        <td>Parents status :</td>
                        <td><?=$row['p_status']?></td>
                    </tr>                
                    <tr>
                        <td>Warden status :</td>
                        <td><?=$row['w_status']?></td>
                    </tr>
                    <tr>
                        <td>Current Status:</td>
                        <td><?= ucfirst($row['current_status']) ?></td>
                    </tr>


    
                </table>






                <?php else: ?>
                        <p>No outpass requests found!</p>
                <?php endif; ?>
                <div style=" text-align: center;">
                                    
                                        <form action="s_dashboard.php" method="post" class="cancel_form" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                            <input type="hidden" name="cancel_id" value="<?= $row['oid'] ?>">
                                            <input type="submit" name="cancel_request" value="Cancel" class="cancel-btn">
                                        </form>
                </div>



            </div>
            

        </div>

        <div class="history_click"  style="display: none;">
        <p style="font-size:35px; color:white;">OUTPASS HISTORY</p>
            <div class="history" >
            <?php if ($result1 && mysqli_num_rows($result1) > 0): ?>
                    
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
                            <?php while ($row = mysqli_fetch_assoc($result1)): ?>
                                <tr>
                                    <td><?= $row['oid'] ?></td>
                                    <td><?= $row['reason'] ?></td>
                                    <td><?= $row['address'] ?></td>
                                    <td><?= $row['dfrom'] ?></td>
                                    <td><?= $row['dto'] ?></td>
                                    <td><?= ucfirst($row['p_status']) ?></td>
                                    <td><?= ucfirst($row['w_status']) ?></td>
                                    <td><?= ucfirst($row['current_status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No outpass history found!</p>
                <?php endif; ?>
            </div>

        </div>


    </div>

    <script src="s_dash.js" ></script>
</body>
</html>