<?php
include 'db_connection.php'; // Incluir el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos de la solicitud
    $phoneNumber = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : '';
    $password = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

    // Validar que los campos no estén vacíos
    if (empty($phoneNumber) || empty($password)) {
        echo json_encode(array('success' => false, 'message' => 'Todos los campos son obligatorios.'));
        exit();
    }

    // Consulta para verificar si el número de teléfono ya está registrado
    $sql = "SELECT * FROM cuentas WHERE numero_telefono = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die(json_encode(array('success' => false, 'message' => 'Error en la preparación de la consulta')));
    }

    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(array('success' => false, 'message' => 'Este número de teléfono ya está registrado.'));
    } else {
        // Si el número de teléfono no está registrado, insertar nuevo usuario
        $insertSql = "INSERT INTO cuentas (numero_telefono, contraseña) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        
        if ($insertStmt === false) {
            die(json_encode(array('success' => false, 'message' => 'Error en la preparación de la consulta de inserción')));
        }

        $insertStmt->bind_param("ss", $phoneNumber, $password);
        if ($insertStmt->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Registro exitoso.'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al registrar el usuario.'));
        }

        $insertStmt->close();
    }

    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Método no permitido, utilice POST.'));
}

$conn->close();
?>
