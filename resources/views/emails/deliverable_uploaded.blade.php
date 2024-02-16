<!DOCTYPE html>
<html>
<head>
    <title>New Deliverable Uploaded</title>
</head>
<body>
    <h1>Dear Program Head,</h1>
    <p>A new deliverable file has been uploaded for {{ $data['program'] }}.</p>
    <p>File Details:</p>
    <ul>
        <li>Name: {{ $data['file_name'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
        <li>Uploader: {{ $data['user_name'] }}</li>
    </ul>
    <p>Please review it at your earliest convenience.</p>
    <p>Thank you,</p>
    <p>CCEtracker System</p>
</body>
</html>
