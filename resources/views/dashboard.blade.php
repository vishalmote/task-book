<!doctype html>
<html>

<head>
    <title>My Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
        function checkLogin() {
            console.log('checkLogin');
            $.ajax({
                url: "{{ route('api.me')}}",
                type: "POST",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token")
                },
                success: function(data) {
                    console.log('checkLogin', data);
                    if (data.status == 'error') {
                        if (data.data.errorList.unauthorized != undefined) {
                            window.location.href = "{{ route('login')}}";
                            return;
                        } else {
                            alert(data.data.errorList[0]);
                        }
                    }
                }
            });
        }
        checkLogin();
    </script>
</head>

<body>
    <div class="col-lg-4 col-md-4">
        Dashboard
    </div>
</body>

</html>