<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7e0, #f5f5f0);
            margin: 0;
            padding: 0;
            min-height: 100vh;
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
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #1b5e20;
            text-decoration: none;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .search-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-clear {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-clear:hover {
            background: #5a6268;
        }
        .courses-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #2e7d32;
            color: #fff;
        }
        .course-image {
            max-width: 60px;
            max-height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .btn-edit {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            margin-right: 5px;
        }
        .btn-delete {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
        }
        .btn-back {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-logout {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Edu Platform</h3>
        <nav class="nav flex-column">
            <a class="nav-link" href="/dashboard">Dashboard</a>
            <a class="nav-link active" href="/courses">Manage Courses</a>
            <a class="nav-link" href="/add_courses">Add Course</a>
            <a class="nav-link" href="/students">Manage Students</a>
            <a class="nav-link" href="/add_students">Add Student</a>
            <a class="nav-link" href="/chat">Join Chat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" style="padding: 20px; position: absolute; bottom: 0; width: 100%;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="content">
        <h1>Manage Courses</h1>
        
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Search by name or description..." value="{{ $search ?? '' }}" oninput="searchCourses(this.value)">
            <button class="btn-clear" onclick="clearSearch()">Clear</button>
        </div>

        <div class="courses-container">
            <h3>All Courses</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Base Mark</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->base_mark }}</td>
                            <td>
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" class="course-image">
                                @else
                                    No image
                                @endif
                            </td>
                            <td>
                                <button class="btn-edit" onclick="window.location.href='/courses/{{ $course->id }}/edit'">Edit</button>
                                <form method="POST" action="/courses/{{ $course->id }}/delete" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No courses found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <a href="/dashboard" class="btn-back">← Back to Dashboard</a>
        </div>
    </div>

    <script>
        function searchCourses(query) {
            window.location.href = `/courses?search=${encodeURIComponent(query)}`;
        }

        function clearSearch() {
            window.location.href = '/courses';
        }
    </script>
</body>
</html>