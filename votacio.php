<?php
session_start();

require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

$usuari = $_SESSION['usuari'];
$consulta = $dades['consulta'];
$oberta = votacioOberta($consulta['tancament']);
$haVotat = jaHaVotat($usuari['email']);

// Comprovacions prèvies per ordre
if ($usuari['rol'] !== 'votant') {
    afegeixMissatge("Només els votants poden votar.");
    header('Location: benvinguda.php');
    exit;
}

if (!$oberta) {
    afegeixMissatge("La votació està tancada.");
    header('Location: resultats.php');
    exit;
}

if ($haVotat) {
    afegeixMissatge("Ja has votat. Gràcies!");
    header('Location: resultats.php');
    exit;
}

$errorVot = '';

// Processament del vot (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opcio = $_POST['opcio'] ?? '';

    // Validació de l'opció triada
    if (array_key_exists($opcio, $consulta['opcions'])) {
        guardaVot($usuari['email'], $opcio);
        afegeixMissatge("Vot registrat. Gràcies per participar!");
        header('Location: resultats.php');
        exit;
    } else {
        $errorVot = "Tria una de les opcions.";
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Emet el teu vot</h2>
<p style="font-weight: bold; font-size: 1.1rem;"><?= htmlspecialchars($consulta['pregunta']) ?></p>

<?php if ($errorVot): ?>
    <div class="alert-error"><?= htmlspecialchars($errorVot) ?></div>
<?php endif; ?>

<form action="" method="post">
    <div style="margin-bottom: 20px;">
        <?php foreach ($consulta['opcions'] as $clau => $text): ?>
            <div style="margin-bottom: 10px;">
                <label style="cursor: pointer;">
                    <input type="radio" name="opcio" value="<?= htmlspecialchars($clau) ?>" required>
                    <?= htmlspecialchars($text) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="btn">Enviar vot</button>
</form>

<?php require_once __DIR__ . '/inc/peu.php'; ?>