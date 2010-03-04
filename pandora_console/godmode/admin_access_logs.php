<?php

// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2009 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.


global $config;

if ($config['flash_charts']) {
	require_once ("include/fgraph.php");
}

check_login ();

if (! give_acl ($config['id_user'], 0, "PM")) {
	audit_db($config['id_user'], $REMOTE_ADDR, "ACL Violation",
		"Trying to access event viewer");
	require ("general/noaccess.php");
	exit;
}

print_page_header (__('Pandora audit')." &raquo; ".__('Review Logs'), "", false, "", true );

$offset = get_parameter ("offset", 0);
$tipo_log = get_parameter ("tipo_log", 'all');

echo '<table width=100% border=0>';
echo "<tr><td colspan=2>";
echo '<b>'.__('Filter').'</b>';
echo "<td rowspan=2>";
if ($config['flash_charts']) {
	echo graphic_user_activity (300, 140);
} else {
	echo '<img src="include/fgraph.php?tipo=user_activity&width=300&height=140" />';
}

$rows = get_db_all_rows_sql ("SELECT DISTINCT(accion) FROM tsesion");
if (empty ($rows)) {
	$rows = array ();
}

$actions = array ();

foreach ($rows as $row) {
	$actions[$row["accion"]] = $row["accion"]; 
}

echo "</td></td></tr>";
echo "<tr><td>";
echo '<form name="query_sel" method="post" action="index.php?sec=godmode&sec2=godmode/admin_access_logs">';
echo __('Action').': ';
echo "</td><td>";
print_select ($actions, 'tipo_log', $tipo_log, 'this.form.submit();', __('All'), 'all');
echo '<br /><noscript><input name="uptbutton" type="submit" class="sub" value="'.__('Show').'"></noscript>';
echo '</form>';


echo "</td></td></tr>";

$filter = '';
if ($tipo_log != 'all') {
	$filter = sprintf (" WHERE accion = '%s'", $tipo_log);
}

$sql = "SELECT COUNT(*) FROM tsesion ".$filter;
$count = get_db_sql ($sql);
$url = "index.php?sec=godmode&sec2=godmode/admin_access_logs&tipo_log=".$tipo_log;


echo "<tr><td colspan=3>";
pagination ($count, $url);
echo "</td></td></tr></table>";

$sql = sprintf ("SELECT * FROM tsesion%s ORDER BY fecha DESC LIMIT %d, %d", $filter, $offset, $config["block_size"]);
$result = get_db_all_rows_sql ($sql);

if (empty ($result)) {
	$result = array ();
}

$table->cellpadding = 4;
$table->cellspacing = 4;
$table->width = 750;
$table->class = "databox";
$table->size = array ();
$table->data = array ();
$table->head = array ();

$table->head[0] = __('User');
$table->head[1] = __('Action');
$table->head[2] = __('Date');
$table->head[3] = __('Source IP');
$table->head[4] = __('Comments');

$table->size[0] = 80;
$table->size[2] = 130;
$table->size[3] = 100;
$table->size[4] = 200;

$rowPair = true;
$iterator = 0;

// Get data
foreach ($result as $row) {
	if ($rowPair)
		$table->rowclass[$iterator] = 'rowPair';
	else
		$table->rowclass[$iterator] = 'rowOdd';
	$rowPair = !$rowPair;
	$iterator++;

	$data = array ();
	$data[0] = $row["ID_usuario"];
	$data[1] = $row["accion"];
	$data[2] = $row["fecha"];
	$data[3] = $row["IP_origen"];
	$data[4] = $row["descripcion"];
	array_push ($table->data, $data);
}

print_table ($table);

?>
