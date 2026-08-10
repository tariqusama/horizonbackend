<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            color: #1B3A64;
            margin: 0;
        }
        .content {
            padding: 20px;
            text-align: center;
            color: #333333;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #E3755D;
            letter-spacing: 5px;
            margin: 20px 0;
            padding: 10px;
            background-color: #FFF2ED;
            border-radius: 8px;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Horizon Pathways</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for starting your immigration journey with us. Please use the verification code below to complete your sign up.</p>
            <div class="code">
                {{ $otp }}
            </div>
            <p>This code is valid for 10 minutes. Do not share this code with anyone.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Horizon Pathways. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
