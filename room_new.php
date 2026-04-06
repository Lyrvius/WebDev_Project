<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Room</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            div { margin-bottom: 10px; }
            input[type="text"], select, input[type="number"] { width: 100%; padding: 5px; box-sizing: border-box; }
            .buttons { margin-top: 20px; }
            button { padding: 6px 15px; cursor: pointer; border: none; color: white;}
            .save-btn { background-color: #4CAF50; }
            .cancel-btn { background-color: #f44336; }
        </style>
    </head>
    <body>
        <form id="f" action="backend_room_create.php" method="POST">
            <h1>New Room</h1>
            <div>Room Name: </div>
            <div><input type="text" id="name" name="name" required /></div>

            <div>Capacity:</div>
            <div>
                <select id="capacity" name="capacity">
                    <option value="1">1</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            
            <div>Status:</div>
            <div>
                <select id="status" name="status">
                    <option value="Ready">Ready</option>
                    <option value="Cleanup">Cleanup</option>
                    <option value="Dirty">Dirty</option>
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
                    fetch(form.action, { method: 'POST', body: new FormData(form) })
                    .then(response => response.json())
                    .then(result => closeModal(result))
                    .catch(error => console.error("Error:", error));
                });
            });
        </script>
    </body>
</html>