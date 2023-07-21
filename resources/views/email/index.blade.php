 
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Confirmation - University of Zimbabwe</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #dddddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #337ab7;
            text-align: center;
        }

        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #337ab7;
            margin-bottom: 30px;
        }

        p {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #337ab7;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #135b9e;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>University of Zimbabwe</h1>
        <p>Thank you for signing up with the University of Zimbabwe!</p>
        <p>Please use the following confirmation code to complete your registration:</p>
        <p class="code">{{$data['message']}}</p>
        <p>
            Use the code above to activate your account and gain access to
            exclusive
            features and resources.
        </p>
        <p>If you have any questions or need assistance, please don't hesitate to Whatsapp our support team.</p>
        <p>
            <a class="btn" href="https://api.whatsapp.com/send?phone=+263786103016">Whatsapp Us</a>
        </p>
        <p>Best regards,<br>The University of Zimbabwe Team</p>
    </div>
</body>

</html>
