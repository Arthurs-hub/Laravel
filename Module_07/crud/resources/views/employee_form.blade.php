<!DOCTYPE html>
<html>

<head>
    <title>Employee Form</title>
    <style>
        
        body {
            background: linear-gradient(135deg, #3e2723, #5d4037);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        form {
            background-color: #4e342e;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            width: 350px;
            color: #f3e5f5;
            position: relative;
            z-index: 1;
        }

        form::before {
            content: "Add Employee";
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #d7ccc8;
            text-align: center;
            font-family: 'Georgia', serif;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #d7ccc8;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 6px;
            background-color: #6d4c41;
            color: #f3e5f5;
            font-size: 1rem;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            background-color: #8d6e63;
            outline: none;
            box-shadow: 0 0 8px #bcaaa4;
        }

        button {
            width: 100%;
            padding: 12px 0;
            background-color: #3e2723;
            border: none;
            border-radius: 8px;
            color: #f3e5f5;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        button:hover {
            background-color: #5d4037;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.5);
        }

        .message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 300px;
            max-width: 90vw;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .message-success {
            background-color: #a5d6a7;
            color: #1b5e20;
            border: 1px solid #4caf50;
        }

        .message-error {
            background-color: #ef9a9a;
            color: #b71c1c;
            border: 1px solid #f44336;
        }

        @media (max-width: 400px) {
            form {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <form method="POST" action="/users">
        @csrf

        @if(session('success'))
        <div id="flash-message" class="message message-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div id="flash-message" class="message message-error">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div id="flash-message" class="message message-error">
            <ul style="margin:0; padding-left: 20px; text-align: left;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required maxlength="50" autocomplete="off" value="{{ old('name') }}">

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required maxlength="50" autocomplete="off" value="{{ old('surname') }}">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" autocomplete="off" value="{{ old('email') }}">

        <button type="submit">Submit</button>
    </form>

    <script>
       
        window.addEventListener('DOMContentLoaded', () => {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.opacity = '0';
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500); 
                }, 3000); 
            }
        });
    </script>
</body>

</html>