<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @routes
    @vite('resources/css/app.css')
    @viteReactRefresh
    @vite('resources/ts/app.tsx')
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
