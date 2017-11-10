var running = false;

function DoNext(NS) {
    "use strict";

    var url = '';

    if (running) {
        var request = $.ajax({
            type: "POST",
            url: url,
            data: NS
        });

        request.done(function (result) {
            $('#import-result-container').html(result);
        });
    }

}

StartImport = function () {
    running = document.getElementById('start-button').disabled = true;
    DoNext();
};

EndImport = function () {
    running = document.getElementById('start-button').disabled = false;
};