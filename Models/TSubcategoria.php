<?php 
require_once("Libraries/Core/Mysql.php");

trait TSubcategoria{
	private $con;

	// Obtener subcategorías por ID
	public function getSubcategoriaT(string $subcategoria, string $desde){
		$this->con = new Mysql();
		$sql = "SELECT idsubcategoria, nombresubcategoria
				 FROM subcategorias 
				 WHERE status != 0 AND idsubcategoria IN ($subcategoria)";
		$request = $this->con->select_all($sql);
		return $request;
	}

	// Obtener todas las subcategorías y el conteo de productos por subcategoría
	public function getsubcategorias(){
		$this->con = new Mysql();
		$sql = "SELECT c.idsubcategoria, c.nombresubcategoria, COUNT(p.subcategoriaid) AS cantidad
				FROM subcategorias c 
				LEFT JOIN producto p ON p.subcategoriaid = c.idsubcategoria
				WHERE c.status = 1
				GROUP BY c.idsubcategoria, c.nombresubcategoria";
		$request = $this->con->select_all($sql);
		return $request;
	}

	// Obtener tallas disponibles y el conteo de productos por talla
	public function getTallas() {
		$this->con = new Mysql();
		$sql = "SELECT t.idtalla, t.talla, COUNT(pt.tallaid) as cantidad
				FROM producto_talla pt
				INNER JOIN talla t ON pt.tallaid = t.idtalla
				INNER JOIN producto p ON pt.idproducto = p.idproducto
				WHERE t.status = 1
				GROUP BY t.idtalla, t.talla";
		$request = $this->con->select_all($sql);
		return $request;
	}
	

	// Obtener colores disponibles y el conteo de productos por color
	public function getColores() {
		$this->con = new Mysql();
		$sql = "SELECT c.idcolor, c.nombrecolor, COUNT(p.colorid) as cantidad 
				FROM producto p 
				INNER JOIN colores c ON p.colorid = c.idcolor
				WHERE c.status = 1
				GROUP BY c.idcolor, c.nombrecolor";
		$request = $this->con->select_all($sql);
		return $request;
	}
}

?>
