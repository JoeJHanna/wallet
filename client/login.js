function login(username, password) {
    axios.post('http://localhost/server/api/v1/Login.php', {
        email: username, password: password
    }).then(function (response) {
        console.log(response.data)
        axios.get('http://localhost/server/api/Admin/v1/Dashboard.php', {
            withCredentials: true
        }).then(function (test) {
            document.cookie = 'access_token=' + response['data'].data[0] + ';' + "SameSite=Lax;path=/"
            console.log(test)

        }).catch(function (error) {
            console.error(error)
        })
    })
        .catch(function (error) {
            console.error(error)
        })
}