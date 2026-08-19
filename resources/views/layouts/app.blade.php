<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Task Management System
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>


<body class="d-flex flex-column min-vh-100">


    {{-- Navbar --}}
    <nav class="navbar navbar-dark bg-dark py-3">

        <div class="container">

            {{-- Brand --}}
            <a class="navbar-brand"
               href="{{ route('home') }}">

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


                {{-- Organizations --}}
                @if(
                    session('user_role') == 'admin' ||
                    session('user_role') == 'manager'
                )

                    <a href="{{ route('organizations.index') }}"
                       class="btn btn-outline-light me-2">

                        Organizations

                    </a>

                @endif


                {{-- Projects --}}
                @if(
                    session('user_role') == 'admin' ||
                    session('user_role') == 'manager' ||
                    session('user_role') == 'employee'
                )

                    <a href="{{ route('projects.index') }}"
                       class="btn btn-outline-light me-2">

                        Projects

                    </a>

                @endif


                {{-- Employee My Tasks --}}
                @if(session('user_role') == 'employee')

                    <a href="{{ route('my.tasks') }}"
                       class="btn btn-outline-light me-2">

                        My Tasks

                    </a>

                @endif


                {{-- Logged-in User --}}
                <span class="text-white me-3">

                    {{ session('user_name') }}

                    ({{ ucfirst(session('user_role')) }})

                </span>

                @if(
    session('user_role') === 'admin' ||
    session('user_role') === 'manager'
)

    <a href="{{ route('invitations.create') }}"
       class="btn btn-outline-light me-2">

        Invite User

    </a>

@endif
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



    {{-- Main Content --}}
    <main class="flex-grow-1">

        <div class="container mt-4">

            @yield('content')

        </div>

    </main>



    {{-- Footer --}}
    <footer class="bg-dark text-white text-center py-3 mt-4">

        <div class="container">

            <p class="mb-0">

                © {{ date('Y') }}
                Task Management System.
                All Rights Reserved.

            </p>

        </div>

    </footer>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>