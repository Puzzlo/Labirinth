$( document ).ready(function() {
$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
	var min;
	var promenade_cost = 300;
	var game_cost = 600;
	window.onload = function(){
		$('#rating1').trigger('click');
	}


	$('#rating1').click(function () {
		clear_html();

		$('#input_date_today').css('display', 'block');
		var day = new Date();
		var str = day.getDate() + '.' + (day.getMonth()+1) + '.' + day.getFullYear();
		console.log(str);
		$('#input_date_today').html('Данные на сегодня' + str);

		get_to_php(str, str);

	})
	.focus();




	$('#rating2').click(function () {

		clear_html();


		$('#input_date_not_today').css('display', 'block');

		$('#report_date_not_today').datepicker({
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			onSelect: function(date){
				
				get_to_php(date, date);

			},
		})
		.focus();

	});
	$('#rating3').click(function () {

		clear_html();

		$('#input_date_from').css('display', 'block');
		$('#report_date').datepicker({
			dateFormat: 'dd.mm.yy', 
			onSelect: function(date_from){
				console.log('первой выбрана дата '+ date_from);
				$('#report_date').css('height', '25px');
				min = date_from;
			},
			onClose: function( selectedDate ) {
				$('#input_date_to').css('display', 'block')
        		$( "#report_date_to" ).datepicker( "option", "minDate", selectedDate ).focus();
      		}
		})
		.focus();
		$('#report_date_to').datepicker({
			dateFormat: 'dd.mm.yy',
			onSelect: function(date_to){
				$('#report_date_to').css('height', '25px');
				console.log('второй выбрана дата '+date_to + '    '+ min);

				get_to_php(min, date_to);
			},
		})
	});

	$('#btn_report').click(function(){
		var date = $('#date_report').val();
		var arr = date.split("-");
		var chislo = arr[2] + '.' + arr[1] + '.' + arr[0];
		var query = "make_table_with_metka.php?begin_date="+chislo+"&end_date="+chislo + "&report=1";
		// alert(arr);
		$.get(query, function(result){
			
			}, "json");
	});



	function clear_html() {
		$('#table_walking').html('');
		$('#table_game').html('');
		$('#sum_walking').html('');
		$('#sum_gamers').html('');
		$('#iter_walking').html('');
		$('#iter_gamer_and_err').html('');

		$('#input_date_to').css("display", 'none');
		$('#input_date_from').css("display", 'none');
		$('#input_date_not_today').css('display', 'none');
		$('#input_date_today').css('display', 'none');
	};

	function get_to_php ( from, to ) {
		var query = "make_table_with_metka.php?begin_date="+from+"&end_date="+to;
			$.get(query, function(result){
				$('#table_walking').html(result[1]);
				$('#table_game').html(result[0]);
				$('#sum_walking').html('Проход '+formatStr(result[3])+ ',сумма   '+ formatStr(result[3]*promenade_cost)+' руб.');
				$('#sum_gamers').html('  Игра '+formatStr(result[2])+ ', сумма  '+formatStr(result[2]*game_cost)+' руб.');
				$('#iter_gamer_and_err').html(result[4]);
				$('#iter_walking').html(result[5]);
			}, "json");
	};

	function formatStr(str) {
		str = str.toString().replace(/(\.(.*))/g, '');
		var arr = str.split('');
		var str_temp = '';
		if (str.length > 3) {
			for (var i = arr.length - 1, j = 1; i >= 0; i--, j++) {
				str_temp = arr[i] + str_temp;
				if (j % 3 == 0) {
					str_temp = ' ' + str_temp;
				}
			}
			return str_temp;
		} else {
			return str;
		}
	}

	

})