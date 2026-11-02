<?php
// Gestió de cookie de tema (clar / fosc)
if (isset($_GET['tema']) && in_array($_GET['tema'], ['clar', 'fosc'], true)) {
    $temaActual = $_GET['tema'];
    setcookie('tema', $temaActual, [
        'expires'  => time() + (30 * 86400),
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    $temaActual = ($_COOKIE['tema'] ?? '') === 'fosc' ? 'fosc' : 'clar';
}

$usuariActual = $_SESSION['usuari'] ?? null;
$missatge = llegeixMissatge();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votacions de l'Institut</title>
    <style>
        :root {
            --bg-color: <?= $temaActual === 'fosc' ? '#1e1e2f' : '#f4f6f8' ?>;
            --card-bg: <?= $temaActual === 'fosc' ? '#27293d' : '#ffffff' ?>;
            --text-color: <?= $temaActual === 'fosc' ? '#f0f0f0' : '#333333' ?>;
            --border-color: <?= $temaActual === 'fosc' ? '#3d405b' : '#e2e8f0' ?>;
        }
        body { font-family: sans-serif; background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 20px; }
        header { background: var(--card-bg); padding: 15px 25px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        nav a { margin-right: 15px; text-decoration: none; color: #0284c7; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 700px; margin: 0 auto; background: var(--card-bg); padding: 25px; border-radius: 8px; border: 1px solid var(--border-color); }
        .flash { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .btn { display: inline-block; padding: 8px 16px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #0369a1; }
        .barra-fons { background: var(--border-color); border-radius: 4px; height: 16px; width: 100%; overflow: hidden; }
        .barra-progres { background: #0284c7; height: 100%; }
    </style>
</head>
<body>
<?php if ($usuariActual): ?>
<header>
    
    <nav>
        <a href="benvinguda.php">Inici</a>
        <?php if ($usuariActual['rol'] === 'votant'): ?>
            <a href="votacio.php">Votar</a>
        <?php endif; ?>
        <a href="resultats.php">Resultats</a>
        <a href="logout.php" style="color: #ef4444;">Tancar sessió</a>
    </nav>
    <div>
        Hola, <strong><?= htmlspecialchars($usuariActual['nom']) ?></strong> | 
        <a href="?tema=<?= $temaActual === 'fosc' ? 'clar' : 'fosc' ?>" style="text-decoration:none; font-size:13px;">
            <?= $temaActual === 'fosc' ? '☀️ Mode Clar' : '🌙 Mode Fosc' ?>
        </a>
    </div>
</header>
<?php endif; ?>

<main class="container">
<?php if ($missatge): ?>
    <div class="flash"><?= htmlspecialchars($missatge) ?></div>
<?php endif; ?>