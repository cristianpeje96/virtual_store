<?php
class SubcategoriasModel extends Mysql
{
    public $intIdsubcategoria;

    // Método para obtener todas las subcategorías
    public function selectSubcategorias() {
        // Consulta para obtener id y nombre de las subcategorías
        $sql = "SELECT idsubcategoria, nombresubcategoria FROM subcategorias WHERE status != 0";
        $request = $this->select_all($sql);

        // Depuración: Registra los datos obtenidos
        error_log('Datos obtenidos: ' . print_r($request, true));
        return $request;
    }

    // Método adicional si es necesario para obtener una subcategoría específica
    public function selectSubcategoria(int $idsubcategoria) {
        $this->intIdsubcategoria = $idsubcategoria;
        $sql = "SELECT * FROM subcategorias WHERE idsubcategoria = $this->intIdsubcategoria";
        $request = $this->select_all($sql);
        return $request;
    }

}

?>