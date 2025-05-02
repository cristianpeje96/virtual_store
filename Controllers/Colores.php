<?php
class Colores extends Controllers
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

    public function Colores()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Colores";
        $data['page_title'] = "COLORES <small>Tienda Virtual</small>";
        $data['page_name'] = "colores";
        $data['page_functions_js'] = "functions_productos.js"; // Adaptar para funciones de colores
        $this->views->getView($this, "colores", $data);
    }

    public function getColor($idcolor)
    {
        if ($_SESSION['permisosMod']['r']) {
            $intIdColor = intval($idcolor);
            if ($intIdColor > 0) {
                $arrData = $this->model->selectColor($intIdColor);
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

    public function getColores()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectColores();
            for ($i = 0; $i < count($arrData); $i++) {
                if ($arrData[$i]['estatus'] == 1) {
                    $arrData[$i]['estatus'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['estatus'] = '<span class="badge badge-danger">Inactivo</span>';
                }
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function getSelectColores()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectColores();

        if (is_array($arrData)) {
            foreach ($arrData as $item) {
                if (isset($item['idcolor'], $item['nombrecolor'], $item['codigo_hex'])) {
                    // Generar un estilo en línea para el color
                    $colorStyle = 'background-color: ' . htmlspecialchars($item['codigo_hex']) . '; width: 20px; height: 20px; display: inline-block; margin-right: 5px;';
                    $htmlOptions .= '<option value="' . htmlspecialchars($item['idcolor']) . '" data-color="' . htmlspecialchars($item['codigo_hex']) . '">'
                                    . '<span style="' . $colorStyle . '"></span> '
                                    . htmlspecialchars($item['nombrecolor']) . '</option>';
                } else {
                    error_log('Item con claves faltantes: ' . print_r($item, true));
                }
            }
        } else {
            error_log('No se encontraron colores.');
        }

        error_log('HTML Options: ' . $htmlOptions);
        echo $htmlOptions;
        die();
    }

}
?>
