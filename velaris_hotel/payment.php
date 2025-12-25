<?php
session_start();

/**
 * HARUS LOGIN
 */
if (!isset($_SESSION['customer_id'])) {
    header("Location: auth/login.php");
    exit;
}

/**
 * AMBIL DATA DARI RESERVASI
 */
$id_kamar    = $_POST['id_kamar']    ?? '';
$checkin     = $_POST['checkin']     ?? '';
$checkout    = $_POST['checkout']    ?? '';
$total_harga = $_POST['total_harga'] ?? '';

/**
 * AMBIL DATA USER DARI SESSION
 */
$nama  = $_SESSION['customer_name'];
$email = $_SESSION['customer_email'] ?? '-';

/**
 * VALIDASI
 */
if (!$id_kamar || !$checkin || !$checkout || !$total_harga) {
    header("Location: booking.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Payment | Crystalkuta Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/payment.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <h3>Crystalkuta Hotel ★★★★</h3>
    <p>Secure Payment</p>
</header>

<!-- ================= PAYMENT ================= -->
<section class="payment">

    <!-- SUMMARY -->
    <div class="summary">
        <h2>Booking Summary</h2>

        <table>
            <tr>
                <td>Guest Name</td>
                <td><?= htmlspecialchars($nama); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= htmlspecialchars($email); ?></td>
            </tr>
            <tr>
                <td>Check-in</td>
                <td><?= $checkin; ?></td>
            </tr>
            <tr>
                <td>Check-out</td>
                <td><?= $checkout; ?></td>
            </tr>
            <tr class="total">
                <td>Total Payment</td>
                <td>IDR <?= number_format($total_harga, 0, ',', '.'); ?></td>
            </tr>
        </table>
    </div>

<div class="payment-method">
    <h2>Select Payment Method</h2>

    <form action="payment_process.php" method="POST" enctype="multipart/form-data">

        <!-- KIRIM DATA -->
        <input type="hidden" name="id_kamar" value="<?= $id_kamar; ?>">
        <input type="hidden" name="checkin" value="<?= $checkin; ?>">
        <input type="hidden" name="checkout" value="<?= $checkout; ?>">
        <input type="hidden" name="total_harga" value="<?= $total_harga; ?>">

        <!-- PAYMENT OPTION -->
        <label class="method">
            <input type="radio" name="payment_method" value="credit_card" onclick="showPaymentForm()" required>
            Credit Card
        </label>

        <label class="method">
            <input type="radio" name="payment_method" value="bank_transfer" onclick="showPaymentForm()">
            Bank Transfer
        </label>

        <!-- CREDIT CARD FORM -->
        <div id="creditCardForm" class="payment-extra" style="display:none;">
            <label>Card Number</label>
            <input type="text" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
        </div>

        <!-- BANK TRANSFER FORM -->
        <div id="bankTransferForm" class="payment-extra" style="display:none;">
            <label>Upload Payment Proof</label>
            <input type="file" name="payment_proof" accept="image/*">
        </div>

        <button type="submit" class="pay-btn">
            Pay Now
        </button>
    </form>
</div>


</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Crystalkuta Hotel</p>
</footer>
<script>
function showPaymentForm() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;

    document.getElementById('creditCardForm').style.display = 'none';
    document.getElementById('bankTransferForm').style.display = 'none';

    if (method === 'credit_card') {
        document.getElementById('creditCardForm').style.display = 'block';
    }

    if (method === 'bank_transfer') {
        document.getElementById('bankTransferForm').style.display = 'block';
    }
}
</script>
</body>
</html>
