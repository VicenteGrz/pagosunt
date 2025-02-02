<?php
include 'db_connect.php';
$fees = $conn->query("SELECT ef.*,s.name as sname,s.id_no,concat(c.course,'  ',c.level) as `class` FROM student_ef_list ef inner join student s on s.id = ef.student_id inner join courses c on c.id = ef.course_id  where ef.id = {$_GET['ef_id']}");
foreach ($fees->fetch_array() as $k => $v) {
	$$k = $v;
}
$payments = $conn->query("SELECT * FROM payments where ef_id = $id ");
$pay_arr = array();
while ($row = $payments->fetch_array()) {
	$pay_arr[$row['id']] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container-fluid {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #2c3e50;
        }
        .flex {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .w-50 {
            width: 48%;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <img src="na.png" alt="Logo UNT">
            <h1><?php echo $_GET['pid'] == 0 ? "Universidad Del Norte de Tamaulipas" : 'Recibo de Pago UNT' ?></h1>
        </div>
        <hr>
        <div class="flex">
            <div class="w-50">
                <p>Folio: <b><?php echo $ef_no ?></b></p>
                <p>Fecha de Expedición: <b><?php echo date("d/m/Y"); ?></b></p>
                <p>Alumno: <b><?php echo ucwords($sname) ?></b></p>
                <p>Matrícula: <b><?php echo $id_no ?></b></p>
                <p>Periodo: <b>2024</b></p>
                <p>Trámite: <b><?php echo $class ?></b></p>
                <p>Número de cuenta: <b>01447065088415581</b></p>
                <p>Santander: <b>65508841558</b></p>
                <p>Depósito OXXO: <b>5579 0890 0439 7983</b></p>
            </div>
            <?php if ($_GET['pid'] > 0) : ?>
                <div class="w-50">
                    <p>Fecha de Expedición: <b><?php echo isset($pay_arr[$_GET['pid']]) ? date("d/m/Y", strtotime($pay_arr[$_GET['pid']]['date_created'])) : '' ?></b></p>
                    <p>Fecha de Vencimiento: <b><?php echo isset($pay_arr[$_GET['pid']]) ? date("d/m/Y", strtotime($pay_arr[$_GET['pid']]['date_created'])) : '' ?></b></p>
                    <p>Monto de Pago: <b><?php echo isset($pay_arr[$_GET['pid']]) ? number_format($pay_arr[$_GET['pid']]['amount'], 2) : '' ?></b></p>
                    <p>Observación: <b><?php echo isset($pay_arr[$_GET['pid']]) ? $pay_arr[$_GET['pid']]['remarks'] : '' ?></b></p>
                </div>
            <?php endif; ?>
        </div>
        <hr>
        <table>
            <tr>
                <th>Concepto</th>
                <th class="text-right">Monto</th>
            </tr>
            <?php
            $cfees = $conn->query("SELECT * FROM fees where course_id = $course_id");
            $ftotal = 0;
            while ($row = $cfees->fetch_assoc()) {
                $ftotal += $row['amount'];
            ?>
                <tr>
                    <td><?php echo $row['description'] ?></td>
                    <td class="text-right"><?php echo number_format($row['amount'], 2) ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
        <div class="total text-right">
            TOTAL A PAGAR: <b><?php echo number_format($ftotal, 2) ?></b>
        </div>
        <div class="footer">
            <p>Gracias por su pago. Para cualquier consulta, contacte a la administración.</p>
        </div>
    </div>
</body>
</html>