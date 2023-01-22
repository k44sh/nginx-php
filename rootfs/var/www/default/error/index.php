<?php
$codes  = array(
	400 => array(
		"Mauvaise Requête",
		"La demande ne peut être satisfaite en raison d'une mauvaise syntaxe."
	),
	401 => array(
		"Erreur de connexion",
		 "Il semble que le mot de passe et/ou le nom d'utilisateur que vous avez entré est incorrect."
	),
	403 => array(
		"Interdit",
		"Désolé, cette ressource n'est pas accessible."
	),
	404 => array(
		"Page Manquante",
		"Nous sommes désolés, mais la page dont vous êtes à la recherche n'existe pas ou est manquante."
	),
	405 => array(
		"Méthode non autorisée",
		" La méthode spécifiée dans la ligne de requête ne est pas autorisée pour la ressource spécifiée"
	),
	408 => array(
		"Timeout",
		"Votre navigateur n'a pas pu envoyer une demande dans le délai imparti par le serveur"
	),
	414 => array(
		"URL",
		"L'URL que vous avez entrée est plus longue que la longueur maximale autorisée."
	)
);

if ( !empty( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
	$status = $_SERVER[ 'REDIRECT_STATUS' ];
	foreach ( $codes as $code => $tab ) {
		if ( $status == $code ) {
			$errortitle = $codes[ $status ][ 0 ];
			$message	= $codes[ $status ][ 1 ];
			break;
		}
		else {
			$errortitle = "Erreur";
			$message	= "Désolé, mais il y a une erreur ...";
		}
	}
}
else {
	$errortitle = "Erreur";
	$message	= "Désolé, mais il y a une erreur ...";
}
?>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="60; URL=https://www.google.com">
	<link rel="shortcut icon" href="/favicon.ico">
	<title>Hmmm..</title>
	<style>
		html {
			color: #333;
			font-family: "Lucida Console", Courier, monospace;
			font-size: 14px;
			background: #eeeeee;
		}
		.content {
			margin: 0 auto;
			width: 1000px;
			margin-top: 20px;
			padding: 10px 0 10px 0;
			border: 1px solid #EEE;
			background: none repeat scroll 0 0 white;
			box-shadow: 0 5px 10px -5px rgba(0, 0, 0, 0.5);
			position: relative;
		}
		h1 {
			font-size: 18px;
			text-align: center;
		}
		h1.title {
			color: red;
		}
		h2 {
			font-size: 16px;
			text-align: center;
		}
		p {
			text-align: center;
		}
		hr {
			border: #fe4902 solid 1px;
		}
	</style>
</head>
<body>
	<div class="content">
		<?php
			echo ( "<h1>" . $errortitle . "</h1>" );
			echo ( "<p>" . $message . "</p>" );
		?>
		<hr>
	</div>
</body>
</html>
