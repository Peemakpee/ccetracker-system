<!DOCTYPE html>
<html>
<head>
    <title>The Deliverable You Uploaded Requires Changes</title>
</head>
<body>
    <h1>Dear {{ $data['user_name'] }},</h1>
    <p>There have been changes to the deliverable you uploaded.</p>
    <p>File Details:</p>
    <ul>
        <li>Name: {{ $data['file_name'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
    </ul>
    <p>Please review the feedback provided and make the necessary revisions as soon as possible.</p>
    <p>Thank you for your attention to this matter.</p>
    <p>Best regards,</p>
    <p>CCEtracker System</p>
</body>
</html>
