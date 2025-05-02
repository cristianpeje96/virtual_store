<?php

class Tallas extends Controllers
{
    
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function Tallas()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Tallas";
        $data['page_title'] = "TALLAS <small>Tienda Virtual</small>";
        $data['page_name'] = "tallas";
        $data['page_functions_js'] = "functions_productos.js"; // Asegúrate de crear y adaptar este archivo JS

        // Obtener las tallas
        $data['tallas'] = $this->model->selectTallas();

        $this->views->getView($this, "tallas", $data);
    }

    public function getTallas()
    {
        if ($_SESSION['permisosMod']['r']) {
            $arrData = $this->model->selectTallas();
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

    public function getSelectTallas()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectTallas(); // Consulta al modelo

        if (is_array($arrData)) {
            foreach ($arrData as $item) {
                if (isset($item['idtalla'], $item['talla'])) {
                    $htmlOptions .= '<option value="' . htmlspecialchars($item['idtalla']) . '">' . htmlspecialchars($item['talla']) . '</option>';
                } else {
                    error_log('Item con claves faltantes: ' . print_r($item, true)); // Log de error si faltan datos
                }
            }
        } else {
            error_log('No se encontraron tallas.'); // Log de error si no hay resultados
        }

        if (empty($htmlOptions)) {
            $htmlOptions = '<option value="">No hay tallas disponibles</option>'; // Mensaje por defecto
        }

        error_log('HTML Options generadas: ' . $htmlOptions); // Log para verificar las opciones
        echo $htmlOptions; // Devuelve las opciones al frontend
        die();
    }

}
?>