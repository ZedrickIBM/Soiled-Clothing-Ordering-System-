<?php
require "userconx.php";
session_start();

// Fetch distinct actions from the audit log
$sqlActions = "SELECT DISTINCT action FROM audit_log";
$resultActions = $conn->query($sqlActions);

$records_per_page = 30;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Fetch total number of records for customers only
$total_records_sql = "SELECT COUNT(*) 
                      FROM audit_log 
                      JOIN users ON audit_log.userID = users.userID 
                      WHERE users.userType = 2";
$total_records_result = $conn->query($total_records_sql);
$total_records_row = $total_records_result->fetch_row();
$total_records = $total_records_row[0];
$total_pages = ceil($total_records / $records_per_page);

// Fetch audit log data for customers only with limit and offset
$sqlFetch = "SELECT audit_log.auditID, audit_log.userID, audit_log.time, audit_log.action, users.fname, users.lname 
             FROM audit_log 
             JOIN users ON audit_log.userID = users.userID 
             WHERE users.userType = 2";

// Check if action filter is applied
if (isset($_GET['action']) && $_GET['action'] != '') {
    $actionFilter = $_GET['action'];
    $sqlFetch .= " AND action = '$actionFilter'";
}

$sqlFetch .= " ORDER BY audit_log.auditID DESC
               LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sqlFetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php">Dashboard</a>
    <a href="usertables.php" >Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderspage.php">Orders</a>
    <a href="auditlog.php" class="active">Audit Log</a>
    <a href="adminaccount.php">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <h2>Audit Log</h2>
    <!-- Dropdown list for filtering by action -->
    <form action="auditlog.php" method="get">
        <label for="action">Filter by Action:</label>
        <select name="action" id="action" onchange="this.form.submit()">
            <option value="">All Actions</option>
            <?php
            if ($resultActions->num_rows > 0) {
                while ($rowAction = $resultActions->fetch_assoc()) {
                    $selected = isset($_GET['action']) && $_GET['action'] == $rowAction['action'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($rowAction['action']) . "' $selected>" . htmlspecialchars($rowAction['action']) . "</option>";
                }
            }
            ?>
        </select>
    </form>
    <br>
    <table>
        <thead>
            <tr>
                <th>Audit ID</th>
                <th>User ID</th>
                <th>User Name</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['auditID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['userID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fname'] . " " . htmlspecialchars($row['lname'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['action']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No audit records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="auditlog.php?page=<?php echo $current_page - 1; ?>">Previous</a>
    <?php endif; ?>
    
    <span>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></span>

    <?php if ($current_page < $total_pages): ?>
        <a href="auditlog.php?page=<?php echo $current_page + 1; ?>">Next</a>
    <?php endif; ?>
</div>

</div>

</body>
</html>

<?php $conn->close(); ?>
