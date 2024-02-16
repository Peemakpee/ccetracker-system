<!DOCTYPE html>
<html>

<head>
    <title>Request for CCEtracker Registration</title>
</head>

<body>
    <p>Hello {{ $data['name'] }},</p>

    <p>Your registration request has been approved. You can now proceed with the registration process.</p>

    <p>To register, please click on the following link:</p>
    <p><a href="{{ $data['registerUrl'] }}">Register Now</a></p>

    <p>Thank you,</p>
    <p>CCEtracker System</p>
</body>

</html>