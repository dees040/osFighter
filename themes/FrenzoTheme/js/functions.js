var seconds;

var init = {
    timer: function() {
        seconds = parseInt($('.timer').text());
    }
}

var functions = {
    countDown: function() {
        seconds -= 1;
        $('.timer').text(seconds);
    }
}


$(document).ready(function() {
    init.timer();
    setInterval(functions.countDown, 1000);
});