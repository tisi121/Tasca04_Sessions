<?php
session_start();

require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

$usuari = $_SESSION['usuari'];
$consulta = $dades['consulta'];
$oberta = votacioOberta($consulta['tancament']);
$haVotat = jaHaVotat($usuari['email']);

// Control d'accés als resultats
$potVeure = ($usuari['rol'] === 'admin') || $haVotat || (!$oberta);

if (!$potVeure) {
    afegeixMissatge("Podràs veure els resultats quan hagis votat.");
    header('Location: votacio.php');
    exit;
}

$vots = llegeixVots();
$totals = recompte($vots, $consulta['opcions']);
$totalVots = count($vots);

// Nombre total de votants censats
$censVotants = 0;
foreach ($dades['usuaris'] as $u) {
    if ($u['rol'] === 'votant') {
        $censVotants++;
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Resultats de la consulta</h2>
<p><strong>Pregunta:</strong> <?= htmlspecialchars($consulta['pregunta']) ?></p>

<table>
    <thead>
        <tr>
            <th>Opció</th>
            <th style="width: 15%;">Vots</th>
            <th style="width: 20%;">Percentatge</th>
            <th style="width: 35%;">Progrés</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($consulta['opcions'] as $clau => $etiqueta): ?>
            <?php 
                $numVots = $totals[$clau] ?? 0;
                $pct = $totalVots > 0 ? (int)round(($numVots / $totalVots) * 100) : 0;
            ?>
            <tr>
                <td><?= htmlspecialchars($etiqueta) ?></td>
                <td><?= $numVots ?></td>
                <td><?= $pct ?>%</td>
                <td>
                    <div class="barra-fons">
                        <div class="barra-progres" style="width: <?= $pct ?>%;"></div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p style="margin-top: 25px; font-weight: bold;">
    Participació: <?= $totalVots ?> de <?= $censVotants ?> votants
</p>

<?php require_once __DIR__ . '/inc/peu.php'; ?>