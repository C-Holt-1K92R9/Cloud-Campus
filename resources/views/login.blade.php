<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .logo-container {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 180px;
        }
        .logo {
            max-width: 140%;
            max-height: 140%;
        }
        .login-container {
            background-color: #18181b;
            padding: 5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 320px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .login-button {
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: none;
            background-color: #3b82f6;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #2563eb;
        }
        /* New styles for the input */
        .input {
            line-height: 28px;
            border: 2px solid transparent;
            border-bottom-color: #777;
            padding: .2rem 0;
            outline: none;
            background-color: transparent;
            color: #fff;
            transition: .3s cubic-bezier(0.645, 0.045, 0.355, 1);
            width: 100%;
            box-sizing: border-box;
        }

        .input:focus, .input:hover {
            outline: none;
            padding: .2rem 1rem;
            border-radius: 1rem;
            border-color: #7a9cc6;
        }

       

        .input:focus::placeholder {
            opacity: 0;
            transition: opacity .3s;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="/logo.png" alt="Logo" class="logo">
    </div>
    <div class="login-container">
        <div class="login-header">
            <div class="spinner"></div>
            <h2>Welcome Back</h2>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form id="loginForm" class="login-form" action="{{route('login')}}" method="POST">
            @csrf
            <input placeholder="Email " type="email" class="input" id="email" name="email" required>
            <input placeholder="Password" type="password" class="input" id="password" name="password" required>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
