<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        .splash-container {
            text-align: center;
        }
        .splash-logo {
            width: 200px;
            height: 200px;
        }
        .splash-text {
            font-size: 24px;
            margin-top: 20px;
            font: "popin, sans-serif";
        }
    </style>
</head>
<body>
    <div class="splash-container">
        <img src="unipilogo.png" alt="Logo" class="splash-logo">
        <div class="splash-text">Welcome to Sistem Informasi Peminjman Ruangan Universitas Insan Pembangunan Indonesia
            <p>(SIPENGAN)</p>
        </div>
    </div>
    <script>
        setTimeout(function(){
            window.location.href = 'index.php';
        }, 7000); // Redirect after 3 seconds
    </script>
</body>
</html>
