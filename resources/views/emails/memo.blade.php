<!DOCTYPE html>
<html>
<head>
    <title>Deliverable For Revision</title>
</head>
<body>
    <h1>Dear Faculty User,</h1>
    <p>The deliverable file you submitted needs to be revised.</p>
    <p>File Details:</p>
    <ul>
        <li>To: {{ $data['memo_to'] }}</li>
        <li>from: {{ $data['memo_from'] }}</li>
        <li>Subject: {{ $data['memo_subject'] }}</li>
        <li>Created at: {{ $data['created_at'] }}</li>
    </ul>
    <p>Please review it at your earliest convenience.</p>
    <p>Thank you,</p>
    <p>CCEtracker System</p>
</body>
</html>
