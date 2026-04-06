<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Reservation</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            div { margin-bottom: 10px; }
            input[type="text"], select { width: 100%; padding: 5px; box-sizing: border-box; }
            .buttons { margin-top: 20px; display: flex; gap: 10px; }
            button { padding: 6px 15px; border: none; color: white; cursor: pointer; }
            .save-btn { background-color: #4CAF50; }
            .cancel-btn { background-color: #777; }
            .delete-btn { background-color: #f44336; margin-left: auto; /* Відсуває кнопку видалення вправо */ }
        </style>
    </head>
    <body>
        <?php
            require_once '_db.php';
            
            $id = $_GET['id'];
            $stmt = $db->prepare('SELECT * FROM reservations WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $reservation = $stmt->fetch();
            
            $rooms = $db->query('SELECT * FROM rooms');
        ?>
        <form id="f" action="backend_update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />
            
            <h1>Edit Reservation</h1>
            <div>Name: </div>
            <div><input type="text" id="name" name="name" value="<?php echo htmlspecialchars($reservation['name']); ?>" required /></div>
            
            <div>Start:</div>
            <div><input type="text" id="start" name="start" value="<?php echo htmlspecialchars($reservation['start']); ?>" /></div>
            
            <div>End:</div>
            <div><input type="text" id="end" name="end" value="<?php echo htmlspecialchars($reservation['end']); ?>" /></div>
            
            <div>Room:</div>
            <div>
                <select id="room" name="room">
                    <?php 
                        foreach ($rooms as $room) {
                            $selected = $reservation['room_id'] == $room['id'] ? ' selected="selected"' : '';
                            $room_id = $room['id'];
                            $name = $room['name'];
                            print "<option value='$room_id' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            
            <div>Status:</div>
            <div>
                <select id="status" name="status">
                    <?php 
                        $options = array("New", "Confirmed", "Arrived", "CheckedOut");
                        foreach ($options as $option) {
                            $selected = $option == $reservation['status'] ? ' selected="selected"' : '';
                            print "<option value='$option' $selected>$option</option>";
                        }
                    ?>
                </select>                
            </div>
            
            <div>Paid:</div>
            <div>
                <select id="paid" name="paid">
                    <?php 
                        $options = array(0, 50, 100);
                        foreach ($options as $option) {
                            $selected = $option == $reservation['paid'] ? ' selected="selected"' : '';
                            $name = $option."%";
                            print "<option value='$option' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            
            <div class="buttons">
                <button type="submit" class="save-btn">Save</button> 
                <button type="button" class="cancel-btn" onclick="closeModal();">Cancel</button>
                
                <button type="button" id="btn-delete" class="delete-btn">Delete</button>
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

                // Оновлення
                var form = document.getElementById("f");
                form.addEventListener("submit", function (event) {
                    event.preventDefault();
                    var formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => closeModal(result))
                    .catch(error => console.error("Error updating:", error));
                });

                // Видалення
                var btnDelete = document.getElementById("btn-delete");
                btnDelete.addEventListener("click", function () {
                    if (confirm("Are you sure you want to delete this reservation?")) {
                        var id = document.querySelector('input[name="id"]').value;
                        var formData = new FormData();
                        formData.append("id", id);

                        fetch("backend_delete.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(result => closeModal(result))
                        .catch(error => console.error("Error deleting:", error));
                    }
                });
            });
        </script>
    </body>
</html>