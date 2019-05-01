<?php
require_once('inc/init.inc.php');

If(isset($_SESSION['success'])){
	$error .= $_SESSION['success'];
	unset($_SESSION['success']);
	// Grace à la session on peut récuperer ici un message généré dans le fichier formulaire_produit.php
}

require_once('inc/header.inc.php');

$resultat = $pdo -> query('SELECT * FROM logement');
$logements = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$html .= '<table class="table table-dark table-fluide">';
$html .= '<tr>';

for($i = 0; $i < $resultat -> columnCount() ; $i++) { 
	$champs = $resultat -> getColumnMeta($i);
	$html.= '<th>' . $champs['name'] . '</th>';
}

$html .= '</tr>';

	foreach ($logements as $value) {
		$html.= '<tr>';
		foreach ($value as $indice => $info) {
			if($indice == 'photo'){
				$html .= '<td><img src="'. RACINE_SITE . 'photo/' . $info . '" height="50px"/></td>';
			}
			else{
				$html.= '<td>' . $info . '</td>';
			}
		}
	
}

$html .= '</table>';

?>

<!-- DEBUT HTML -->

<h1>Gestion des produits</h1>

		<?= $error ?>

		<?= $html ?>

<!-- FIN HTML -->
<?php
require_once('inc/footer.inc.php');
?>
