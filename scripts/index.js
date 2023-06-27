let file, data, jsonData;
let start = 0;
let paginate = 10;
let is_logged_in = 0;

// void method fetches required file's part of content 
function getFileContent() {
    if (!is_logged_in) {
        window.location.href = 'login.html';
    }
    file = $('#file').val();
    $.get("classes/Main.php?file=" + file + "&start=" + start + "&paginate=" + paginate, function(response, status) {
        jsonData = JSON.parse(response);
        if (jsonData.code == 200) {
            data = jsonData.data;
            $('#response').removeClass('text-danger');
            $('#response').html('');
            for (let i = 0; i < data.length; i++) {
                $('#response').append('<p class="index-p font-weight-bold">' + data[i] + '</p>');
            }

            // reset data start 
            $('.previous').attr('data-start', jsonData.previous)
            $('.next').attr('data-start', jsonData.next)
            $('.end').attr('data-start', jsonData.end)

            let previous_zindex = jsonData.previous == 0 ? -2 : 1;
            let next_zindex = jsonData.next == jsonData.total ? -2 : 1;

            $('.previous').css('z-index', previous_zindex);
            $('.start').css('z-index', previous_zindex);
            $('.next').css('z-index', next_zindex);
            $('.end').css('z-index', next_zindex);
        } else {
            $('#response').addClass('text-danger');
            $('#response').html(jsonData.message);
        }
    });
}

$('.paginate').click(function() {
    start = $(this).attr('data-start');
    getFileContent();
});

$('form').submit(function(event) {
    event.preventDefault();
    start = 0;
    getFileContent();
});

checkLogin();

function checkLogin() {
    $.get("Services/checkLogin.php",
        function(response) {
            is_logged_in = response;
            if (response == 0) {
                window.location.href = 'login.html';
            }
        });
}