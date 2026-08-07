<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="/home">
            Task Management System
        </a>

        <div>
            <a href="/home" class="btn btn-outline-light me-2">
                Home
            </a>

            <a href="/about" class="btn btn-outline-light me-2">
                About
            </a>

            <a href="{{ route('projects.index') }}" class="btn btn-outline-light">
                Projects
            </a>


            <form action="{{ route('logout') }}"
          method="POST"
          class="d-inline">

        @csrf

        <button type="submit"
                class="btn btn-danger">
            Logout
        </button>

    </form>
        </div>

    </div>
</nav>

<div class="container py-5">
    @yield('content')
</div>

<footer class="bg-dark text-white text-center py-3">
    © 2026 Task Management System
</footer>

</body>
</html>