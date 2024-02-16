<!DOCTYPE html>
<html>

<head>
    <title>Follow-Up Reminder</title>
</head>

<body>
    <p>Hello {{ $followUpData['program'] }} Faculty Member,</p>

    <p>This is a friendly reminder about an upcoming deadline for the {{ $followUpData['deliverable_type'] }} deliverable that has been assigned to you.</p>

    <p>The submission deadline is {{ \Carbon\Carbon::parse($followUpData['deadline'])->format('F j, Y') }}.</p>

    <p>To ensure timely completion, please prepare and submit all required materials on or before the deadline.</p>

    <p>Your cooperation is greatly appreciated.</p>
    <p>Best regards,</p>
    <p>The CCEtracker System</p>
</body>

</html>