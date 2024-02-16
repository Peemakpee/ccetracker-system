<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Deadline Reminder!</title>
</head>
<body>
    <h1>Dear Faculty Member,</h1>
    <p>We'd like to remind you about an upcoming deadline for {{ $data['program'] }}.</p>
    <p>Deadline Details:</p>
    <ul>
        <li>Program: {{ $data['program'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
        <li>Deadline Date: {{ $data['deadline'] }}</li>
    </ul>
    <p>Please ensure that all necessary tasks or submissions related to this are completed by the given date.</p>
    <p>Thank you for your attention to this matter.</p>
    <p>Regards,</p>
    <p>CCEtracker System</p>
</body>
</html>
