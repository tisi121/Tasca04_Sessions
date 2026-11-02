<?php
session_start();

// Si l'usuari ja té sessió activa, va a la benvinguda
if (isset($_SESSION['usuari'])) {
    header('Location: benvinguda.php');
    exit;
}

$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

// 1. Processament de la cookie 'darrera_visita'
$ara = time();
$textVisita = "Benvingut! És la teva primera visita.";

if (isset($_COOKIE['darrera_visita'])) {
    $ts = $_COOKIE['darrera_visita'];
    // Validar que sigui enter positiu i que no sigui una data futura
    if (ctype_digit((string)$ts) && (int)$ts > 0 && (int)$ts <= $ara) {
        $textVisita = "La teva darrera visita va ser " . tempsDesDe((int)$ts) . ".";
    }
}

// Actualitzem la cookie per a la propera visita (30 dies)
setcookie('darrera_visita', (string)$ara, [
    'expires'  => $ara + (30 * 86400),
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// 2. Cookie 'Recorda el meu email'
$emailRecordat = '';
$recordarMarcat = false;
if (isset($_COOKIE['email_recordat'])) {
    $emailValidat = filter_var($_COOKIE['email_recordat'], FILTER_VALIDATE_EMAIL);
    if ($emailValidat) {
        $emailRecordat = $emailValidat;
        $recordarMarcat = true;
    }
}

// 3. Processament del formulari de Login (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['contrasenya'] ?? '';
    $recordar = isset($_POST['recordar']);

    $usuari = buscaUsuari($dades['usuaris'], $email);
    $loginCorrecte = false;

    if ($usuari !== null) {
        // Suport dual: text pla (pràctica) o hash
        if ($pass === $usuari['contrasenya'] || password_verify($pass, $usuari['contrasenya'])) {
            $loginCorrecte = true;
        }
    }

    if ($loginCorrecte) {
        session_regenerate_id(true);

        $_SESSION['usuari'] = [
            'nom'   => $usuari['nom'],
            'email' => $usuari['email'],
            'rol'   => $usuari['rol'],
        ];
        $_SESSION['hora_login'] = time();
        $_SESSION['ultima_activitat'] = time();

        // Gestió de la cookie 'email_recordat'
        if ($recordar) {
            setcookie('email_recordat', $usuari['email'], [
                'expires'  => time() + (30 * 86400),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } else {
            setcookie('email_recordat', '', [
                'expires'  => time() - 3600,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        header('Location: benvinguda.php');
        exit;
    } else {
        header('Location: index.php?error=credencials');
        exit;
    }
}

// 4. Missatges d'error per GET
$errorsMap = [
    'credencials' => 'Credencials no vàlides.',
    'acces'       => 'Primer has d' . "'" . 'iniciar sessió.',
    'caducada'    => 'La sessió ha caducat per inactivitat.'
];
$missatgeError = $errorsMap[$_GET['error'] ?? ''] ?? null;

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Inici de Sessió</h2>
<p style="color: #64748b; font-size: 0.95rem;"><?= htmlspecialchars($textVisita) ?></p>

<?php if ($missatgeError): ?>
    <div class="alert-error"><?= htmlspecialchars($missatgeError) ?></div>
<?php endif; ?>

<form action="" method="post">
    <div style="margin-bottom: 15px;">
        <label for="email" style="display:block; margin-bottom:5px; font-weight:bold;">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($emailRecordat) ?>" required style="width:100%; padding:8px; box-sizing:border-box;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="contrasenya" style="display:block; margin-bottom:5px; font-weight:bold;">Contrasenya:</label>
        <input type="password" id="contrasenya" name="contrasenya" required style="width:100%; padding:8px; box-sizing:border-box;">
    </div>

    <div style="margin-bottom: 20px;">
        <label>
            <input type="checkbox" name="recordar" <?= $recordarMarcat ? 'checked' : '' ?>> Recorda el meu email
        </label>
    </div>

    <button type="submit" class="btn">Entrar</button>
</form>

<?php require_once __DIR__ . '/inc/peu.php'; ?>