<?php
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

// Conexión a la base de datos
$servername = "localhost";
$username = "u814339862_admin"; // Usuario de la base de datos
$password = "Stafatima104!";     // Contraseña de la base de datos
$dbname = "u814339862_produccion"; // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500); // Error del servidor
    echo json_encode(["status" => "error", "message" => "Error de conexión a la base de datos: " . $conn->connect_error]);
    exit;
}

// Obtener el código QR enviado desde el frontend
$codigo_qr = $_POST["codigo_qr"] ?? null;

if (empty($codigo_qr)) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(["status" => "error", "message" => "Código QR no proporcionado"]);
    exit;
}

// Escapar el código QR para prevenir inyecciones SQL
$codigo_qr = $conn->real_escape_string($codigo_qr);

// Realizar la consulta
$sql = "SELECT * FROM tabla WHERE uniqno = '$codigo_qr'";
$result = $conn->query($sql);

if ($result === false) {
    http_response_code(500); // Error del servidor
    echo json_encode(["status" => "error", "message" => "Error en la consulta SQL: " . $conn->error]);
    exit;
}

if ($result->num_rows > 0) {
    // Se encontraron resultados
    $row = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $row]);
} else {
    // No se encontraron resultados
    echo json_encode(["status" => "error", "message" => "No se encontraron resultados"]);
}

$conn->close();
?>