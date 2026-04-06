// Ініціалізація планувальника
var dp = new DayPilot.Scheduler("dp");
dp.startDate = DayPilot.Date.today().firstDayOfMonth();
dp.days = 100;
dp.scale = "Day";
dp.timeHeaders = [
{ groupBy: "Month", format: "MMMM yyyy" },
{ groupBy: "Day", format: "d" }
];

dp.rowHeaderColumns = [
    {title: "Room", width: 80},
    {title: "Capacity", width: 80},
    {title: "Status", width: 80}
];

dp.onBeforeResHeaderRender = function(args) {
    var beds = function(count) {
        return count + " bed" + (count > 1 ? "s" : "");
    };
    
    args.resource.columns[1].html = beds(args.resource.capacity);
    args.resource.columns[2].html = args.resource.status;
    
    switch (args.resource.status) {
        case "Dirty":
            args.resource.cssClass = "status_dirty";
            break;
        case "Cleanup":
            args.resource.cssClass = "status_cleanup";
            break;
    }
};

// Відкриття модального вікна для НОВОГО бронювання
dp.onTimeRangeSelected = function (args) {
    var modal = new DayPilot.Modal();
    modal.closed = function() {
        dp.clearSelection();
        var data = this.result;
        if (data && data.result === "OK") {
            loadEvents();
        }
    };
    modal.showUrl("new.php?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);
};

// Відкриття модального вікна для РЕДАГУВАННЯ
dp.onEventClick = function(args) {
    var modal = new DayPilot.Modal();
    modal.closed = function() {
        var data = this.result;
        if (data && data.result === "OK") {
            loadEvents();
        }
    };
    modal.showUrl("edit.php?id=" + args.e.id());
};

dp.init();

function loadResources() {
    fetch("backend_rooms.php", { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            dp.resources = data;
            dp.update();
        })
        .catch(error => console.error("Помилка завантаження кімнат:", error));
}

function loadEvents() {
    var start = dp.visibleStart();
    var end = dp.visibleEnd();

    var formData = new FormData();
    formData.append("start", start.toString());
    formData.append("end", end.toString());

    fetch("backend_events.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        dp.events.list = data;
        dp.update();
    })
    .catch(error => console.error("Помилка завантаження бронювань:", error));
}

loadResources();
loadEvents();