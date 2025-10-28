
<!--  -->
<?php
// Use Dompdf if available via Composer; otherwise include a bundled autoloader path
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once(__DIR__ . '/../vendor/autoload.php');
} elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once(__DIR__ . '/vendor/autoload.php');
} elseif (file_exists(__DIR__ . '/dompdf/autoload.inc.php')) {
    require_once(__DIR__ . '/dompdf/autoload.inc.php');
} else {
    die('PDF library not found. Please install Dompdf via Composer or place dompdf in Client/dompdf.');
}

use Dompdf\Dompdf;

// Send pdf to email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';


// Create a new Dompdf instance
$dompdf = new Dompdf();

// Retrieve the JSON data from the AJAX request and decode it
$data = json_decode($_POST['jsonData'], true);
$orderItem = $data['OrderItems'];
$invoiceNo = 'CC-' . date('ymdHis');

 
// HTML content for the PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        /* Thermal receipt style, print friendly */
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111; font-size: 11px; margin: 0; }
        .receipt { width: 80mm; margin: 0 auto; padding: 14px 14px 18px; }
        .header { text-align: center; margin-bottom: 8px; }
        .brand { font-size: 30px; font-weight: 900; color: #FEA116; letter-spacing: .6px; }
        .muted { color: #444; font-size: 12px; line-height: 1.6; }
        .sep { border-top: 1px dashed #999; margin: 8px 0; }
        .meta { margin: 8px 0 10px; }
        .meta-row { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; }
        thead th { border-bottom: 1px dashed #999; padding: 6px 0; text-align: left; font-weight: 700; }
        tbody td { padding: 6px 0; border-bottom: 1px dotted #ddd; }
        tfoot td { padding: 6px 0; font-weight: 700; }
        .right { text-align: right; }
        .center { text-align: center; }
        .totals-row td { border-top: 1px dashed #999; }
        .thanks { margin-top: 10px; text-align: center; font-size: 10px; }
        .doc-title { text-align: center; font-size: 18px; font-weight: 700; margin: 6px 0 8px; }
    </style>
    </head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="brand">Canvas Cafe</div>
            <div class="muted">123 Street, Gujarat, India</div>
            <div class="muted">Phone: +91 98765 43210 • Email: canvascafe02@gmail.com</div>
            <div class="muted">GSTIN: 24ABCDE1234F1Z5</div>
        </div>

        <div class="sep"></div>
        <h3 class="doc-title">BILL</h3>

        <div class="meta">
            <div class="meta-row"><span>Customer</span><span>' . htmlspecialchars($data['CustomerName']) . '</span></div>
            <div class="meta-row"><span>Email</span><span>' . htmlspecialchars($data['Email']) . '</span></div>
            <div class="meta-row"><span>Date</span><span>' . htmlspecialchars($data['Date']) . '</span></div>
            <div class="meta-row"><span>Invoice No.</span><span>' . htmlspecialchars($invoiceNo) . '</span></div>
        </div>

        <div class="sep"></div>

        <table>
        <thead>
            <tr>
                <th style="width:8%">#</th>
                <th>Item</th>
                <th class="center" style="width:16%">Qty</th>
                <th class="right" style="width:22%">Rate</th>
                <th class="right" style="width:22%">Amount</th>
            </tr>
        </thead>
        <tbody>';



$i = 1;
foreach ($orderItem as $item) {
    $name = isset($item['item_name']) ? $item['item_name'] : '';
    $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $price = isset($item['item_price']) ? (float)$item['item_price'] : 0;
    $lineTotal = $price * $qty;
    $html .= '<tr>'
        . '<td class="center">' . $i++ . '</td>'
        . '<td>' . htmlspecialchars($name) . '</td>'
        . '<td class="center">' . $qty . '</td>'
        . '<td class="right">₹' . number_format($price, 2) . '</td>'
        . '<td class="right">₹' . number_format($lineTotal, 2) . '</td>'
        . '</tr>';
}

$html .= '</tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right">Total Amount</td>
                <td class="right">₹' . number_format((float)$data['TotalAmount'], 2) . '</td>
            </tr>
        </tfoot>
    </table>

    <div class="note">Thank you for dining with Canvas Cafe!</div>
</body>
</html>';

// Load the HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation (A4 landscape)
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Generate a unique file name for the PDF
$filename = 'order_bill_' . date('YmdHis') . '.pdf';


// Get the PDF content
$pdfContent = $dompdf->output();

// Stream the PDF to the browser for direct download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($pdfContent));
echo $pdfContent;
exit; // prevent any further output

try {
    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    // Configure SMTP settings (replace with your own SMTP server settings)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'francybhalani@gmail.com'; // Replace with your Gmail email address
        $mail->Password   = 'quuymyfkylsvucbi'; // Replace with your Gmail password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->Port       = 587; // TLS port

    // Set the sender and recipient email addresses
    $mail->setFrom('francybhalani@gmail.com', 'Cafe Management');
    $mail->addAddress($data['Email'], $data['CustomerName']);

    // Set email subject
    $mail->Subject = 'Order Bill';

    // Set email body (you can add a message here if needed)
    $mail->Body = 'Please find the attached order bill.';

    // Attach the PDF file
    $mail->addStringAttachment($pdfContent, $filename, 'base64', 'application/pdf');

    // Send the email
    $mail->send();

    // Email sent (download already started)
} catch (Exception $e) {
    // Handle any exceptions that occur during sending
    // Ignore email failure for download
}
?>
