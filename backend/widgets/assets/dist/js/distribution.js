var running = false;

function DoNext(NS) {
    "use strict";

    if (running) {
        var request = $.ajax({
            type: "POST",
            url: '',
            data: NS
        });

        request.done(function (result) {
            $('#distribution-result-container').html(result);
        });
    }

}

StartDistribution = function () {
    running = document.getElementById('start-button').disabled = true;
    DoNext();
};

EndDistribution = function () {
    running = document.getElementById('start-button').disabled = false;
};