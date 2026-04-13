<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Edu Platform</title>
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
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-input {
            flex: 1;
            margin-right: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .clear-btn {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .clear-btn:hover {
            background: #b71c1c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #2e7d32;
            color: #fff;
        }
        .no-results {
            text-align: center;
            color: #d32f2f;
        }
        .btn-custom {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn-custom:hover {
            background: #1b5e20;
            color: #fff;
        }
        .btn-edit {
            background: #1976d2;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-edit:hover {
            background: #1565c0;
            color: #fff;
        }
        .btn-delete {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #b71c1c;
        }
        .btn-logout {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Edu Platform</h3>
        <nav class="nav flex-column">
            <a class="nav-link" href="/dashboard">Dashboard</a>
            <a class="nav-link active" href="/students">Manage Students</a>
            <a class="nav-link" href="/add_students">Add Student</a>
            <a class="nav-link" href="/courses">Manage Courses</a>
            <a class="nav-link" href="/add_courses">Add Course</a>
            <a class="nav-link" href="/chat">Join Chat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" style="padding: 20px; position: absolute; bottom: 0; width: 100%;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
    
    <div class="content">
        <h2>Manage Students</h2>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by student name or course..." value="{{ $search ?? '' }}" oninput="debounce(searchStudents, 300)(this.value)">
            <button class="clear-btn" onclick="clearSearch()">Clear</button>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Mark</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentsTbody">
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</td>
                        <td>{{ $enrollment->student->email }}</td>
                        <td>{{ $enrollment->course->name }}</td>
                        <td style="text-align: center;">{{ $enrollment->mark ?? '-' }}</td>
                        <td>
                            <a href="/students/{{ $enrollment->id }}/edit" class="btn-edit">Edit</a>
                            <form method="POST" action="/students/{{ $enrollment->id }}/delete" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student enrollment?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-results">No results found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <a href="/dashboard" class="btn-custom mt-3">&larr; Back to Dashboard</a>
    </div>

    <script>
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function searchStudents(query) {
            window.location.href = `/students?search=${encodeURIComponent(query)}`;
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            searchStudents('');
        }
    </script>
</body>
</html>