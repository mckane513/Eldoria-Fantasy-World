<?php
// Příjem dat z PayPal
$raw_post_data = file_get_contents('php://input');
$data = 'cmd=_notify-validate&' . $raw_post_data;

// Ověření s PayPal
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

// Zkontrolujte odpověď
if ($response === "VERIFIED" && $_POST['payment_status'] == "Completed") {
    $payer_email = $_POST['payer_email'];
    $download_url = "https://www.eldoria.cz/download-page.html";

    // Zaslat potvrzení e-mailem
    $subject = "Děkujeme za nákup e-knihy Eldoria!";
    $message = "Děkujeme za váš nákup. Stáhněte si e-knihu na následujícím odkazu: $download_url";
    $headers = "From: no-reply@eldoria.cz";

    mail($payer_email, $subject, $message, $headers);

    // Logovat úspěšnou platbu
    file_put_contents("payments_log.txt", "$payer_email - Completed\n", FILE_APPEND);
}
?>
