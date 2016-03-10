<?php
	require_once('functions.php');
	// $host = 'localhost:E:/OpenServer/domains/remot/T.GDB';

	// $dbh = ibase_connect($host, 'SYSDBA', 'masterkey');
	// $stmt = 'SELECT * FROM PERS';

	$db = connect_to_db();
	$sth = request_to_logtab($db, $_GET['begin_date'], $_GET['end_date']);
	$response[0] = '<table>';
	$response[1] = '<table id='rightvision'>';
	while ($row = ibase_fetch_object($sth)) {
	    // echo $row->name. "1\n";
	    if (iconv("Windows-1251", "UTF-8",$row->POBJ_TEXT)=='Турникет'){
	    	$response[0] .= '<tr>';
			$response[0] .= '<td>'. $row->DT . '</td>';
			// $response .= '<td>'. $row->EV_TEXT . '</td>';
			$response[0] .= '<td>'. iconv("Windows-1251", "UTF-8",$row->EV_TEXT) . '</td>';
			// $response .= '<td>'. $row->CLI_TEXT . '</td>';
			$response[0] .= '<td>'. iconv("Windows-1251", "UTF-8",$row->CLI_TEXT) . '</td>';
			$response[0] .= '</tr>';
		}
		 if (iconv("Windows-1251", "UTF-8",$row->POBJ_TEXT)=='картоприемник'){
		 	$response[1] .= '<tr>';
			$response[1] .= '<td>'. $row->DT . '</td>';
			// $response .= '<td>'. $row->EV_TEXT . '</td>';
			$response[1] .= '<td>'. iconv("Windows-1251", "UTF-8",$row->EV_TEXT) . '</td>';
			// $response .= '<td>'. $row->CLI_TEXT . '</td>';
			$response[1] .= '<td>'. iconv("Windows-1251", "UTF-8",$row->CLI_TEXT) . '</td>';
			$response[1] .= '</tr>';
		 }
	}
	$response[0] .= '</table>';
	$response[1] .= '</table>';
	ibase_free_result($sth);
	ibase_close($db);
	$response = json_encode($response);
	echo $response;
?>