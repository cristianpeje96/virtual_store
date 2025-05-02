<?php

class ProductosModel extends Mysql
{
    private $intIdProducto;
    private $strNombre;
    private $strDescripcion;
    private $intCodigo;
    private $intCategoriaId;
    private $intSubcategoriaId;
    private $intPrecio;
    private $intStock;
    private $intColorId;
    private $intStatus;
    private $strRuta;
    private $strImagen;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectProductos()
    {
        $sql = "SELECT p.idproducto,
                        p.codigo,
                        p.nombre,
                        p.descripcion,
                        p.categoriaid,
                        c.nombre as categoria,
                        s.nombresubcategoria as subcategorias, 
                        p.precio,
                        p.stock,
                        col.nombrecolor as colores,
                        p.status 
                FROM producto p 
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                INNER JOIN subcategorias s ON p.subcategoriaid = s.idsubcategoria
                INNER JOIN colores col ON p.colorid = col.idcolor
                WHERE p.status != 0";
        $request = $this->select_all($sql);

        // Obtener las tallas para cada producto
        foreach ($request as &$producto) {
            $sqlTallas = "SELECT t.idtalla, t.talla 
                          FROM producto_talla pt
                          INNER JOIN talla t ON pt.tallaid = t.idtalla
                          WHERE pt.idproducto = ?";
            $arrData = array($producto['idproducto']);
            $tallas = $this->select_all($sqlTallas, $arrData);
            $producto['talla'] = implode(", ", array_column($tallas, 'talla'));
        }

        return $request;
    }

    public function insertProducto(string $nombre, string $descripcion, int $codigo, int $categoriaid, int $subcategoriaid, float $precio, int $stock, int $colorid, string $ruta, int $status)
    {
        $this->strNombre = $nombre;
        $this->strDescripcion = $descripcion;
        $this->intCodigo = $codigo;
        $this->intCategoriaId = $categoriaid;
        $this->intSubcategoriaId = $subcategoriaid;
        $this->intPrecio = $precio;
        $this->intStock = $stock;
        $this->intColorId = $colorid;
        $this->strRuta = $ruta;
        $this->intStatus = $status;
        $return = 0;

        $sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO producto(categoriaid,
                                                    subcategoriaid,
                                                    codigo,
                                                    nombre,
                                                    descripcion,
                                                    precio,
                                                    stock,
                                                    colorid,                                          
                                                    ruta,
                                                    status) 
                              VALUES(?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $this->intCategoriaId,
                $this->intSubcategoriaId,
                $this->intCodigo,
                $this->strNombre,
                $this->strDescripcion,
                $this->intPrecio,
                $this->intStock,
                $this->intColorId,
                $this->strRuta,
                $this->intStatus
            );
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function insertProductoTallas(int $idproducto, array $tallas)
    {
        // Eliminar las asociaciones de tallas existentes para este producto
        $sqlDeleteTallas = "DELETE FROM producto_talla WHERE idproducto = ?";
        $this->delete($sqlDeleteTallas, [$idproducto]);

        // Insertar las nuevas asociaciones de tallas
        foreach ($tallas as $idtalla) {
            $sqlInsertTallas = "INSERT INTO producto_talla (idproducto, tallaid) VALUES (?, ?)";
            $arrValues = [$idproducto, intval($idtalla)];
            $this->insert($sqlInsertTallas, $arrValues);
        }
    }

    public function deleteTallasByProducto($idProducto)
    {
        $sql = "DELETE FROM producto_talla WHERE idproducto = ?";
        $arrData = array($idProducto);
        $request = $this->delete($sql, $arrData); // Ahora pasamos ambos parámetros.
        return $request;
    }


    public function updateProducto(int $idproducto, string $nombre, string $descripcion, int $codigo, int $categoriaid, int $subcategoriaid, float $precio, int $stock, int $colorid, string $ruta, int $status)
    {
        $this->intIdProducto = $idproducto;
        $this->strNombre = $nombre;
        $this->strDescripcion = $descripcion;
        $this->intCodigo = $codigo;
        $this->intCategoriaId = $categoriaid;
        $this->intSubcategoriaId = $subcategoriaid;
        $this->intPrecio = $precio;
        $this->intStock = $stock;
        $this->intColorId = $colorid;
        $this->strRuta = $ruta;
        $this->intStatus = $status;
        $return = 0;

        $sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}' AND idproducto != $this->intIdProducto ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE producto 
                    SET categoriaid=?,
                        subcategoriaid=?,  
                        codigo=?,
                        nombre=?,
                        descripcion=?,
                        precio=?,
                        stock=?,
                        colorid=?,
                        ruta=?,
                        status=? 
                    WHERE idproducto = $this->intIdProducto ";
            $arrData = array(
                $this->intCategoriaId,
                $this->intSubcategoriaId,
                $this->intCodigo,
                $this->strNombre,
                $this->strDescripcion,
                $this->intPrecio,
                $this->intStock,
                $this->intColorId,
                $this->strRuta,
                $this->intStatus
            );

            $request = $this->update($sql, $arrData);
            $return = $request;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectProducto(int $idproducto)
    {
        $this->intIdProducto = $idproducto;
        $sql = "SELECT p.idproducto,
                        p.codigo,
                        p.nombre,
                        p.descripcion,
                        p.precio,
                        p.stock,
                        p.categoriaid,
                        p.subcategoriaid,
                        p.colorid,
                        c.nombre as categoria,
                        s.nombresubcategoria as subcategorias,
                        col.nombrecolor as colores,
                        p.status
                FROM producto p
                INNER JOIN categoria c ON p.categoriaid = c.idcategoria
                INNER JOIN subcategorias s ON p.subcategoriaid = s.idsubcategoria
                INNER JOIN colores col ON p.colorid = col.idcolor
                WHERE idproducto = $this->intIdProducto";
        $request = $this->select($sql);

        // Obtener las tallas para este producto
        $sqlTallas = "SELECT t.idtalla, t.talla 
                      FROM producto_talla pt
                      INNER JOIN talla t ON pt.tallaid = t.idtalla
                      WHERE pt.idproducto = ?";
        $arrData = array($this->intIdProducto);
        $request['tallas'] = $this->select_all($sqlTallas, $arrData);

        return $request;
    }

    // Métodos para manejar imágenes se mantienen igual
    public function insertImage(int $idproducto, string $imagen)
    {
        $this->intIdProducto = $idproducto;
        $this->strImagen = $imagen;
        $query_insert = "INSERT INTO imagen(productoid, img) VALUES(?,?)";
        $arrData = array($this->intIdProducto, $this->strImagen);
        $request_insert = $this->insert($query_insert, $arrData);
        return $request_insert;
    }

    public function selectImages(int $idproducto)
    {
        $this->intIdProducto = $idproducto;
        $sql = "SELECT productoid, img
                FROM imagen
                WHERE productoid = $this->intIdProducto";
        $request = $this->select_all($sql);
        return $request;
    }

    public function deleteImage(int $idproducto, string $imagen)
    {
        $this->intIdProducto = $idproducto;
        $this->strImagen = $imagen;
        $query = "DELETE FROM imagen 
                    WHERE productoid = $this->intIdProducto 
                    AND img = '{$this->strImagen}'";
        $request_delete = $this->delete($query);
        return $request_delete;
    }

    public function deleteProducto(int $idproducto)
    {
        $this->intIdProducto = $idproducto;
        $sql = "UPDATE producto SET status = ? WHERE idproducto = $this->intIdProducto ";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function getFilteredProducts($categoria = '', $subcategoria = '', $talla = '', $color = '')
    {
        $sql = "SELECT *, url_image FROM producto WHERE status = 1";

        // Inicializar el arreglo de parámetros
        $params = [];

        // Filtrar por categoría
        if (!empty($categoria)) {
            $sql .= " AND categoriaid = ?";
            $params[] = intval($categoria); // Asumimos que hay un campo `categoriaid` en la tabla `producto`
        }

        // Filtrar por subcategoría
        if (!empty($subcategoria)) {
            $sql .= " AND subcategoriaid = ?";
            $params[] = intval($subcategoria);
        }

        // Filtrar por talla
        if (!empty($talla)) {
            $sql .= " AND tallaid = ?";
            $params[] = strClean($talla);
        }

        // Filtrar por color
        if (!empty($color)) {
            $sql .= " AND colorid = ?";
            $params[] = strClean($color);
        }

        // Ejecutar la consulta con los parámetros usando una consulta preparada
        $request = $this->select_all($sql, $params);

        return $request;
    }
    public function getTallasDisponibles($idProducto) {
        $sql = "SELECT t.idtalla, t.talla 
                FROM producto_talla pt
                INNER JOIN talla t ON pt.tallaid = t.idtalla
                WHERE pt.idproducto = ?";
        $arrData = [$idProducto];
        return $this->select_all($sql, $arrData);
    }
    



}
?>