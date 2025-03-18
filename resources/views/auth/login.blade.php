<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Perpustakaan</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">

    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #007bff;
        }
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #0056b3;
            color: white;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .text-error {
            color: white;
            text-align: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Login User Sistem Perpustakaan CI/CD</h2>
                            </div>
                            {{-- error log message --}}
                            @if ($errors->any())
                                <div class="col align-self-center">
                                    <ul class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                <span class="text-danger text-error">{{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="fa fa-eye" id="togglePassword_password"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <p class="text-center mt-3">Forgot password?</p>
                        <p class="text-center">Not a member? <a href="{{ route('register') }}">SignUp Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const togglePasswordButton = document.getElementById('togglePassword_' + fieldId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.classList.remove('fa-eye');
                togglePasswordButton.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.classList.remove('fa-eye-slash');
                togglePasswordButton.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
