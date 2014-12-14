var countDownInterval;
var seconds;

var init = {
    timer: function() {
        seconds = parseInt($('.timer').text());
    },
    change: function() {
        $('.crime-change-output').text($('.input-change').val());
    }
};

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
    },
    multipleCountdown: function(_class) {
        var seconds = _class.text();

        var interval = setInterval(function() {
            if (seconds != 0) {
                seconds--;
                _class.text(seconds);
                _class.parent().parent().find(".cost_td").find(".costs").text(seconds * 180);
            } else {
                clearInterval(interval);
            }
        }, 1000);
    },
    crackTheVault: function(e, _object) {
        var searchText = $.trim($(_object).val());

        var c= String.fromCharCode(e.keyCode);
        var isWordCharacter = c.match(/\w/);
        var isBackspaceOrDelete = (e.keyCode == 8 || e.keyCode == 46);

        // trigger only on word characters, backspace or delete and an entry size of at least 3 characters
        if((isWordCharacter || isBackspaceOrDelete)) {
            var inputs = $(_object).closest('form').find('.crack_the_vault');
            inputs.eq(inputs.index(_object) + 1).focus();
        }
    },
    selectMessages: function(e, _object){
        var selected = $(_object).prop('checked');

        if (selected == true) {
            $('.checkbox-message').prop("checked", true);
        } else {
            $('.checkbox-message').prop("checked", false);
        }
    }
};

tinymce.init({
    selector: "textarea",
    plugins: ["image", "emoticons", "textcolor", "link"],
    toolbar: [
        "undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright | forecolor backcolor",
        "emoticons image | link"
    ],
    target_list: [
        {title: 'New page', value: '_blank'}
    ]
});

$(document).ready(function() {
    init.timer();
    countDownInterval = setInterval(functions.countDown, 1000);

    $('.input-change').change(init.change);
    $('.reload').click(functions.reload);
    $('.multiple-timer').each(function() {
        functions.multipleCountdown($(this));
    });

    $(".crack_the_vault").keyup(function(e) {
        functions.crackTheVault(e, this);
    });

    $(".delete-messages-all").click(function(e) {
        functions.selectMessages(e, this);
    });
});