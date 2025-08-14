<!DOCTYPE html>
<html>

<head>
    <title>Employee Resume</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f0e6;
            color: #4e342e;
            margin: 40px;
        }

        h1 {
            text-align: center;
            color: #6d4c41;
            font-size: 2.5rem;
            margin-bottom: 40px;
            border-bottom: 3px solid #a1887f;
            padding-bottom: 10px;
            font-family: 'Georgia', serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff8f0;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(109, 76, 65, 0.3);
            border: 1px solid #d7ccc8;
        }

        .field {
            margin-bottom: 25px;
        }

        .label {
            font-weight: 700;
            font-size: 1.1rem;
            color: #5d4037;
            margin-bottom: 6px;
            display: block;
            font-family: 'Georgia', serif;
        }

        .value {
            font-size: 1.3rem;
            color: #3e2723;
            padding-left: 10px;
            border-left: 4px solid #a1887f;
        }
    </style>
</head>

<body>
    <h1>Employee Information</h1>
    <div class="container">
        <div class="field">
            <span class="label">Name:</span>
            <span class="value">{{ $name }}</span>
        </div>
        <div class="field">
            <span class="label">Surname:</span>
            <span class="value">{{ $surname }}</span>
        </div>
        <div class="field">
            <span class="label">Email:</span>
            <span class="value">{{ $email }}</span>
        </div>
    </div>
</body>

</html>