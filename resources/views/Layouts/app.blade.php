<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Metode Pembelajaran')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-wrapper {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 280px;
            background-color: #343a40;
            color: white;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        @include('layouts.partials.sidebar')

        <div class="d-flex flex-column w-100">
            @include('layouts.partials.navigation')

            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>