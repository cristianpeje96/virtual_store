<?php
class Mysql extends Conexion
{
	private $conexion;
	private $strquery;
	private $arrValues; // Declaración correcta de la propiedad

	function __construct()
	{
		$this->conexion = new Conexion();
		$this->conexion = $this->conexion->conect();
	}

	// Insertar un registro
	public function insert(string $query, array $arrValues)
	{
		try {
			$this->strquery = $query;
			$insert = $this->conexion->prepare($this->strquery);
			$resInsert = $insert->execute($arrValues);

			// Si la inserción fue exitosa, devolver el ID insertado; si no, devolver 0.
			$lastInsert = ($resInsert) ? $this->conexion->lastInsertId() : 0;

			return $lastInsert;
		} catch (PDOException $e) {
			// Registro del error o manejo personalizado.
			error_log("Error en la inserción: " . $e->getMessage());
			return 0;
		}
	}


	// Busca un registro
	public function select(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$result->execute();
		$data = $result->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	// Devuelve todos los registros
	public function select_all(string $query, array $params = [])
	{
		try {
			$this->strquery = $query;
			$result = $this->conexion->prepare($this->strquery);
			$result->execute($params); // Ejecuta la consulta con parámetros
			return $result->fetchAll(PDO::FETCH_ASSOC); // Devuelve todos los resultados
		} catch (PDOException $e) {
			// Puedes manejar el error como desees
			echo "Error en la consulta: " . $e->getMessage();
			return []; // Devuelve un arreglo vacío en caso de error
		}
	}

	// Actualiza registros
	public function update(string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues; // Asignación correcta
		$update = $this->conexion->prepare($this->strquery);
		$resExecute = $update->execute($this->arrValues);
		return $resExecute;
	}

	// Eliminar un registro
	public function delete(string $query, array $arrData = [])
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$del = $result->execute($arrData); // Ejecuta con parámetros seguros.
		return $del;
	}


	public function prepare(string $query)
	{
		return $this->conexion->prepare($query);
	}
}
?>