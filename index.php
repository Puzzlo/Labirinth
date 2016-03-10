<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>firebird db</title>
    <link rel="stylesheet"  href="style.css"  type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Cousine:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <script type="text/javascript" src ="jquery-1.11.1.min.js" ></script>
    <script type="text/javascript" src ="jquery-ui.js" ></script>
    <script type="text/javascript" src ="datepicker-ru.js" ></script>
    <script type="text/javascript" src ="bootstrap.js"></script>
    <script type="text/javascript" src ="script.js" ></script>
</head>
<body>
	<table id=HeaderTable>
	<tr>
	<td id=lefttd>
    <form id=formleft >
           
        <h2>Выберите день или интервал</h2>
        <div class="group">
            <input type="radio" name="rating" id="rating1" value="1" checked />
            <label for="rating1">Сегодня</label>

            <input type="radio" name="rating" id="rating2" value="2" />
            <label for="rating2">Несегодня</label>

            <input  type="radio" name="rating" id="rating3" value="3" />
            <label for="rating3">Интервал</label>

        </div>
    </form>
	</td>
	<td id=middletd>
	<form>
        <label class="dn" id="input_date_today">Дата<input type="text" id="report_date_today"></label>
        <label class="dn" id="input_date_not_today"><h2>Дата</h2> <br><h2><input type="text" id="report_date_not_today"></h2></label>
    <!-- <div id="input_date_from"> -->
        <label class="dn" id="input_date_from">Начало<input type="text" id="report_date"></label>
    <!-- </div> -->
    <!-- <div id="input_date_to"> -->
        <label class="dn" id="input_date_to">Конец<input type="text" id="report_date_to"></label>
    <!-- </div> -->
	</form>
	</td>
	<td>
	<form id=formright>
    <div id="sum_walking">
        <!-- количество гуляющих за выбранных период, формируется в script.js -->
    </div>
    <div id="sum_gamers">
        <!-- количество игроков за выбранный период, формируется в script.js -->
    </div>
	</form>
	</td>
	</tr>
	</table>
    <div id="table_walking">
        <!-- здесь таблица прогулок. формируется из пхп  -->
    </div>
    <div id="table_game">
        <!-- здесь таблица игр. формируется из пхп  -->
    </div>
    <div id="bordering"></div>
    <div id="iter_walking">
        <!-- табличка для гостей, прошедших в одну минуту с одной карточки -->
    </div>
    <div id="iter_gamer_and_err">
        <!-- таблица для игроков плюс ошибки (турникет закрыт, ошибка контроля доступа и т.д.) -->
    </div>
    <div id="bordering"></div>
    <div class="send_report">
        <button type="button" id="btn_report">Послать отчёт</button>
        <input type="date" id="date_report">
    </div>
</body>
</html>