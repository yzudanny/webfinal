<?php

require_once('../TCPDF/tcpdf_import.php');

/*---------------- Sent Mail Start -----------------*/
$name = $_POST['name'];
$department = $_POST['department'];
$id = $_POST['id'];
$pnumber = $_POST['pnumber'];
$mail = $_POST['mail'];

$from = "[email protected]";

$to = "$mail";
$subject = "四系迎新報名成功";
$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
$attach_filename = date("Y-m-d") . ".html";

$emailBody =  "
請確認以下資料:
  姓名: $name
  系所: $department
  學號: $id
  電話: $pnumber
  電子郵件: $mail
";

# 然後我們要作為附件的HTML檔案 
$attachment =  "<html>
<head>
<title>The attached file</title>
</head>
<body>
<h2>This is the attached HTML file</h2>
</body>
</html>";

$boundary = uniqid("");

$headers =  "From: $from
To: $to
Content-type: multipart/mixed; boundary=\"$boundary\"";

$emailBody =  "--$boundary
Content-type: text/plain; charset=utf-8
Content-transfer-encoding: 8bit

$emailBody

--$boundary
Content-type: text/html; name=$attach_filename
Content-disposition: inline; filename=$attach_filename
Content-transfer-encoding: 8bit

$attachment

--$boundary--";

mail("[email protected]", $subject, $emailBody, $headers);

/*---------------- Sent Mail End -------------------*/

/*---------------- Print PDF Start -----------------*/
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetFont('cid0jp','', 18); 
$pdf->AddPage();

$name = $_POST['name'];
$department = $_POST['department'];
$id = $_POST['id'];
$pnumber = $_POST['pnumber'];
$mail = $_POST['mail'];
$html = <<<EOF
<p>請確認電子郵件</p>
<table border = "1" width = 400 height = 300>
            <tr>
               <td colspan = "1">名字</td>
               <td colspan = "3" style = "color:red">$name</td>
               <td colspan = "1">系所</td>
               <td colspan = "3">$department</td>
            </tr>
            <tr>
               <td colspan = "1">學號</td>
               <td colspan = "3">$id</td>
               <td colspan = "1">電話</td>
               <td colspan = "3">$pnumber</td>
            </tr>
            <tr>
              <td colspan = "2">電子郵件</td>
              <td colspan = "6">$mail</td>
            </tr>
</table>
EOF;
/*---------------- Print PDF End -------------------*/

$pdf->writeHTML($html);
$pdf->lastPage();
$pdf->Output('order.pdf', 'I');