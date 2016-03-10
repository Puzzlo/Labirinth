<?php
	require_once('def.php');

	function connect_to_db() {
		$dbfirebird = ibase_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_CODE);
		return $dbfirebird;
	};

	function request_to_logtab($db, $date_first, $date_last) {

		$query = 'SELECT DT, POBJ_TEXT, EV_TEXT, CLI_TEXT FROM LOGTAB';
		$query .= " WHERE DT >='". date_format(date_create($date_first), 'd.m.Y'). "'";
		$query .= " AND DT <'". date_format(date_modify(date_create($date_last), '+1 day'), 'd.m.Y'). "'";

// echo '</br>query = '. $query. '</br>';
		$res = ibase_query($db, $query);

		return $res;
	};

	/**********************************/
	function pre($a) {
		echo ('<pre>');
		print_r($a);
		echo ('</pre>');
	}
	/**********************************/

?>