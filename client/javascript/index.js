const baseUrl = "https://ec2-13-38-64-121.eu-west-3.compute.amazonaws.com/"
// const baseUrl = "http://localhost/"
let loginButton = document.getElementById("login-button");
// References: https://stackoverflow.com/questions/201323/how-can-i-validate-an-email-address-using-a-regular-expression

function callLogin(username, password) {
    axios.post(baseUrl + 'server/api/v1/Login.php', {
        email: username, password: password
    }).then(function (response) {
        console.debug(response.data)
        document.cookie = 'access_token=' + response['data'].data[0] + ';' + "SameSite=Lax;path=/"
        window.location.href = 'Admin/v1/Dashboard.php'
    }).catch(function (error) {
        console.error(error)
    })
}

function isPasswordValid(password) {
    const RegexPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,15}$");
    if (!password) {
        window.alert("Password cannot be empty!")
        return false;
    }
    if (!RegexPassword.test(password)) {
        window.alert("Password must include at least: one lowercase character, one uppercase character, one digit, and one special symbol!")
        return false;
    }
    return true;
}

function isEmailValid(email) {
    const RegexEmail = new RegExp("(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\\])");
    if (!email) {
        window.alert("Email cannot be empty!")
        return false;
    }
    if (!RegexEmail.test(email)) {
        window.alert("Invalid email!")
        return false;
    }
    return true;
}
function getEmail() {
    let emailInput = document.getElementById("form-email");
    return emailInput.value;
}

function getPassword() {
    let passwordInput = document.getElementById("form-password");
    return passwordInput.value
}


loginButton.addEventListener('click',
    function (event) {
        event.preventDefault();
        let email = getEmail();
        let password = getPassword();
        if (!isEmailValid(email) || !isPasswordValid(password)) {
            return;
        }
        callLogin(email, password);
    });