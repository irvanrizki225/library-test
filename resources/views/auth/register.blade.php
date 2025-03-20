<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sistem Perpustakaan</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>

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
            font-size: 1.5rem;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
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
                                Register User Sistem Perpustakaan Oke
                            </div>
                            {{-- error log message --}}
                            @if ($errors->any())
                                <div class="col-md-12 align-self-center">
                                    <div class="alert alert-danger">
                                        <ul class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                <li>
                                                    <span class="text-danger">{{ $error }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-group">
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <div class="input-group">
                                    <input type="text" id="nik" name="nik" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <div class="input-group">
                                    <input type="text" id="phone" name="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <div class="input-group">
                                    <textarea name="address" id="" cols="100" rows="5"></textarea>
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
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                        <i class="fa fa-eye" id="togglePassword_password_confirmation"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        {{-- <p class="text-center mt-3">Forgot password?</p> --}}
                        <p class="text-center">You have an account? <a href="{{ route('login') }}">SignIn Now</a></p>
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
