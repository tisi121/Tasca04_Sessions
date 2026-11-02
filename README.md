# TASCA-04: Votacions de l'institut

## Preguntes de reflexió

### 1. Per què guardem qui ha votat al servidor (al fitxer de vots) i no en una cookie ha_votat? Què podria fer un alumne si ho guardéssim en una cookie?
Guardem el registre al servidor perquè les dades del client no són de confiança. Si es guardés en una cookie, qualsevol alumne podria esborrar-la des de les eines de desenvolupador del navegador o utilitzar la navegació privada per votar tantes vegades com volgués.

### 2. Què passa si un usuari canvia el valor de la cookie darrera_visita a les eines del navegador? Com ho heu controlat?
Si l'usuari manipula la cookie (posant text, 0 o una data futura), l'aplicació podria generar avisos (*warnings*) o càlculs incoherents. Ho hem controlat comprovant amb `ctype_digit()`, verificant que sigui major que 0 i que el valor sigui menor o igual al timestamp actual (`time()`); si no compleix aquests criteris, es descarta i es tracta com a primera visita.

### 3. Quina diferència hi ha entre el timeout de 5 minuts de la sessió i els 30 dies de la cookie darrera_visita? On es guarda cada dada?
El timeout de la sessió es gestiona al servidor mitjançant un timestamp guardat a `$_SESSION` que es destrueix si passen 5 minuts d'inactivitat. En canvi, `darrera_visita` és una cookie persistent que es guarda al navegador del client durant 30 dies i sobreviu fins i tot després de tancar el navegador.

### 4. A index.php, per què no podem mostrar l'hora que acabem de guardar a darrera_visita llegint `$_COOKIE['darrera_visita']` a la mateixa petició?
Perquè `$_COOKIE` només conté les cookies que el navegador ha enviat a la capçalera de la petició HTTP actual. La funció `setcookie()` envia la cookie cap al navegador a la resposta HTTP, per la qual cosa no estarà disponible dins de l'array superglobal `$_COOKIE` fins a la següent petició.