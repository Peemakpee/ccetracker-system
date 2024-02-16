<!DOCTYPE html>
<html>
<head>
    <title>New Deliverable Approved By Dean</title>
</head>
<body>
    <h1>Dear {{ $data['user_name'] }},</h1>
    <p>The deliverable file you submitted was approved by the dean.</p>
    <p>File Details:</p>
    <ul>
        <li>Name: {{ $data['file_name'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
    </ul>
    <p>Please review it at your earliest convenience.</p>
    <p>Thank you,</p>
    <p>CCEtracker System</p>
</body>
</html>
