<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Enrollment - Edu Platform</title>
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
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #2e7d32;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn-submit {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn-submit:hover {
            background: #1b5e20;
        }
        .btn-back {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }
        .btn-back:hover {
            background: #5a6268;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student Enrollment</h1>
        
        <div class="info-box">
            <p><strong>Student:</strong> {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</p>
            <p><strong>Email:</strong> {{ $enrollment->student->email }}</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/students/{{ $enrollment->id }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="course_id">Course:</label>
                <select id="course_id" name="course_id" required>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $enrollment->course_id == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="mark">Mark:</label>
                <input type="number" id="mark" name="mark" value="{{ old('mark', $enrollment->mark) }}" min="0" max="1000">
            </div>

            <button type="submit" class="btn-submit">Update Enrollment</button>
        </form>

        <a href="/students" class="btn-back">← Back to Students</a>
    </div>
</body>
</html>