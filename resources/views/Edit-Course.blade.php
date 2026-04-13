<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Edu Platform</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #2e7d32;
            font-weight: 600;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
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
        .current-image {
            max-width: 200px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Course</h1>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/courses/{{ $course->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Course Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="base_mark">Base Mark:</label>
                <input type="number" id="base_mark" name="base_mark" value="{{ old('base_mark', $course->base_mark) }}" min="1" max="1000" required>
            </div>

            <div class="form-group">
                <label for="image">Course Image:</label>
                @if($course->image)
                    <div>
                        <p>Current Image:</p>
                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" class="current-image">
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color: #666;">Leave empty to keep current image</small>
            </div>

            <button type="submit" class="btn-submit">Update Course</button>
        </form>

        <a href="/courses" class="btn-back">← Back to Courses</a>
    </div>
</body>
</html>