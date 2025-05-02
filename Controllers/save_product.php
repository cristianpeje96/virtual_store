<?php
require 'Config.php'; // Asegúrate de tener la conexión a la base de datos

// Ejemplo de obtención o generación de productId
$productId = 123; // Asegúrate de que este valor provenga de la lógica de tu aplicación

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['listTallas']) && is_array($_POST['listTallas'])) {
        $listTallas = $_POST['listTallas']; // Obtener el array de tallas

        // Depurar el array de tallas recibidas
        error_log('Tallas recibidas: ' . print_r($listTallas, true));

        // Procesar cada talla
        try {
            $pdo->beginTransaction(); // Inicia una transacción
            error_log('Iniciando transacción');

            foreach ($listTallas as $tallaId) {
                // Sanitizar las entradas
                $tallaId = htmlspecialchars($tallaId, ENT_QUOTES, 'UTF-8');

                // Depurar la talla actual
                error_log('Procesando talla ID: ' . $tallaId);

                // Ejemplo de inserción en base de datos (usando PDO)
                $stmt = $pdo->prepare("INSERT INTO product_tallas (idproduct, tallaid) VALUES (:idproduct, :tallaid)");
                $stmt->execute([
                    ':idproduct' => $productId, // Usar el productId correcto
                    ':tallaid' => $tallaId
                ]);
                error_log('Consulta ejecutada: ' . $stmt->queryString);
            }

            $pdo->commit(); // Confirma la transacción
            error_log('Transacción confirmada');
            echo "Tallas guardadas correctamente.";
        } catch (Exception $e) {
            $pdo->rollBack(); // Revertir la transacción en caso de error
            error_log('Error al guardar las tallas: ' . $e->getMessage());
            echo "Error al guardar las tallas: " . $e->getMessage();
        }
    } else {
        error_log('No se han seleccionado tallas.');
        echo "No se han seleccionado tallas.";
    }
}
?>
