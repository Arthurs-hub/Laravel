<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма пользователя</title>
    <style>
        body {
            background: linear-gradient(120deg, #e0f7fa, #1317f16b);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #ffffffdd;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"] {
            width: 93%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.2s ease;
        }

        input:focus {
            border-color: #3966e2ff;
            outline: none;
            box-shadow: 0 0 5px rgba(151, 175, 240, 0.86);
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #4063ffff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e91e63;
        }
    </style>
</head>
<body>
    <form method="POST" action=" ">
        @csrf
        <h2>Форма пользователя</h2>

        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>

        <label for="surname">Фамилия:</label>
        <input type="text" id="surname" name="surname" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Отправить</button>
    </form>
</body>
</html>
