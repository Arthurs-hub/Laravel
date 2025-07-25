<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e5ec);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .greeting {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 2.2em;
        }
    </style>
</head>
<body>
    <div class="greeting">
        <h1>Привет, {{ $name }}!</h1>
    </div>
</body>
</html>
