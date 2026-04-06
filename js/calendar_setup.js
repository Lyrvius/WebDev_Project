var dp = new DayPilot.Scheduler("dp");
dp.startDate = DayPilot.Date.today().firstDayOfMonth();
dp.days = 100;
dp.scale = "Day";
dp.timeHeaders = [
    { groupBy: "Month", format: "MMMM yyyy" },
    { groupBy: "Day", format: "d" }
];
dp.rowHeaderColumns = [
    { title: "Room", width: 80 },
    { title: "Capacity", width: 80 },
    { title: "Status", width: 80 }
];

dp.onBeforeResHeaderRender = function(args) {
    var beds = function(count) { return count + " bed" + (count > 1 ? "s" : ""); };
    
    args.resource.columns[1].html = beds(args.resource.capacity);
    args.resource.columns[2].html = args.resource.status;

    switch (args.resource.status) {
        case "Dirty": args.resource.cssClass = "status_dirty"; break;
        case "Cleanup": args.resource.cssClass = "status_cleanup"; break;
    }
};

// Відкриття модальних вікон
dp.onTimeRangeSelected = function (args) {
    var modal = new DayPilot.Modal();
    modal.closed = function() {
        dp.clearSelection();
        if (this.result && this.result.result === "OK") loadEvents();
    };
    modal.showUrl("new.php?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);
};

dp.onEventClick = function(args) {
    var modal = new DayPilot.Modal();
    modal.closed = function() {
        if (this.result && this.result.result === "OK") loadEvents();
    };
    modal.showUrl("edit.php?id=" + args.e.id());
};

// Заборона накладання бронювань
dp.allowEventOverlap = false;

// Перетягування
dp.onEventMoved = function (args) {
    var formData = new FormData();
    formData.append("id", args.e.id());
    formData.append("newStart", args.newStart.toString());
    formData.append("newEnd", args.newEnd.toString());
    formData.append("newResource", args.newResource);

    fetch("backend_move.php", { method: 'POST', body: formData })
    .then(response => response.json())
    .then(data => {
        dp.message(data.message);
        if(data.result === 'Error') {
            loadEvents();
        }
    });
};

// Відображення статусу бронювання та оплати
dp.onBeforeEventRender = function(args) {
    var start = new DayPilot.Date(args.e.start);
    var end = new DayPilot.Date(args.e.end);
    var today = DayPilot.Date.today();
    var now = new DayPilot.Date();

    args.e.html = args.e.text;

    switch (args.e.status) {
        case "New":
            var in2days = today.addDays(1);
            if (start < in2days) {
                args.e.barColor = 'red'; args.e.toolTip = 'Not confirmed';
            } else {
                args.e.barColor = 'orange'; args.e.toolTip = 'New';
            }
            break;
        case "Confirmed":
            var arrivalDeadline = today.addHours(18);
            if (start < today || (start.getDatePart() === today.getDatePart() && now > arrivalDeadline)) {
                args.e.barColor = "#f41616"; args.e.toolTip = 'Late arrival';
            } else {
                args.e.barColor = "green"; args.e.toolTip = "Confirmed";
            }
            break;
        case 'Arrived':
            var checkoutDeadline = today.addHours(10);
            if (end < today || (end.getDatePart() === today.getDatePart() && now > checkoutDeadline)) {
                args.e.barColor = "#f41616"; args.e.toolTip = "Late checkout";
            } else {
                args.e.barColor = "#1691f4"; args.e.toolTip = "Arrived";
            }
            break;
        case 'CheckedOut':
            args.e.barColor = "gray"; args.e.toolTip = "Checked out";
            break;
        default:
            args.e.toolTip = "Undefined state";
            break;
    }

    args.e.html = args.e.html + "<br /><span style='color:gray'>" + args.e.toolTip + "</span>";

    var paid = args.e.paid;
    var paidColor = "#aaaaaa";
    args.e.areas = [
        { bottom: 10, right: 4, html: "<div style='color:" + paidColor + "; font-size: 8pt;'>Paid: " + paid + "%</div>", v: "Visible"},
        { left: 4, bottom: 8, right: 4, height: 2, html: "<div style='background-color:" + paidColor + "; height: 100%; width:" + paid + "%'></div>", v: "Visible" }
    ];
};

dp.init();

// AJAX
function loadResources() {
    var capacity = document.getElementById("filter").value;
    var formData = new FormData();
    formData.append("capacity", capacity);

    fetch("backend_rooms.php", { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            dp.resources = data;
            dp.update();
        });
}

function loadEvents() {
    var start = dp.visibleStart();
    var end = dp.visibleEnd();
    var formData = new FormData();
    formData.append("start", start.toString());
    formData.append("end", end.toString());

    fetch("backend_events.php", { method: 'POST', body: formData })
    .then(response => response.json())
    .then(data => {
        dp.events.list = data;
        dp.update();
    });
}

document.getElementById("filter").addEventListener("change", function() {
    loadResources();
});

// Додавання кімнати
document.getElementById("btn-add-room").addEventListener("click", function() {
    var modal = new DayPilot.Modal();
    modal.closed = function() {
        if (this.result && this.result.result === "OK") loadResources();
    };
    modal.showUrl("room_new.php");
});

loadResources();
loadEvents();