<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
require('userconx.php'); // Assuming this file contains your database connection

// Query to retrieve data from the "orders" table
$sql = "SELECT o.orderID, o.userID, o.cartID, o.contactNo, o.order_deliveryProvince, o.order_deliveryCity, o.order_deliverBrgy, o.order_MOP, o.order_deliveryStatus, o.orderDate,
        u.fname, u.lname,
        c.prodName, c.prodPrice, c.quantity, c.totalAmount
        FROM orders o
        INNER JOIN users u ON o.userID = u.userID
        INNER JOIN cart c ON o.cartID = c.cartID";

$result = $conn->query($sql);

// Initialize TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Orders Report');
$pdf->SetSubject('Orders Report');
$pdf->SetKeywords('TCPDF, PDF, report');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Include Bootstrap CSS
$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';

// Header
$html .= '<h1 class="text-center mb-4">Orders Report</h1>';
$html .= '<p class="text-center">Report generated on ' . date('Y-m-d') . '</p>';

// Table
$html .= '<table class="table table-bordered">';
$html .= '<thead class="thead-dark">';
$html .= '<tr><th>Order ID</th><th>User Name</th><th>Cart ID</th><th>Contact No</th><th>Delivery Province</th><th>Delivery City</th><th>Delivery Barangay</th><th>Mode of Payment</th><th>Delivery Status</th><th>Order Date</th><th>Product Name</th><th>Product Price</th><th>Quantity</th><th>Total Amount</th></tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Loop through the results
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . $row["orderID"] . '</td>';
    $html .= '<td>' . $row["fname"] . " " . $row["lname"] .'</td>';
    $html .= '<td>' . $row["cartID"] . '</td>';
    $html .= '<td>' . $row["contactNo"] . '</td>';
    $html .= '<td>' . $row["order_deliveryProvince"] . '</td>';
    $html .= '<td>' . $row["order_deliveryCity"] . '</td>';
    $html .= '<td>' . $row["order_deliverBrgy"] . '</td>';
    $html .= '<td>' . $row["order_MOP"] . '</td>';
    $html .= '<td>' . $row["order_deliveryStatus"] . '</td>';
    $html .= '<td>' . $row["orderDate"] . '</td>';
    $html .= '<td>' . $row["prodName"] . '</td>';
    $html .= '<td>' . $row["prodPrice"] . '</td>';
    $html .= '<td>' . $row["quantity"] . '</td>';
    $html .= '<td>' . $row["totalAmount"] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('orders_report.pdf', 'D');

?>
