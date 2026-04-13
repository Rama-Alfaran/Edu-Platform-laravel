<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7e0, #f5f5f0);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #2e7d32;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f5f5f5;
        }
        .course-image {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .btn-back {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
            display: inline-block;
        }
        .btn-back:hover {
            background: #1b5e20;
            color: #fff;
        }
        .no-courses {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Enrolled Courses and Marks</h1>
        
        @if($enrollments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Base Mark</th>
                        <th>My Mark</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->course->name }}</td>
                            <td>{{ $enrollment->course->description }}</td>
                            <td style="text-align: center;">{{ $enrollment->course->base_mark }}</td>
                            <td style="text-align: center;">
                                @if($enrollment->mark)
                                    {{ $enrollment->mark }}
                                @else
                                    <span style="color: #999;">Not graded yet</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($enrollment->course->image)
                                    <img src="{{ asset('storage/' . $enrollment->course->image) }}" 
                                         alt="{{ $enrollment->course->name }}" 
                                         class="course-image">
                                @else
                                    <span style="color: #999;">No image</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-courses">
                <p>You are not enrolled in any courses yet.</p>
            </div>
        @endif
        
        <a href="/dashboard" class="btn-back">← Back to Dashboard</a>
    </div>
</body>
</html>
