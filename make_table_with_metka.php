<?php
	require_once('functions.php');

	$db = connect_to_db();
	$sth = request_to_logtab($db, $_GET['begin_date'], $_GET['end_date']);
	$response = array();
	$response[1] = '<table>';
	$response[2] = $response[3] =  0;  // amount walking and gamers
	$response[4] = $response[5] = '';
	$curr_guest = '';  // for forming table iter_walking need remember last guest and his time
	$curr_guest_time = 0;
	$curr_guest_array = array();

	$curr_gamer = 0; // for forming table iter_gamer need remember last guest and his time
	$curr_gamer_time = 0;
	$curr_gamer_array = array();
	$gamer_report_array = array();
	$filename = "report.txt";


	$tyrn = array();

	$k = 0 ; // number turniketov

	while ($row = ibase_fetch_object($sth)) {

	    $guest = iconv("Windows-1251", "UTF-8",$row->CLI_TEXT);
	    // $guest = $row->CLI_TEXT;
	    $card_reader = iconv("Windows-1251", "UTF-8",$row->POBJ_TEXT);
	    // $card_reader = $row->POBJ_TEXT;


	    if ($card_reader=='Турникет'){
	    	if(strcmp(substr($row->CLI_TEXT, 0, 5), iconv("UTF-8", "Windows-1251",'гость')) !== 0 ) {
		    	if(  (  (int)(preg_replace("/[^0-9]/", '', $row->CLI_TEXT)) - $curr_gamer)!==0 ) {
		    		$response[2]++;
		    		$curr_gamer = (int)(preg_replace("/[^0-9]/", '', $row->CLI_TEXT));
		    		$curr_gamer_time = strtotime($row->DT);
		    		$curr_gamer_array[gamer][] = $row->CLI_TEXT;
		    		$curr_gamer_array[time][] = strtotime($row->DT);
			    	$time_input = substr($row->DT, -8);
			    	$tyrn[$row->CLI_TEXT] = array(0=>preg_replace("/[^0-9]/", '', $row->CLI_TEXT), 1=>$time_input);  // add client to game array
				}
			}

		}
		elseif (!strcmp($card_reader ,'картоприемник')){
			if( $curr_guest_time == 0 ) { 
				$curr_guest = $row->CLI_TEXT;
				$curr_guest_time = strtotime($row->DT);
				//echo 'first passenger = '.$curr_guest. '<br>';
				  // первоначальное присваивание
			}
			if( !strcmp($row->CLI_TEXT,$curr_guest) && strtotime($row->DT) == $curr_guest_time ) {
				$curr_guest_array[guest][] = $curr_guest;
				$curr_guest_array[time][] = $curr_guest_time;
			}

				    	// echo $row->CLI_TEXT. ' '. $curr_gamer. strtotime($row->DT). ' '.  $curr_gamer_time. '<br>';

			if( (strcmp($row->CLI_TEXT,$curr_guest)!=0) || ( (strcmp($row->CLI_TEXT,$curr_guest)==0) && (strtotime($row->DT) - $curr_guest_time > 100) )   ){
				//|| ( && ((strtotime($row->DT) - $curr_guest_time) < 100)) { 
		
			// либо не совпадаюст посетители, либо совпадают, но тогда время больше минуты : обрабатываем, иначе повторное срабатывание		 	
				$curr_guest = $row->CLI_TEXT;
				$curr_guest_time = strtotime($row->DT);


				$response[3]++;

				$response[1] .= '<tr>';
				$response[1] .= '<td>'. substr(substr($row->DT, -8), 0, 5) . '</td>';
				// $response[1] .= '<td>'. iconv("Windows-1251", "UTF-8",$row->EV_TEXT) . '</td>';   // "вход с ключом" - не нужно оно в таблице
				$response[1] .= '<td>'. '   '. iconv("Windows-1251", "UTF-8",$row->CLI_TEXT) . '</td>';
				$response[1] .= '</tr>';
			}
		}

		elseif ( !strcmp(substr($row->POBJ_TEXT, 0, 5), iconv("UTF-8", "Windows-1251",'метка') )  ) {

	    	$time_input = substr($row->DT, -8);
			if (in_array($row->CLI_TEXT, $tyrn)) {
				exit('не заходил такой персонаж'. $guest. ', откуда он взялся на '. $row->POBJ_TEXT. '???');
			} else {
				if(in_array(substr($card_reader, -1), $tyrn[$row->CLI_TEXT]))
					continue;
				else 
					$tyrn[$row->CLI_TEXT][substr($card_reader, -1)] = $time_input;
			}
			

		}

		// elseif (!strcmp($card_reader ,'Запрет входа')) {
		// 	exit('reject');
		// }
		else {
			$er = 'reject '. $row->POBJ_TEXT;

			$curr_gamer_array[gamer][] = $curr_gamer;
	    	$curr_gamer_array[time][] = $curr_gamer_time;
	    	$curr_gamer = $row->POBJ_TEXT;
	    	$curr_gamer_time = strtotime($row->DT);


			// echo $er;
			continue;
			exit($er);
			// continue;
			exit('не метка, не картоприемник, не турникет. я не знаю, что делать. i dont know what me make');
		}


	} // end while , end quest
	// echo $response[2];
	// echo count($tyrn);
	// pre($tyrn);

	//forming table with gamers and for report
	$count_gamer = 0;
	$response[0] = '<table>';
	foreach ($tyrn as $gue) {
		$response[0] .= "<table class='gamer'>";
		$response[0] .= '<tr><td width=20%>Игрок #'. $gue[0]. '</td>';
		$gamer_report_array[$count_gamer] = "Игрок #". $gue[0];
		
		array_shift($gue);
		for($i=0; $i <=4;$i++){
			if(array_key_exists($i, $gue)) {
				// echo 'i='. $i. '  gue='. $gue[$i]. '<br>';
				$response[0] .= "<td width=10%>". substr($gue[$i], 0, 5). '</td>';
				$gue[$i] = strtotime($gue[$i]);
			}
		}
		//$response[0] .= '</tr>';
		//$response[0] .= "<tr>";
		if(((max($gue)-min($gue))/60)>20) $response[0] .= "<td align='right' class = 'redcolor'>";
		else $response[0] .= "<td align='right' class = 'bluecolor'>";
		$response[0] .= 'время : '. ((max($gue)-min($gue))/60). ' мин.</td>';
		$gamer_report_array[$count_gamer] .= '   '. 'время : '. ((max($gue)-min($gue))/60). ' мин.';
		$count_gamer++;
		$response[0] .= '</tr></table>';

	}
	$response[0] .= '</table>';


	$response[1] .= '</table>';

	//form a double pass of the table and errors
	$response[4] = '<table>';
	$response[4] .= '<tr><td> Повторные срабатывания за выбраный период и прочее'. '</td></tr>';
	// foreach ($curr_gamer_array as $value) {
	for($i=0;$i<count($curr_gamer_array[gamer]); $i++) {
		$response[4] .= '<tr><td>'. iconv("Windows-1251", "UTF-8",$curr_gamer_array[gamer][$i])
						. '</td><td>  '
						. date('d.m.y H:i', $curr_gamer_array[time][$i])
						. '</td></tr>';
	}
	$response[4] .= '</table>';


	//form a double pass of walking peoples
	$response[5] = '<table>';
	for($i=0;$i<count($curr_guest_array[guest]); $i++) {
		$response[5] 	.= '<tr><td width=50%> Гость_'. preg_replace("/[^0-9]/", '',$curr_guest_array[guest][$i])
						. '</td><td>'
						. date('d.m.y H:i', $curr_guest_array[time][$i])
						. '</td></tr>';
	}
	$response[5] .= '</table>';

	if($_GET['report']) {
		// сформировать из результата отчёт и отослать по указанным адресам
		// pre($curr_gamer_array);
		$f = fopen($filename, "w");
		fclose($f);
		$f = fopen($filename, "a");
		// сначала гости. все, включая повторные срабатывания в пределах одной минуты
		$str = "ОТЧЁТ ЗА ". $_GET['begin_date']. "\r\n\r\n";
		fputs($f, $str);
		for($i=0;$i<count($curr_guest_array[guest]); $i++) {
			$str = 'Гость_'. preg_replace("/[^0-9]/", '',$curr_guest_array[guest][$i]). ' в '. date('H:i', $curr_guest_array[time][$i]). "\r\n";
			fputs($f, $str);
		}
		$str = "Итого, исключая повторные срабатывания : ". $response[3]. "\r\n\r\n";
		fputs($f, $str);
		$str = "Игроки за отчётный день : ". "\r\n";
		fputs($f, $str);
		for($i=0; $i<count($gamer_report_array); $i++){
			fputs($f, $gamer_report_array[$i]. "\r\n");
		}
		$str = "Итого игроков : ". $response[2]. "\r\n\r\n";
		fputs($f, $str);


		fclose($f);

		mail("p_uzo@mail.ru", "Andrey", "message");

	}



	ibase_free_result($sth);
	ibase_close($db);
	// echo($response[4]);
	$response = json_encode($response);
	echo $response;
	// echo $curr_gamer_array[gamer][1];
	// pre($curr_gamer_array);
?>