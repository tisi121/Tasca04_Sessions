<?php
require_once __DIR__ . '/vots.php';

// Cerca un usuari pel seu correu electrònic
function buscaUsuari(array $usuaris, string $email): ?array {
    foreach ($usuaris as $u) {
        if (strcasecmp($u['email'], $email) === 0) {
            return $u;
        }
    }
    return null;
}

// Retorna el temps transcorregut en format textual
function tempsDesDe(int $moment): string {
    $diferencia = time() - $moment;
    if ($diferencia < 60) {
        return "fa menys d'un minut";
    }
    $minuts = (int)floor($diferencia / 60);
    if ($minuts < 60) {
        return $minuts === 1 ? "fa 1 minut" : "fa {$minuts} minuts";
    }
    $hores = (int)floor($minuts / 60);
    if ($hores < 24) {
        return $hores === 1 ? "fa 1 hora" : "fa {$hores} hores";
    }
    $dies = (int)floor($hores / 24);
    return $dies === 1 ? "fa 1 dia" : "fa {$dies} dies";
}

// Comprova si la votació encara no ha arribat a la data límit
function votacioOberta(string $tancament): bool {
    $limit = strtotime($tancament);
    return $limit !== false && time() < $limit;
}

// Comprova si l'usuari ja ha registrat el seu vot al fitxer JSON
function jaHaVotat(string $email): bool {
    $vots = llegeixVots();
    return array_key_exists($email, $vots);
}

// Fa el recompte assegurant que totes les opcions existeixin a l'array retornat
function recompte(array $vots, array $opcions): array {
    $resultat = [];
    foreach (array_keys($opcions) as $clau) {
        $resultat[$clau] = 0;
    }
    foreach ($vots as $opcioVotada) {
        if (isset($resultat[$opcioVotada])) {
            $resultat[$opcioVotada]++;
        }
    }
    return $resultat;
}

// Guarda un missatge flash a la sessió
function afegeixMissatge(string $text): void {
    $_SESSION['missatge_flash'] = $text;
}

// Llegeix el missatge flash i l'esborra de la sessió immediatament
function llegeixMissatge(): ?string {
    if (isset($_SESSION['missatge_flash'])) {
        $msg = $_SESSION['missatge_flash'];
        unset($_SESSION['missatge_flash']);
        return $msg;
    }
    return null;
}