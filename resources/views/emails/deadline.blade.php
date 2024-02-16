<!DOCTYPE html>
<html>
<head>
    <title>New Deadline for you!</title>
</head>
<body>
    <h1>Dear Faculty Member,</h1>
    <p>A new deadline has been set for {{ $data['program'] }}.</p>
    <p>Deadline Details:</p>
    <ul>
        <li>Program: {{ $data['program'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
        <li>Deadline Date: {{ \Carbon\Carbon::parse($data['deadline'])->format('F j, Y') }}</li>
    </ul>
    <p>Please review it at your earliest convenience.</p>
    <p>Thank you,</p>
    <p>CCEtracker System</p>
</body>
</html>
