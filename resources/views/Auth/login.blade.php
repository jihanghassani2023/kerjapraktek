<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MG Tech Palembang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .left-panel {
            background-color: #9D4E4E; /* Reddish-brown as shown in image */
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-container {
            text-align: center;
        }
        .logo-container img {
            max-width: 500px;
        }
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            position: relative;
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
            background-color: #FAF0F0; /* Light pink background */
            padding: 30px;
            border-radius: 8px;
            width: 300px;
            z-index: 2;
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

        /* Style for the password input and eye icon */
        .password-container {
            position: relative;
        }

        #password {
            width: 100%;
            padding: 8px 30px 8px 10px;  /* Padding for the eye icon */
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
            font-size: 18px;
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
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">PASSWORD</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" required placeholder="Password">
                            <span class="eye-icon" id="eye-icon" onclick="togglePassword()">👁️</span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
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
                eyeIcon.textContent = "🙈";
            } else {
                passwordInput.type = "password";
                eyeIcon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>