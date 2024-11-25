<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php'; // Incluir el archivo de conexión

$response = array('success' => false, 'message' => 'Error desconocido');

try {
    if (!$conn) {
        throw new Exception('Error en la conexión a la base de datos');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos de la solicitud
        $phoneNumber = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : '';
        $password = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

        if (empty($phoneNumber) || empty($password)) {
            throw new Exception('Número de teléfono y contraseña son requeridos');
        }

        // Consulta para verificar el usuario
        $sql = "SELECT * FROM cuentas WHERE numero_telefono = ? AND contraseña = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta');
        }

        $stmt->bind_param("ss", $phoneNumber, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $response = array('success' => true, 'message' => 'Inicio de sesión exitoso', 'user' => $user);
        } else {
            $response = array('success' => false, 'message' => 'Número de teléfono o contraseña incorrectos');
        }

        $stmt->close();
    } else {
        $response = array('success' => false, 'message' => 'Método no permitido, utilice POST.');
    }
} catch (Exception $e) {
    $response = array('success' => false, 'message' => $e->getMessage());
}

echo json_encode($response);
$conn->close();
?>
