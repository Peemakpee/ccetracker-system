<!DOCTYPE html>
<html>

<head>
    <title>Deliverable Reviewed by Dean Requires Revision</title>
</head>

<body>
    <h1>Dear Faculty User,</h1>
    <p>The dean has reviewed the deliverable you submitted and has requested revisions.</p>
    <p>File Details:</p>
    <ul>
        <li>Name: {{ $data['file_name'] }}</li>
        <li>Type: {{ $data['deliverable_type'] }}</li>
        <li>Uploader: {{ $data['user_name'] }}</li>
    </ul>
    <p>Please make the necessary revisions at your earliest convenience.</p>
    <p>Thank you for your attention to this matter.</p>
    <p>CCEtracker System</p>
</body>

</html>