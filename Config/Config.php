<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	const BASE_URL = "http://localhost/tienda_virtual";
	//const BASE_URL = "https://abelosh.com/tiendavirtual";

	//Zona horaria
	date_default_timezone_set('America/Bogota');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "db_tiendavirtual";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//Para envío de correo
	const ENVIRONMENT = 0; // Local: 0, Produccón: 1;

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "$";
	const CURRENCY = "COP";

	//Api PayPal
	//SANDBOX PAYPAL
	const URLPAYPAL = "https://api-m.sandbox.paypal.com";
	const IDCLIENTE = "";
	const SECRET = "";
	//LIVE PAYPAL
	//const URLPAYPAL = "https://api-m.paypal.com";
	//const IDCLIENTE = "";
	//const SECRET = "";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Tienda Virtual PIPE´SHOES";
	const EMAIL_REMITENTE = "pipeshoes@gmail.com";
	const NOMBRE_EMPESA = "PIPE´SHOES";
	const WEB_EMPRESA = "www.pipeshoespasto.com";

	const DESCRIPCION = "Tienda de zapatos fisica y virtual.";
	const SHAREDHASH = "TiendaVirtual";

	//Datos Empresa
	const DIRECCION = "Carrera 21A#16-15 (parqueadero motos amorel) Pasto-Nariño ";
	const TELEMPRESA = "+(57)3175752935";
	const WHATSAPP = "+57 3175752935";
	const EMAIL_EMPRESA = "pipeshoez@gmail.com";
	const EMAIL_PEDIDOS = "pipeshoez@gmail.com"; 
	const EMAIL_SUSCRIPCION = "pipeshoez@gmail.com";
	const EMAIL_CONTACTO = "pipeshoez@gmail.com";

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6,7";
	const CAT_FOOTER = "1,2,3,4,5,6,7";

	//Datos para Encriptar / Desencriptar
	const KEY = 'abelosh';
	const METHODENCRIPT = "AES-128-ECB";

	//Envío
	const COSTOENVIO = 0;

	//Módulos
	const MDASHBOARD = 1;
	const MUSUARIOS = 2;
	const MCLIENTES = 3;
	const MPRODUCTOS = 4;
	const MPEDIDOS = 5;
	const MCATEGORIAS = 6;
	const MSUSCRIPTORES = 7;
	const MDCONTACTOS = 8;
	const MDPAGINAS = 9;

	//Páginas
	const PINICIO = 1;
	const PTIENDA = 2;
	const PCARRITO = 3;
	const PNOSOTROS = 4;
	const PCONTACTO = 5;
	const PPREGUNTAS = 6;
	const PTERMINOS = 7;
	const PSUCURSALES = 8;
	const PERROR = 9;

	//Roles
	const RADMINISTRADOR = 1;
	const RSUPERVISOR = 2;
	const RCLIENTES = 3;

	const STATUS = array('Completo','Aprobado','Cancelado','Reembolsado','Pendiente','Entregado');

	//Productos por página
	const CANTPORDHOME = 4;
	const PROPORPAGINA = 8;
	const PROCATEGORIA = 8;
	const PRODSUBCATEGORIA = 8;
	const PROBUSCAR = 8;

	//REDES SOCIALES
	const FACEBOOK = "https://www.facebook.com/profile.php?viewas=100000686899395&id=61566773018702";
	const INSTAGRAM = "https://www.instagram.com/pipe.shoez/";
	

 ?>