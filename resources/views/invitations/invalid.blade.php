<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Invitation</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-bottom: 20px;
            color: #1f2937;
        }

        p {
            color: #6b7280;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Invitation Notice</h1>

        <p>{{ $message }}</p>

        <a href="{{ route('register') }}" class="button">
            Create Account
        </a>

    </div>

</body>
</html>