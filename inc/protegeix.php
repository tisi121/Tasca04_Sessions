<?php
// Timeout de 5 minuts = 300 segons
const TIMEOUT_SEGONS = 300;

if (!isset($_SESSION['usuari'])) {
    header('Location: index.php?error=acces');
    exit;
}

// Comprovació d'inactivitat
if (isset($_SESSION['ultima_activitat'])) {
    if ((time() - $_SESSION['ultima_activitat']) > TIMEOUT_SEGONS) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
        header('Location: index.php?error=caducada');
        exit;
    }
}

// Actualització del timestamp d'activitat
$_SESSION['ultima_activitat'] = time();