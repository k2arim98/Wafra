<?php
session_start();
include '../PHP/config.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['user_email'];

$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$wilayas = [
    "Adrar", "Chlef", "Laghouat", "Oum El Bouaghi", "Batna", "Béjaïa", "Biskra", "Béchar", "Blida", "Bouira",
    "Tamanrasset", "Tébessa", "Tlemcen", "Tiaret", "Tizi Ouzou", "Alger", "Djelfa", "Jijel", "Sétif", "Saïda",
    "Skikda", "Sidi Bel Abbès", "Annaba", "Guelma", "Constantine", "Médéa", "Mostaganem", "M'Sila", "Mascara",
    "Ouargla", "Oran", "El Bayadh", "Illizi", "Bordj Bou Arréridj", "Boumerdès", "El Tarf", "Tindouf", "Tissemsilt",
    "El Oued", "Khenchela", "Souk Ahras", "Tipaza", "Mila", "Aïn Defla", "Naâma", "Aïn Témouchent", "Ghardaïa", "Relizane"
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Commande - Wafra</title>
    <link rel="stylesheet" href="/Wafra/CSS/confirm_order.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
    </nav>

    <main>
        <section class="confirmation-section">
            <div class="container">
                <h1>Confirmer la Commande</h1>

                <form action="../PHP/place_order.php" method="post">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Wilaya</label>
                        <select name="wilaya" required>
                            <option value="">-- Sélectionnez une wilaya --</option>
                            <?php foreach ($wilayas as $wilaya): ?>
                                <option value="<?php echo $wilaya; ?>"><?php echo $wilaya; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Adresse de livraison</label>
                        <textarea name="address" rows="3" required placeholder="Entrez votre adresse complète ici..."></textarea>
                    </div>

                    <button href="confirm_order.php" type="submit" class="cta-button">Valider la Commande</button>
                </form>
            </div>
        </section>
    </main>

</body>
</html>
