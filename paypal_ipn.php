<?php
// Zapnout hlášení chyb pro ladění
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Funkce pro logování
function log_to_file($message) {
    $file = __DIR__ . '/ipn_log.txt'; // Používá absolutní cestu k souboru ve stejné složce
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($file, "[$timestamp] $message\n", FILE_APPEND);
}

// Logovat spuštění skriptu
log_to_file("IPN skript spuštěn");

// Příjem dat z PayPal
$raw_post_data = file_get_contents('php://input');
$data = 'cmd=_notify-validate&' . $raw_post_data;

// Logování příchozích dat
log_to_file("Příchozí data: " . print_r($raw_post_data, true));

// Ověření s PayPal
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

// Logování odpovědi od PayPalu
log_to_file("Odpověď od PayPal: $response");

// Zkontrolujte odpověď a stav platby
if ($response === "VERIFIED" && isset($_POST['payment_status']) && $_POST['payment_status'] === "Completed") {
    $payer_email = $_POST['payer_email'];
    $download_url = "https://www.eldoria.cz/download-page.html";

    // Zaslat potvrzení e-mailem
    $subject = "Děkujeme za nákup e-knihy Eldoria!";
    $message = "Děkujeme za váš nákup. Stáhněte si e-knihu na následujícím odkazu: $download_url";
    $headers = "From: no-reply@eldoria.cz";

    if (mail($payer_email, $subject, $message, $headers)) {
        log_to_file("E-mail odeslán na $payer_email");
    } else {
        log_to_file("E-mail nebyl odeslán na $payer_email");
    }

    // Logovat úspěšnou platbu
    log_to_file("Platba ověřena: $payer_email - Completed");
} else {
    log_to_file("Platba nebyla ověřena nebo není dokončena.");
}
?>

?>
