<?php
class ColoresModel extends Mysql
{
    public $intIdColor;

    // Método para obtener todos los colores
    public function selectColores() {
        $sql = "SELECT idcolor, nombrecolor, codigo_hex FROM colores WHERE status != 0";
        $request = $this->select_all($sql);

        // Depuración: Registra los datos obtenidos
        error_log('Datos obtenidos: ' . print_r($request, true));
        return $request;
    }

    // Método adicional para obtener un color específico
    public function selectColor(int $idcolor) {
        $this->intIdColor = $idcolor;
        $sql = "SELECT * FROM colores WHERE idcolor = $this->intIdColor";
        $request = $this->select_all($sql);
        return $request;
    }
}
?>
