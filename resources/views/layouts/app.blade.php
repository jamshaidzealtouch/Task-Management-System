<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            Task Management System
        </a>

        <div class="d-flex align-items-center">

            {{-- Home --}}
            <a href="{{ route('home') }}"
               class="btn btn-outline-light me-2">
                Home
            </a>

            {{-- About --}}
            <a href="{{ route('about') }}"
               class="btn btn-outline-light me-2">
                About
            </a>

            {{-- Admin & Manager --}}
            @if(session('user_role') == 'admin' || session('user_role') == 'manager')

                <a href="{{ route('organizations.index') }}"
                   class="btn btn-outline-light me-2">
                    Organizations
                </a>

                <a href="{{ route('projects.index') }}"
                   class="btn btn-outline-light me-2">
                    Projects
                </a>

            @endif

            {{-- Employee --}}
            @if(session('user_role') == 'employee')

                <a href="#"
                   class="btn btn-outline-light me-2">
                    My Tasks
                </a>

            @endif

            {{-- Logged In User --}}
            <span class="text-white me-3">
                {{ session('user_name') }}
                ({{ ucfirst(session('user_role')) }})
            </span>

            {{-- Logout --}}
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