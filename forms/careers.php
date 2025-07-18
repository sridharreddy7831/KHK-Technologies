<?php

$statusMsg = '';

if (isset($_FILES["cvResume"]["name"])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactNum = $_POST['contactNum'];
    $jobVacancy = $_POST['jobVacancy'];
    // Removed jobLocation

    $fromemail = $email;
    $subject = "Submitted CV";

    // Build the email body without Job Location
    $email_body_html = '<h2>CV Submitted</h2>
        <p><b>Name:</b> ' . $name . '</p>
        <p><b>Email:</b> ' . $email . '</p>
        <p><b>Contact #:</b> ' . $contactNum . '</p>
        <p><b>Job Vacancy:</b> ' . $jobVacancy . '</p>
        <p>Please find the attachment below.</p>';

    $semi_rand = md5(uniqid(time()));
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers = "From: " . $fromemail . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed;\r\n" .
                " boundary=\"{$mime_boundary}\"";

    $email_message = "--{$mime_boundary}\r\n";
    $email_message .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $email_message .= $email_body_html . "\r\n\r\n";

    if ($_FILES["cvResume"]["name"] != "") {
        $file_name = $_FILES["cvResume"]["name"];
        $file_data = file_get_contents($_FILES["cvResume"]["tmp_name"]);
        $file_content = chunk_split(base64_encode($file_data));

        $email_message .= "--{$mime_boundary}\r\n";
        $email_message .= "Content-Type: application/octet-stream;\r\n" .
                          " name=\"{$file_name}\"\r\n";
        $email_message .= "Content-Disposition: attachment;\r\n" .
                          " filename=\"{$file_name}\"\r\n";
        $email_message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $email_message .= $file_content . "\r\n\r\n";
        $email_message .= "--{$mime_boundary}--";
    }

    $toemail = "khktechnologiesprivatelimited@gmail.com";

    if (mail($toemail, $subject, $email_message, $headers)) {
        $statusMsg = "Email sent successfully with attachment.";
    } else {
        $statusMsg = "Email not sent.";
    }
}

echo '<script type="text/javascript">
    alert("' . $statusMsg . '");
    window.location.href = "https://khktechnologies.com/careers.html";
</script>';
