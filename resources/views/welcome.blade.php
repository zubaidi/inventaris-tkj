<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris TKJ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }
        .container {
            max-width: 720px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1);
            padding: 36px 28px;
            text-align: center;
        }
        h1 {
            margin: 0 0 12px;
            font-size: 2rem;
        }
        p {
            line-height: 1.7;
            color: #475569;
            margin-bottom: 24px;
        }
        .actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 600;
        }
        .primary {
            background: #2563eb;
            color: #fff;
        }
        .secondary {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistem Inventaris TKJ</h1>
        <p>
            Landing page ini sudah dibersihkan dari template Laravel default. Aplikasi ini menggunakan route khusus untuk inventaris, laboratorium, dan sumber dana.
        </p>
        <div class="actions">
            <a href="{{ route('home') }}" class="primary">Halaman Publik</a>
            <a href="{{ route('login') }}" class="secondary">Login Admin</a>
        </div>
    </div>
</body>
</html>
