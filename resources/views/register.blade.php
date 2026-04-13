<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; background: #ece5dd; font-family: "Segoe UI", Arial, sans-serif; }
        .register-container { background: #fff; padding: 10px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 250px; width: 100%; animation: fadeIn .3s ease; }
        h2 { text-align: center; margin-bottom: 5px; color: #2e7d32; font-size: 1.2em; }
        .form-group { margin-bottom: 5px; }
        label { display: block; margin-bottom: 1px; color: #333; font-size: 12px; }
        input, select { width: 100%; padding: 3px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 12px; }
        .checkbox-group { display: flex; flex-wrap: wrap; font-size: 12px; }
        .checkbox-group label { margin-right: 5px; display: flex; align-items: center; }
        button { width: 100%; padding: 3px; background: #2e7d32; color: #fff; border: none; border-radius: 4px; cursor: pointer; transition: background .3s; font-size: 12px; }
        button:hover { background: #1b5e20; }
        .error { color: #d32f2f; text-align: center; margin-bottom: 5px; font-size: 12px; }
        .login-link { text-align: center; margin-top: 5px; font-size: 0.8em; }
        .login-link a { color: #2e7d32; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        @if ($errors->any())
            <div class="error">@foreach ($errors->all() as $error) {{ $error }}<br> @endforeach</div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="role" value="{{ old('role') }}">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hobbies">Hobbies</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="hobbies[]" value="Reading" {{ in_array('Reading', old('hobbies', [])) ? 'checked' : '' }}> Reading</label>
                    <label><input type="checkbox" name="hobbies[]" value="Sports" {{ in_array('Sports', old('hobbies', [])) ? 'checked' : '' }}> Sports</label>
                    <label><input type="checkbox" name="hobbies[]" value="Music" {{ in_array('Music', old('hobbies', [])) ? 'checked' : '' }}> Music</label>
                </div>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required onchange="updateDateLimits()">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                    <option value="Teacher" {{ old('role') == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="Student" {{ old('role') == 'Student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
    <script>
        function updateDateLimits() {
            const role = document.getElementById('role').value;
            const birthdateInput = document.getElementById('birthdate');
            const currentDate = new Date();
            const minDate = new Date(currentDate);
            const maxDate = new Date(currentDate);
            const ages = role === 'Student' ? [25, 5] : [65, 18];
            minDate.setFullYear(currentDate.getFullYear() - ages[0]);
            maxDate.setFullYear(currentDate.getFullYear() - ages[1]);
            birthdateInput.setAttribute('min', minDate.toISOString().split('T')[0]);
            birthdateInput.setAttribute('max', maxDate.toISOString().split('T')[0]);
            const currentValue = birthdateInput.value ? new Date(birthdateInput.value) : null;
            if (currentValue && (currentValue < minDate || currentValue > maxDate)) {
                birthdateInput.value = '';
            }
        }
        window.onload = updateDateLimits;
    </script>
</body>
</html>