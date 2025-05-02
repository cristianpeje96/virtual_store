<?php
class Productos extends Controllers
{
	public $model;
	public $views;
	public function __construct()
	{
		parent::__construct();
		session_start();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . '/login');
			die();
		}
		getPermisos(MPRODUCTOS);
	}

	public function Productos()
	{
		if (empty($_SESSION['permisosMod']['r'])) {
			header("Location:" . base_url() . '/dashboard');
		}
		$data['page_tag'] = "Productos";
		$data['page_title'] = "PRODUCTOS <small>Tienda Virtual</small>";
		$data['page_name'] = "productos";
		$data['page_functions_js'] = "functions_productos.js";
		$this->views->getView($this, "productos", $data);
	}

	public function getProductos()
	{
		if ($_SESSION['permisosMod']['r']) {
			$arrData = $this->model->selectProductos();
			for ($i = 0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if ($arrData[$i]['status'] == 1) {
					$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				} else {
					$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				}

				$arrData[$i]['precio'] = SMONEY . ' ' . formatMoney($arrData[$i]['precio']);
				if ($_SESSION['permisosMod']['r']) {
					$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['idproducto'] . ')" title="Ver producto"><i class="far fa-eye"></i></button>';
				}
				if ($_SESSION['permisosMod']['u']) {
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['idproducto'] . ')" title="Editar producto"><i class="fas fa-pencil-alt"></i></button>';
				}
				if ($_SESSION['permisosMod']['d']) {
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $arrData[$i]['idproducto'] . ')" title="Eliminar producto"><i class="far fa-trash-alt"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
			}
			echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function setProducto()
	{
	    $arrResponse = array();
	    if ($_POST) {
	        // Depuración y validación de datos recibidos
	        error_log('Datos recibidos: ' . print_r($_POST, true));

	        // Validación de entradas
	        $tallas = isset($_POST['listTallas']) && is_array($_POST['listTallas']) ? $_POST['listTallas'] : [];
	        if (
	            empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listCategoria']) ||
	            empty($_POST['listSubcategoria']) || empty($_POST['txtPrecio']) || empty($_POST['txtStock']) ||
	            empty($_POST['listColor']) || empty($tallas) || empty($_POST['listStatus'])
	        ) {
	            $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o incompletos.');
	            error_log("Error: Datos incorrectos o incompletos.");
	            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	            die();
	        }

	        // Datos básicos del producto
	        $idProducto = intval($_POST['idProducto']);
	        $strNombre = strClean($_POST['txtNombre']);
	        $strDescripcion = strClean($_POST['txtDescripcion']);
	        $strCodigo = strClean($_POST['txtCodigo']);
	        $intCategoriaId = intval($_POST['listCategoria']);
	        $intSubcategoriaId = intval($_POST['listSubcategoria']);
	        $strPrecio = floatval($_POST['txtPrecio']);
	        $intStock = intval($_POST['txtStock']);
	        $intColor = intval($_POST['listColor']);
	        $intStatus = intval($_POST['listStatus']);
	        $ruta = strtolower(clear_cadena($strNombre));
	        $ruta = str_replace(" ", "-", $ruta);
			$request_producto = "";
			$option = 0;

	        // Logs de depuración
	        error_log("ID Producto: $idProducto");
	        error_log("Nombre: $strNombre, Descripción: $strDescripcion, Código: $strCodigo");
	        error_log("Categoría: $intCategoriaId, Subcategoría: $intSubcategoriaId, Precio: $strPrecio");
	        error_log("Stock: $intStock, Color: $intColor, Tallas: " . print_r($tallas, true));
	        error_log("Ruta: $ruta, Estado: $intStatus");

	        // Verificar permisos y procesar según sea creación o actualización
	        if ($idProducto == 0) {
	            if (!$_SESSION['permisosMod']['w']) {
	                $arrResponse = array("status" => false, "msg" => 'No tienes permisos para agregar productos.');
	                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	                die();
	            }
	            $option = 1;
	            $request_producto = $this->model->insertProducto(
	                $strNombre,
	                $strDescripcion,
	                $strCodigo,
	                $intCategoriaId,
	                $intSubcategoriaId,
	                $strPrecio,
	                $intStock,
	                $intColor,
	                $ruta,
	                $intStatus
	            );
	            $idProducto = $request_producto;
	            error_log("Producto insertado con ID: $idProducto");
	        } else {
	            if (!$_SESSION['permisosMod']['u']) {
	                $arrResponse = array("status" => false, "msg" => 'No tienes permisos para actualizar productos.');
	                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	                die();
	            }
	            $option = 2;
	            $request_producto = $this->model->updateProducto(
	                $idProducto,
	                $strNombre,
	                $strDescripcion,
	                $strCodigo,
	                $intCategoriaId,
	                $intSubcategoriaId,
	                $strPrecio,
	                $intStock,
	                $intColor,
	                $ruta,
	                $intStatus
	            );
	            error_log("Producto actualizado con ID: $idProducto");
	        }

	        // Procesar resultado
	        if ($request_producto > 0) {
	            // Eliminar e insertar tallas relacionadas
	            $this->model->deleteTallasByProducto($idProducto);
	            error_log("Tallas eliminadas para Producto ID: $idProducto");
	            foreach ($tallas as $idtalla) {
					$idProducto = (int) $idProducto;
					if (!is_array($tallas)) {
						$tallas = is_array($_POST['tallas']) ? $_POST['tallas'] : [$_POST['tallas']]; // Convierte a array si es un entero o un solo valor.
					}
	                $this->model->insertProductoTallas($idProducto, $tallas);
	                error_log("Talla $idtalla insertada para Producto ID: $idProducto");
	            }

	            $msg = ($option == 1) ? 'Datos guardados correctamente.' : 'Datos actualizados correctamente.';
	            $arrResponse = array('status' => true, 'idproducto' => $idProducto, 'msg' => $msg);
	        } elseif ($request_producto == 'exist') {
	            $arrResponse = array('status' => false, 'msg' => '¡Atención! Ya existe un producto con el código ingresado.');
	        } else {
	            $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
	        }

	        error_log("Respuesta: " . json_encode($arrResponse));
	        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	    } else {
	        $arrResponse = array("status" => false, "msg" => "No se recibieron datos.");
	        error_log("Error: No se recibieron datos.");
	        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	    }
	    die();
	}

	
	/**
	 * Limpia las tallas existentes y vuelve a insertarlas.
	 */
	private function procesarTallasProducto($idProducto, $tallas) {
		// Eliminar tallas anteriores
		$this->model->deleteTallasByProducto($idProducto);
	
		// Insertar nuevas tallas
		foreach ($tallas as $idtalla) {
			$this->model->insertProductoTallas($idProducto, intval($idtalla));
		}
	}
	


	public function getProducto($idproducto)
	{
		if ($_SESSION['permisosMod']['r']) {
			$idproducto = intval($idproducto);
			if ($idproducto > 0) {
				$arrData = $this->model->selectProducto($idproducto);
				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrImg = $this->model->selectImages($idproducto);
					if (count($arrImg) > 0) {
						for ($i = 0; $i < count($arrImg); $i++) {
							$arrImg[$i]['url_image'] = media() . '/images/uploads/' . $arrImg[$i]['img'];
						}
					}
					$arrData['images'] = $arrImg;
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}
	public function getTallasproducto($idProducto) {
		
		$idProducto = intval($idProducto);
		$tallas = $this->model->getTallasDisponibles($idProducto);
		// Debug para verificar tallas
		echo "<pre>";
		print_r($_POST);
		die(); // Pausa la ejecución para inspeccionar el resultado
	}			
	public function setImage()
	{
		if ($_POST) {
			if (empty($_POST['idproducto'])) {
				$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
			} else {
				$idProducto = intval($_POST['idproducto']);
				$foto = $_FILES['foto'];

				// Validar si la imagen existe y no tiene errores
				if (empty($foto['name']) || $foto['error'] != 0) {
					$arrResponse = array('status' => false, 'msg' => 'Error al cargar la imagen.');
					echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
					die();
				}

				// Extensiones permitidas
				$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
				$ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION)); // Convertir extensión a minúscula

				if (!in_array($ext, $extensionesPermitidas)) {
					$arrResponse = array('status' => false, 'msg' => 'Formato de imagen no permitido.');
					echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
					die();
				}

				// También podemos validar el tipo MIME para asegurarnos
				$mimePermitidos = ['image/jpeg', 'image/png', 'image/gif'];
				$tipoMime = mime_content_type($foto['tmp_name']);

				if (!in_array($tipoMime, $mimePermitidos)) {
					$arrResponse = array('status' => false, 'msg' => 'El tipo de archivo no coincide con un formato de imagen permitido.');
					echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
					die();
				}

				// Renombrar la imagen
				$imgNombre = 'pro_' . md5(date('d-m-Y H:i:s')) . '.' . $ext;
				$request_image = $this->model->insertImage($idProducto, $imgNombre);

				if ($request_image) {
					$uploadImage = uploadImage($foto, $imgNombre);

					if ($uploadImage) {
						$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
					} else {
						$arrResponse = array('status' => false, 'msg' => 'Error al subir la imagen al servidor.');
					}
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error de carga en la base de datos.');
				}
			}

			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}


	//public function setImage(){
	//	if($_POST){
	//		if(empty($_POST['idproducto'])){
	//			$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
	//		}else{
	//			$idProducto = intval($_POST['idproducto']);
	//			$foto      = $_FILES['foto'];
	//			$imgNombre = 'pro_'.md5(date('d-m-Y H:i:s')).'.jpg';
	//			$request_image = $this->model->insertImage($idProducto,$imgNombre);
	//			if($request_image){
	//				$uploadImage = uploadImage($foto,$imgNombre);
	//				$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
	//			}else{
	//				$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
	//			}
	//		}
	//		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	//	}
	//	die();
	//}

	public function delFile()
	{
		if ($_POST) {
			if (empty($_POST['idproducto']) || empty($_POST['file'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			} else {
				//Eliminar de la DB
				$idProducto = intval($_POST['idproducto']);
				$imgNombre = strClean($_POST['file']);
				$request_image = $this->model->deleteImage($idProducto, $imgNombre);

				if ($request_image) {
					$deleteFile = deleteFile($imgNombre);
					$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delProducto()
	{
		if ($_POST) {
			if ($_SESSION['permisosMod']['d']) {
				$intIdproducto = intval($_POST['idProducto']);
				$requestDelete = $this->model->deleteProducto($intIdproducto);
				if ($requestDelete) {
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto.');
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
		}
		die();
	}

	public function filtrarProductos($categoria = '', $subcategoria = '', $talla = '', $color = '')
	{
		// Lógica para obtener productos filtrados
		$arrProductos = $this->model->getFilteredProducts($categoria, $subcategoria, $talla, $color);

		// Iniciar la variable de respuesta
		$html = '';

		if (count($arrProductos) > 0) {
			foreach ($arrProductos as $producto) {
				// Similar al código anterior para generar el HTML
				$ruta = isset($producto['ruta']) ? $producto['ruta'] : '';
				$portada = isset($producto['url_image']) && !empty($producto['url_image'])
					? $producto['url_image']
					: media() . '/images/uploads/product.png';

				// Generar HTML de producto
				$html .= '<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
							<div class="block2">
								<div class="block2-pic hov-img0">
									<img src="' . htmlspecialchars($portada, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') . '">
									<a href="' . base_url() . '/tienda/producto/' . $producto['idproducto'] . '/' . htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') . '" 
									   class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
										Ver producto
									</a>
								</div>
								<div class="block2-txt flex-w flex-t p-t-14">
									<div class="block2-txt-child1 flex-col-l">
										<a href="' . base_url() . '/tienda/producto/' . $producto['idproducto'] . '/' . htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8') . '" 
										   class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
											' . htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') . '
										</a>
										<span class="stext-105 cl3">
											' . SMONEY . formatMoney($producto['precio']) . '
										</span>
									</div>
									<div class="block2-txt-child2 flex-r p-t-3">
										<a href="#" id="' . openssl_encrypt($producto['idproducto'], METHODENCRIPT, KEY) . '" 
										   class="btn-addwish-b2 dis-block pos-relative js-addwish-b2 js-addcart">
											<i class="zmdi zmdi-shopping-cart"></i>
										</a>
									</div>
								</div>
							</div>
						</div>';
			}
		} else {
			$html .= '<p>No hay productos para mostrar. <a href="' . base_url() . '/tienda">Ver productos</a></p>';
		}

		// Devolver el HTML generado en formato JSON
		echo json_encode(['status' => true, 'html' => $html]);
		exit; // Termina la ejecución
	}
	public function selcttallacolor()
	{
		parent::__construct();
	}
}

?>