<!doctype html>
<html>

<head>
    <title>My Login Page</title>
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
                    if (data.status == 'success') {
                        window.location.href = "{{ route('dashboard')}}";
                    }
                }
            });
        }
        checkLogin();

        function submitForm() {
            console.log('login_form');
            var all = $("#login_form").serialize();
            $.ajax({
                url: $('#login_form').attr('action'),
                type: "POST",
                data: {
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    console.log('login_form', data);
                    if (data.status == 'error') {
                        if (data.data.validationErrorList) {
                            $.each(data.data.validationErrorList, function(prefix, val) {
                                $('.' + prefix + '_error').text(val[0]);
                            });
                        } else if (data.data.messageList) {
                            $('#show_error').text(data.data.messageList[0]);
                        }
                        return;
                    }
                    localStorage.setItem("token", data.data.token);
                    window.location.href = "{{ route('dashboard')}}";
                }
            });
        }
    </script>
</head>

<body>
    <div class="row">
        <div class="col-lg-4 col-md-4">&nbsp;</div>
        <div class="col-lg-4 col-md-4">
            <form action="{{ route('api.login') }}" method="POST" id="login_form" class="request-form ">
                <h2>Login</h2>

                <div class="form-group mr-2">
                    <label for="" class="label">Email</label>
                    <input type="email" id="email" class="form-control">
                    <span class="text-danger error-text email_error" style="color: red"></span>
                </div>

                <div class="form-group mr-2">
                    <label for="" class="label">Password</label>
                    <input type="password" id="password" class="form-control">
                    <span class="text-danger error-text password_error" style="color: red"></span>
                </div>
                <div id="show_error" style="color: red"> </div>

                <div class="form-group">
                    <input type="button" onclick="submitForm()" value="Login" class="btn  py-3 px-4" style="background-color: #5f76e8; color:#ffffff">
                </div>
                <div class="form-group">
                    <a href="{{ url('/register') }}">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>