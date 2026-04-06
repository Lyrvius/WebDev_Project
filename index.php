<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Room Booking in Hotel</title>
    <script src="js/daypilot-all.min.js" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body>
    <main>
        <div style="margin-bottom: 15px; display: flex; gap: 15px; align-items: center; padding: 10px; background-color: #f9f9f9; border-radius: 5px; border: 1px solid #ccc;">
            <div>
                <label for="filter" style="font-weight: bold;">Room filter:</label>
                <select id="filter" style="padding: 5px;">
                    <option value="0">All</option>
                    <option value="1">Single</option>
                    <option value="2">Double</option>
                    <option value="3">Triple</option>
                    <option value="4">Family</option>
                </select>
            </div>
            
            <button id="btn-add-room" style="padding: 6px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer;">
                Add Room
            </button>
        </div>

        <div style="width:100%; float:left;">
            <div id="dp"></div>
        </div>
    </main>

    <div class="clear"></div>
    <footer>
        <address>(с)Автор лабораторної роботи: Дударенко Нікіта Іванович, спеціальності ІПЗ, ПЗІС-25003м</address>
    </footer>

    <script src="js/calendar_setup.js" type="text/javascript"></script>
</body>
</html>