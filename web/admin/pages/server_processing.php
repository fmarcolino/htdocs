<?php
require_once 'usuario.php';
require_once 'sessao.php';
require_once 'autenticador.php';
include_once '../../inc/conexao.php';
$aut = Autenticador::instanciar();

$usuario = null;
if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
	$area = $usuario->getArea();
}
else {
    $aut->expulsar();
}


/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'trabalhos_cientificos';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id', 'dt' => 'id1' ),
    array( 'db' => 'titulo', 'dt' => 'titulo' ),
    array( 'db' => 'area', 'dt' => 'area' ),
    array( 'db' => 'pet',  'dt' => 'pet' ),
    array( 'db' => 'estado',   'dt' => 'estado' ),
    array(
        'db'        => 'aprovado',
        'dt'        => 'aprovado',
        'formatter' => function( $d, $row ) {
		
			switch($d)
			{
				case 0:
				//	Trabalho enviado
					return 'Não avaliado';
				break;
				case 1:
				//trabalho aprovado
					return 'Aprovado';
				break;
				case 2:
				//trabalho aprovado com restrições
					return 'Aprovado com restrições';
				break;
				case 3:
				//Esperando uma nova avaliação
					return 'Não avaliado, 2';
				break;
				case 4:
				//Esperando uma nova avaliação
					return 'Não aprovado';
				break;
			
			}
        }
    ),
    array(
        'db'        => 'link',
        'dt'        => 'link',
        'formatter' => function( $d, $row ) {
		
		
			$msg = '';
			$msg .= '<div class="dropdown show">
				  ';
				  
				$itens = json_decode($d, true);

				if ($itens !== false) {

					foreach ($itens as $key=>$item) {
						$urlArray = explode('_', $item);
						$cpf = $urlArray[1];
						$msg .= '<a class="dropdown-item" href="SubmissaoTrabalhos/'.$cpf.'/'.$item.'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.$key.'</a> <br />';
					}
				}else{
				
					$msg .= '<a class="dropdown-item" href="#">Problema: nenhum trabalho localizado. Falar com ADMIN.</a>';
				
				}
			$msg .= '
				</div>';
            return $msg;
        }
    ),
	
	
    array(
        'db'        => 'id',
        'dt'        => 'id',
        'formatter' => function( $d, $row ) {
		
		
			$msg = '<a href="#avaliacao" class="btn btn-xl" onclick="avaliar(\''.$d.'\')" data-toggle="modal"><i class="fa fa-edit"></i> Avaliar ID #'.$d.'</a>';
            return $msg;
        }
    )
);

// SQL server connection information
$sql_details = array(
    'user' => 'u426573602_root',
    'pass' => '@p0sitiv02014',
    'db'   => 'u426573602_ene',
    'host' => 'u426573602-ene.mysql.uhserver.com'
    // 'user' => 'root',
    // 'pass' => '',
    // 'db'   => 'enepet2017',
    // 'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $area)
);