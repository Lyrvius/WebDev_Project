<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Reservation</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            div { margin-bottom: 10px; }
            input[type="text"], select { width: 100%; padding: 5px; box-sizing: border-box; }
            .buttons { margin-top: 20px; }
            button { padding: 5px 15px; cursor: pointer; }
            .cancel-btn { background-color: #f44336; color: white; border: none; padding: 6px 15px; text-decoration: none; cursor: pointer;}
            .save-btn { background-color: #4CAF50; color: white; border: none; padding: 6px 15px; cursor: pointer;}
        </style>
    </head>
    <body>
        <?php
            require_once '_db.php';
            
            $rooms = $db->query('SELECT * FROM rooms');
            
            $start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d\TH:i:s');
            $end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d\TH:i:s', strtotime('+1 day'));
        ?>
        <form id="f" action="backend_create.php" method="POST">
            <h1>New Reservation</h1>
            <div>Name: </div>
            <div><input type="text" id="name" name="name" value="" required /></div>
            
            <div>Start:</div>
            <div><input type="text" id="start" name="start" value="<?php echo htmlspecialchars($start); ?>" /></div>
            
            <div>End:</div>
            <div><input type="text" id="end" name="end" value="<?php echo htmlspecialchars($end); ?>" /></div>
            
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
            
            <div class="buttons">
                <button type="submit" class="save-btn">Save</button> 
                <button type="button" class="cancel-btn" onclick="closeModal();">Cancel</button>
            </div>
        </form>

        <script type="text/javascript">
            function closeModal(result) {
                if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
                    parent.DayPilot.ModalStatic.close(result);
                }
            }

            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("name").focus();

                var form = document.getElementById("f");
                form.addEventListener("submit", function (event) {
                    event.preventDefault();

                    var formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        closeModal(result);
                    })
                    .catch(error => console.error("Помилка відправки:", error));
                });
            });
        </script>
    </body>
</html>