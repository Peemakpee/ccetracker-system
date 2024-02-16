<!DOCTYPE html>
<html>
<head>
    <title>Faculty Compliance Notification</title>
</head>
<body>
    <h1>Dear Dean,</h1>
    <p>We are writing to inform you that {{ $data['user_name'] }} of {{ $data['program'] }} has successfully complied with the required changes for the deliverable.</p>
    <p>Deliverable Details:</p>
    <ul>
        <li>Name: {{ $data['file_name'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
    </ul>
    <p>This deliverable has been reviewed and approved by the faculty user, and it now meets the necessary standards.</p>
    <p>If you have any further questions or require additional information, please feel free to reach out to us.</p>
    <p>Thank you for your attention to this matter.</p>
    <p>Best regards,</p>
    <p>CCEtracker System</p>
</body>
</html>
