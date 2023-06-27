var checkbox = document.getElementById("see");
var passField = document.getElementById("pass");
checkbox.addEventListener("click", function() {
    var value = passField.getAttribute("type");
    if (value == "password") {
        passField.setAttribute("type", "text");
    } else {
        passField.setAttribute("type", "password");
    }
});

$('form').submit(function(event) {
    event.preventDefault();
    start = 0;
    login();
});

checkLogin();

function checkLogin() {
    $.get("Services/checkLogin.php",
        function(response) {
            if (response == 1) {
                window.location.href = 'index.html';
            }
        });
}

function login() {
    let user_name = $('#username').val();
    let password = $('#pass').val();
    let jsonData, message;
    $.post("Services/Login.php", {
            user_name: user_name,
            password: password
        },
        function(response, status) {
            jsonData = JSON.parse(response);
            message = jsonData.message;
            console.log('status : ' + status);
            console.log('message : ' + message);
            console.log('jsonData.status : ' + jsonData.status);
            if (status == 'success') {
                $('#message').removeClass('text-danger').addClass('text-success').html(message);
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 1000);
            } else {
                $('#message').addClass('text-danger').removeClass('text-success').html(message);
            }
        });
}