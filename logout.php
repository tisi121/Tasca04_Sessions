<?php
session_start();

$nomUsuari = $_SESSION['usuari']['nom'] ?? '';

// Buidar variables de sessió
$_SESSION = [];

// Esborrar la cookie de sessió
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sessió al servidor
session_destroy();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Sessió tancada</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; }
        a { color: #0284c7; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
<div class="card">
    <h2>Fins la propera<?= $nomUsuari !== '' ? ', ' . htmlspecialchars($nomUsuari) : '' ?>!</h2>
    <p>Has tancat la teva sessió correctament.</p>
    <p><a href="index.php">&larr; Tornar a entrar</a></p>
</div>
</body>
</html>