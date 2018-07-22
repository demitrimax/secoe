<?php

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
$table = 'V_cat_pozos';

// Table's primary key
$primaryKey = 'idpozo';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array( 'db' => 'idpozo', 'dt' => 'idpozo' ),
	array( 'db' => 'nombrepozo', 'dt' => 'nombrepozo' ),
	array( 'db' => 'campo',  'dt' => 'campo' ),
	array( 'db' => 'numero',   'dt' => 'numero' ),
	array( 'db' => 'tipo',     'dt' => 'tipo' ),
	array( 'db' => 'profundidad',     'dt' => 'profundidad'),
	array( 'db' => 'UOP_CORTO',     'dt' => 'UOP_CORTO'),
	
);

// SQL server connection information
$sql_details = array(
	'user' => 'id4491471_secoeuser',
	'pass' => 'secoeweb',
	'db'   => 'id4491471_secoe',
	'host' => 'localhost',
	'charset' => 'utf8'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../DataTables/examples/server_side/scripts/ssp.class.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);

