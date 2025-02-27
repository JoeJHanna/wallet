function login(username, password) {
    axios.post('http://localhost/LoginApi.php', {
        email: username,
        password: password
    }).then(function(response){
        console.debug(response)
    })
        .catch(function(error) {
            console.error(error)
        })
}