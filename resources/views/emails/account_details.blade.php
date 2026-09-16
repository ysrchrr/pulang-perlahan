<!DOCTYPE html>
<html>
<head>
    <title>Detail Akun UNS Pengaduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 50px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .details {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>UNS Pengaduan</h3>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $data['user_name'] }}</strong></p>
            <p>Berikut adalah detail akun untuk mengakses sistem <strong>UNS Pengaduan</strong>:</p>
            <div class="details">
                <p><strong>Username:</strong> {{ $data['email'] }}</p>
                <p><strong>Password:</strong> {{ $data['password'] }}</p>
            </div>
            <p>Silakan login melalui tautan di bawah ini:</p>
            <a href="{{ $data['login_url'] }}" class="button" style="color: white;">Login ke UNS Pengaduan</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} UNS Pengaduan. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
