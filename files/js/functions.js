var countDownInterval;
var seconds;

var init = {
    timer: function() {
        seconds = parseInt($('.timer').text());
    },
    change: function() {
        $('.crime-change-output').text($('.input-change').val());
    }
}

var functions = {
    countDown: function() {
        seconds -= 1;
        $('.timer').text(seconds);
        if (seconds == 0) {
            clearInterval(countDownInterval);
            $('.reload').text("Click here to reload.");
        }
    },
    reload: function() {
        location.reload();
    }
}


$(document).ready(function() {
    init.timer();
    countDownInterval = setInterval(functions.countDown, 1000);

    $('.input-change').change(init.change);
    $('.reload').click(functions.reload);
});