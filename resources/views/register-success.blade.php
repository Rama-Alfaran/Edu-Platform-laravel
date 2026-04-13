<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ece5dd;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .success-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            background: #2e7d32;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #1b5e20;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Registration Successful!</h2>
        <p>Welcome, {{ $first_name }}! You are now logged in.</p>
        @if (session('success'))
            <p class="text-success">{{ session('success') }}</p>
        @endif
        <a href="{{ route('dashboard') }}" class="btn">Go to Dashboard</a>
    </div>
</body>
</html>