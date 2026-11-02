<?php
session_start();

require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

$usuari = $_SESSION['usuari'];
$consulta = $dades['consulta'];
$oberta = votacioOberta($consulta['tancament']);
$haVotat = jaHaVotat($usuari['email']);
$tempsSessio = tempsDesDe($_SESSION['hora_login']);

// Format de data: 18/12/2026 a les 14:00
$dataTimestamp = strtotime($consulta['tancament']);
$dataLimitFormatted = date('d/m/Y \a \l\e\s H:i', $dataTimestamp);

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Hola, <?= htmlspecialchars($usuari['nom']) ?>!</h2>
<p style="color: #64748b;">Vas iniciar sessió <?= htmlspecialchars($tempsSessio) ?>.</p>

<div style="background: rgba(0,0,0,0.03); padding: 15px; border-radius: 6px; margin: 20px 0;">
    <h3 style="margin-top: 0;"><?= htmlspecialchars($consulta['pregunta']) ?></h3>
    <p>
        <strong>Estat de la votació:</strong> 
        <?= $oberta ? "<span style='color:#16a34a;'>Oberta</span> (fins el {$dataLimitFormatted})" : "<span style='color:#dc2626;'>Tancada</span>" ?>
    </p>
</div>

<div>
    <?php if ($usuari['rol'] === 'admin'): ?>
        <a href="resultats.php" class="btn">Veure Resultats</a>
    <?php elseif ($haVotat): ?>
        <p>Ja has votat. <a href="resultats.php" class="btn">Consultar resultats</a></p>
    <?php elseif ($oberta): ?>
        <a href="votacio.php" class="btn">Votar ara</a>
    <?php else: ?>
        <p>La votació està tancada. <a href="resultats.php" class="btn">Veure Resultats</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/inc/peu.php'; ?>