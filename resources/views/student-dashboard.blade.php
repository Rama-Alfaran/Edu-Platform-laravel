<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7e0, #f5f5f0);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: #2e7d32;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .sidebar h3 {
            text-align: center;
            padding: 20px;
            margin: 0;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #1b5e20;
            text-decoration: none;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .profile-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .options-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background: #2e7d32;
            color: #fff;
            border: none;
            margin: 5px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }
        .btn-custom:hover {
            background: #1b5e20;
            color: #fff;
        }
        .btn-logout {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
            display: block;
            text-align: center;
            width: 100%;
        }
        .btn-logout:hover {
            background: #b71c1c;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Edu Platform</h3>
        <nav class="nav flex-column">
            <a class="nav-link active" href="/dashboard">Dashboard</a>
            <a class="nav-link" href="/my_courses">My Courses</a>
            <a class="nav-link" href="/chat">Join Chat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" style="padding: 20px; margin-top: auto; position: absolute; bottom: 0; width: 100%;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
    
    <div class="content">
        <div class="profile-card">
            <h2>Welcome, {{ $user->first_name }}, to your Student Dashboard!</h2>
        </div>
        <div class="options-card">
            <h3>Student Options</h3>
            <div class="d-grid gap-2">
                <a href="/my_courses" class="btn btn-custom">View My Courses and Marks</a>
            </div>
            <h3 class="mt-4">General</h3>
            <div class="d-grid gap-2">
                <a href="/chat" class="btn btn-custom">Join Chat</a>
            </div>
        </div>
    </div>
</body>
</html>