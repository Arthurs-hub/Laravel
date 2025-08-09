<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Success</title>
</head>

<body style="background: linear-gradient(135deg, #FFE5B4 0%, #FFB347 100%); min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); padding: 30px 40px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 8px 20px rgba(255, 140, 0, 0.4); color: #fff; text-align: center;">
        <h1 style="margin-bottom: 20px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Success!</h1>
        <p style="font-size: 18px; font-weight: 600; margin-bottom: 30px;">{{ $message }}</p>
        <a href="{{ url('/index') }}" style="display: inline-block; background-color: #ff7f00; color: #fff; padding: 12px 25px; border-radius: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 12px rgba(255, 127, 0, 0.6); transition: background-color 0.3s ease;">
            Add Another Book
        </a>
    </div>
</body>

</html>