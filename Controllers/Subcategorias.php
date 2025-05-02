<?php
class Subcategorias extends Controllers
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
    }

    public function Subcategorias()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Subcategorías";
        $data['page_title'] = "SUBCATEGORÍAS <small>Tienda Virtual</small>";
        $data['page_name'] = "subcategorias";
        $data['page_functions_js'] = "functions_productos.js"; // Adaptar para funciones de subcategorías
        $this->views->getView($this, "subcategorias", $data);
    }

    public function getSubcategoria($idsubcategoria)
    {
        if ($_SESSION['permisosMod']['r']) {
            $intIdsubcategoria = intval($idsubcategoria);
            if ($intIdsubcategoria > 0) {
                $arrData = $this->model->selectSubcategoria($intIdsubcategoria);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function getSubcategorias()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectSubcategorias();
            for ($i = 0; $i < count($arrData); $i++) {
                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                }
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function getSelectSubcategorias() {
        $htmlOptions = "";
        $arrData = $this->model->selectSubcategorias();
    
        // Validación adicional: Verifica que $arrData es un array
        if (is_array($arrData)) {
            foreach ($arrData as $item) {
                // Validación adicional: Verifica que las claves existen
                if (isset($item['idsubcategoria'], $item['nombresubcategoria'])) {
                    $htmlOptions .= '<option value="' . htmlspecialchars($item['idsubcategoria']) . '">' . htmlspecialchars($item['nombresubcategoria']) . '</option>';
                } else {
                    // Depuración: Registra si falta alguna clave
                    error_log('Item con claves faltantes: ' . print_r($item, true));
                }
            }
        } else {
            // Depuración: Registra si no hay datos
            error_log('No se encontraron subcategorías.');
        }
    
        // Depuración: Registra el contenido de $htmlOptions
        error_log('HTML Options: ' . $htmlOptions);
    
        // Enviar solo el HTML al cliente
        echo $htmlOptions;
        die();
    }
    
}
?>
