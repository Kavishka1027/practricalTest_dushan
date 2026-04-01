<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Platform11111111111111111</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
<div
    id="app"
    data-websites-endpoint="{{ url('/api/v1/websites') }}"
></div>
</body>
</html>
