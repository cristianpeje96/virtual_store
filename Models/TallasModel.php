<?php
class TallasModel extends Mysql
{
    public $intIdtalla;

    // Método para obtener todas las tallas
    public function selectTallas()
    {
        $sql = "SELECT idtalla, talla FROM talla WHERE status != 0";
        $request = $this->select_all($sql);
        error_log('Datos obtenidos: ' . print_r($request, true));
        return $request;
    }

    // Método adicional si es necesario para obtener una talla específica
    public function selectTalla(int $idtalla)
    {
        $this->intIdtalla = $idtalla;
        $sql = "SELECT * FROM talla WHERE idtalla = $this->intIdtalla";
        $request = $this->select_all($sql);
        return $request;
    }

    // Nuevo método para obtener las tallas de un producto específico
    public function selectTallasByProduct(int $idproducto)
    {
        $sql = "SELECT t.idtalla, t.talla 
                FROM talla t
                JOIN producto_talla pt ON t.idtalla = pt.tallaid
                WHERE pt.idproducto = $idproducto AND t.status != 0";
        $request = $this->select_all($sql);
        error_log('Datos obtenidos para el producto: ' . print_r($request, true));
        return $request;
    }
    public function deleteTallasByProducto($idProducto)
    {
        $sql = "DELETE FROM producto_talla WHERE idproducto = ?";
        $arrData = array($idProducto);
        $request = $this->delete($sql, $arrData); // Ahora pasamos ambos parámetros.
        return $request;
    }

    public function insertTallaProducto($idProducto, $tallaid)
    {
        $sql = "INSERT INTO producto_talla(idproducto, tallaid) VALUES(?, ?)";
        $arrData = array($idProducto, $tallaid);
        $request = $this->insert($sql, $arrData);
        return $request;
    }
}
?>