<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>

    {{-- Vite (TS / CSS 読み込み) --}}
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="bg-gray-100">

    <div class="container mx-auto py-8">
        @yield('content')
    </div>

</body>
</html>
