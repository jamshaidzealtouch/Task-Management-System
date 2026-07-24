<!DOCTYPE html>
<html>
<head>

<title>Task Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="/home">
Task Management
</a>

<div>

<a href="/home" class="btn btn-outline-light me-2">
Home
</a>

<a href="/about" class="btn btn-outline-light">
About
</a>

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