<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MG Tech Palembang</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        html {
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .left-panel {
            background-color: #9D4E4E;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            max-width: 500px;
            max-height: 80vh;
            width: auto;
            height: auto;
        }

        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            position: relative;
            overflow: hidden;
        }

        .right-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #9D4E4E 50%, transparent 50%);
            z-index: 1;
        }

        .login-form {
            background-color: #FAF0F0;
            padding: 30px;
            border-radius: 8px;
            width: 300px;
            max-width: 90%;
            z-index: 2;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #9D4E4E;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .password-container {
            position: relative;
        }

        #password {
            width: 100%;
            padding: 8px 30px 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #666;
        }

        .form-group input.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
            }

            .left-panel {
                height: 35vh;
                flex: none;
            }

            .right-panel {
                height: 65vh;
                flex: none;
            }

            .logo-container img {
                max-width: 250px;
                max-height: 30vh;
            }

            .login-form {
                padding: 20px;
                width: 90%;
                max-width: 350px;
                min-width: 280px;
            }
        }

        @media (max-width: 600px) {
            .left-panel {
                height: 30vh;
            }

            .right-panel {
                height: 70vh;
            }

            .logo-container img {
                max-width: 200px;
                max-height: 25vh;
            }

            .login-form {
                padding: 15px;
                width: 85%;
                max-width: 300px;
                min-width: 250px;
            }

            .login-form h2 {
                font-size: 1.3em;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 400px) {
            .left-panel {
                height: 25vh;
            }

            .right-panel {
                height: 75vh;
            }

            .logo-container img {
                max-width: 150px;
                max-height: 20vh;
            }

            .login-form {
                padding: 12px;
                width: 90%;
                max-width: 280px;
                min-width: 200px;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .form-group label {
                font-size: 0.9em;
            }

            .form-group input {
                padding: 6px;
                font-size: 0.9em;
            }

            .btn-login {
                padding: 8px;
                font-size: 0.9em;
            }
        }

        @media (max-height: 600px) {
            .login-form {
                padding: 15px;
                max-height: 85vh;
                overflow-y: auto;
            }

            .form-group {
                margin-bottom: 10px;
            }

            .logo-container img {
                max-height: 40vh;
            }
        }

        @media (max-height: 500px) {
            .left-panel {
                height: 25vh;
            }

            .right-panel {
                height: 75vh;
            }

            .login-form {
                padding: 10px;
                max-height: 70vh;
            }

            .login-form h2 {
                font-size: 1.2em;
                margin-bottom: 10px;
            }

            .form-group {
                margin-bottom: 8px;
            }

            .logo-container img {
                max-height: 20vh;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="left-panel">
            <div class="logo-container">
                <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo">
            </div>
        </div>
        <div class="right-panel">
            <div class="login-form">
                <h2>LOGIN</h2>
                <form method="POST" action="{{ route('login.submit') }}" id="loginForm" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            autofocus placeholder="Email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback" id="email-error" style="display: none;"></div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">PASSWORD</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" required placeholder="Password"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <i class="fas fa-eye-slash eye-icon" id="eye-icon" onclick="togglePassword()"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback" id="password-error" style="display: none;"></div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-login">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');

            if (!emailInput.value.trim()) {
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Email wajib diisi.';
                emailError.style.display = 'block';
                isValid = false;
            } else if (!isValidEmail(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Format email tidak valid.';
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
                emailError.style.display = 'none';
            }

            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');

            if (!passwordInput.value.trim()) {
                passwordInput.classList.add('is-invalid');
                passwordError.textContent = 'Password wajib diisi.';
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordError.style.display = 'none';
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>

</html>
