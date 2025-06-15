<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { background: #f8fafc; color: #222; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .error-container { background: #fff; padding: 40px 60px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center; }
        .error-code { font-size: 72px; font-weight: bold; color: #e53e3e; }
        .error-message { font-size: 24px; margin: 20px 0; }
        .back-link { color: #3182ce; text-decoration: none; font-size: 18px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code', 'Error')</div>
        <div class="error-message">@yield('message', 'Terjadi kesalahan. Silakan coba lagi nanti.')</div>
        <a href="{{ url('/') }}" class="back-link">Kembali ke Beranda</a>
    </div>
</body>
</html>
