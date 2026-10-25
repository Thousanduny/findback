<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindBack - Бюро находок</title>

    <!-- ВОТ НАША ИКОНКА -->
    <link rel="icon" type="image/png" href="https://findback-bpip.onrender.com/favicon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ... (тут остальные стили, они не меняются) ... */
        body {
            background-color: #111827;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #F3F4F6;
            min-height: 100vh;
        }

        .navbar-custom {
            background-color: #2563EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .btn-custom-orange {
            background-color: #F97316;
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-custom-orange:hover {
            background-color: #ea580c;
            color: white;
        }
        .btn-custom-blue {
            background-color: #2563EB;
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-custom-blue:hover {
            background-color: #1d4ed8;
            color: white;
        }

        .card {
            background-color: #1F2937;
            color: white;
            border: 1px solid #374151;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }
        .form-control, .form-select {
            background-color: #374151;
            border: 1px solid #4B5563;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background-color: #374151;
            color: white;
            border-color: #F97316;
            box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25);
        }

        .hero-graphite {
            background-color: #1F2937;
            color: white;
            border-radius: 16px;
            padding: 60px 20px;
            margin-top: 30px;
            border: 1px solid #374151;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        .offcanvas-custom {
            background-color: #1F2937;
            color: white;
            border-left: 1px solid #374151;
            display: flex;
            flex-direction: column;
        }
        .offcanvas-custom .nav-link {
            color: #D1D5DB;
            font-size: 1.1rem;
            padding: 10px 0;
        }
        .offcanvas-custom .nav-link:hover {
            color: #F97316;
        }

        ::placeholder {
            color: #9CA3AF !important;
            opacity: 1;
        }

        .nav-pills .nav-link {
            color: #9CA3AF;
            font-weight: 600;
            margin-right: 5px;
            border-radius: 8px;
        }
        .nav-pills .nav-link:hover {
            color: #F97316;
        }
        .nav-pills .active-tab {
            background-color: #F97316 !important;
            color: white !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">🔍 FindBack</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end offcanvas-custom" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Меню</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column">
            <ul class="navbar-nav w-100">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Главная</a></li>

                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('items.my') }}">Мои объявления</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('matches') }}">Совпадения</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('chats.index') }}">Мои чаты</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Профиль</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('items.create') }}" style="color: #F97316; font-weight: bold;">Добавить объявление</a></li>
                @else
                    <li class="nav-item mt-3">
                        <a class="btn btn-outline-light w-100 mb-2" href="{{ route('login') }}">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-custom-orange w-100" href="{{ route('register') }}">Регистрация</a>
                    </li>
                @endauth
            </ul>

            @auth
                <div class="mt-auto mb-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-secondary p-0 text-decoration-none" style="font-size: 0.9rem;">Выйти</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
