<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Reservation</title>
        <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
            require_once '_db.php';
            
            $rooms = $db->query('SELECT * FROM rooms');
            
            $start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d\TH:i:s');
            $end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d\TH:i:s', strtotime('+1 day'));
        ?>
        <form id="f" action="backend_create.php" method="POST" style="padding:20px;">
            <h1>New Reservation</h1>
            <div>Name: </div>
            <div><input type="text" id="name" name="name" value="" required /></div>
            <div>Start:</div>
            <div><input type="text" id="start" name="start" value="<?php echo $start ?>" /></div>
            <div>End:</div>
            <div><input type="text" id="end" name="end" value="<?php echo $end ?>" /></div>
            <div>Room:</div>
            <div>
                <select id="room" name="room">
                    <?php 
                        foreach ($rooms as $room) {
                            $selected = (isset($_GET['resource']) && $_GET['resource'] == $room['id']) ? ' selected="selected"' : '';
                            $id = $room['id'];
                            $name = $room['name'];
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            <div style="margin-top: 15px;"><input type="submit" value="Save" /> <a href="javascript:close();">Cancel</a></div>
        </form>
    </body>
</html>