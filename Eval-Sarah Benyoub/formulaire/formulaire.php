<?php

require_once('inc/init.inc.php');

debug($_POST);
debug($_POST);


if($_POST){

	// // //
	// // //  VERIFICATIONS DES CHAMPS // // //
	// // //	

	// Verification : titre
	if(empty($_POST['titre'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser un titre.</div>';
	}

	// Verification : adresse
	if(empty($_POST['adresse'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser une adresse.</div>';
	}

	// Verification : ville
	if(empty($_POST['ville'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser une ville.</div>';
	}

	// Verification : code postal
	if(empty($_POST['cp'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser un code postal.</div>';
	}
	else{
		$verif_cp = preg_match('#^[z0-9]{5}+$#',$_POST['cp']);
		if(!$verif_cp){
			$error .= '<div class="alert alert-danger">Le code postal doit être composé de 5 chiffres.</div>';
		}
	}

	// Verification : surface
	if(empty($_POST['surface'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser une surface.</div>';
	}
	else{
		$verif_surface = preg_match('#^[z0-9]+$#',$_POST['surface']);
		if(!$verif_surface){
			$error .= '<div class="alert alert-danger">La surface doit être un nombre entier.</div>';
		}
	}

	// Verification : prix
	if(empty($_POST['prix'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser un prix.</div>';
	}
	else{
		$verif_prix = preg_match('#^[z0-9]+$#',$_POST['prix']);
		if(!$verif_prix){
			$error .= '<div class="alert alert-danger">Le prix doit être un nombre entier.</div>';
		}
	}

	// Verification : type
	if(empty($_POST['type'])){
		$error .= '<div class="alert alert-danger">Veuillez préciser un type.</div>';
	}

	// Verification : photo
	//Nom générique par défaut (dans cas ou le produit n'a pas de photo)
	$photo_bdd = 'default.jpg';
	
	
	if(isset($_POST['photo_actuelle'])){		
		$photo_bdd = $_POST['photo_actuelle'];	
	}

	if(!empty($_FILES['photo']['name'])){
		// Cela signifie qu'une photo a été postée dans le form
		
		$photo_bdd = time() . '_' . rand(1, 9999) . $_FILES['photo']['name'];
		// On modifie le nom de la photo pour éviter les doublons ex : 
		// Chat.jpg
		// 1540000000_ref_465_chat.jpg
		
		$photo_dir = RACINE_SERVEUR . RACINE_SITE . 'photo/';

		if($_FILES['photo']['size'] > 2000000){
			$error .= '<div class="alert alert-danger">Veuillez choisir une photo de 2Mo max</div>';
		}	
		
		$ext = array('image/jpeg', 'image/png', 'image/gif');
		
		if(!in_array($_FILES['photo']['type'], $ext)){	
			// Si le type de l'image uploadée n'est pas l'un des types stockés dans $ext, alors erreur
			$error .= '<div class="alert alert-danger">Veuillez choisir une au format JPG, JPEG, PNG ou GIF</div>';
		}
		
		if($_FILES['photo']['error'] == '0' && empty($error)){
			// OK on peut enregistrer la photo sur le serveur
			if(!copy($_FILES['photo']['tmp_name'], $photo_dir . $photo_bdd)){
				$error .= '<div class="alert alert-danger">Problème à l\'enregistrement de la photo</div>';
			}
		}
	}//-IF !empty($_FILES..)

	// // //
	// // //  FIN DES VERIFICATIONS DES CHAMPS  // // //
	// // //

	if(empty($error)){
		// SI $error est vide cela signifie que toutes les conditions sont respectées
		// on enregistre le logement dans la BDD
		$resultat = $pdo -> prepare("INSERT INTO logement (titre, adresse, ville, cp, surface, prix, photo, type, description) VALUES (:titre, :adresse, :ville, :cp, :surface, :prix, :photo, :type, :description)");
	
		// STR
		$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
		$resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
		$resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
		$resultat -> bindParam(':type', $_POST['type'], PDO::PARAM_STR);
		$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);
		//PHOTO
		$resultat -> bindParam(':photo', $photo_bdd, PDO::PARAM_STR);
		// INT
		$resultat -> bindParam(':cp', $_POST['cp'], PDO::PARAM_INT);
		$resultat -> bindParam(':surface', $_POST['surface'], PDO::PARAM_INT);
		$resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);

		if($resultat -> execute()){
			header('location:' . RACINE_SITE . 'gestion_maisons.php?validation=succes');
			
			$id_produit_add = $pdo -> lastInsertId();
			// lastInsertId, méthode de pdo nous retourne le dernier ID enregistré en BDD
			
			$_SESSION['success'] = '<div class="alert alert-success">Félicitations le produit N°' . $id_produit_add . ' a été ajouté avec succèss</div>';
			//On stocke dans la session un message de félicitations, pour pouvoir l'afficher dans la page de destination. 
		}
	}

} // --- FIN $_POST

require_once('inc/header.inc.php');



?>

<!-- DEBUT HTML -->
<br>
<h1>Formulaire logement</h1><br><hr>

<div class="row">
	<div class="col-md-12">
		<form method="post" action="" enctype="multipart/form-data">
			<?= $error ?>

			<div >
				<label>Titre :</label><br>
				<input type="text" name="titre" class="form-control"><br><br>
			</div>
			<div >
				<label>Adresse :</label><br>
				<input type="text" name="adresse" class="form-control"><br><br>
			</div>
			<div >
				<label>Ville :</label><br>
				<input type="text" name="ville" class="form-control"><br><br>
			</div>
			<div >
				<label>Code postal :</label><br>
				<input type="text" name="cp" class="form-control"><br><br>
			</div>
			<div >
				<label>Surface :</label><br>
				<input type="text" name="surface" class="form-control"><br><br>
			</div>
			<div >
				<label>Prix :</label><br>
				<input type="text" name="prix" class="form-control"><br><br>
			</div>
			<div >
				<label>Photo :</label><br>
				<?php if($_POST['photo']) : ?>
				<img src="<?= RACINE_SITE ?>photo/<?= $_POST['photo'] ?>" width="50px" />
				<input type="hidden" name="photo_actuelle" /><br><br>
				<?php endif; ?>
					
				<input type="file" class="form-control" name="photo"/><br><br>

			</div>
			<div >
				<label>Type de logement :</label><br>
				<select name="type" class="form-control">
					<option value="1">Location</option>
					<option value="2">Vente</option>
				</select><br><br>
			</div>
			<div>
				<label>Description :</label><br>
				<textarea name="description" class="form-control"></textarea><br><br>
			</div>

			<div class="form-group">
				<input type="submit" name="Valider" class="btn">
				
			</div>


			
		</form>
		
	</div>

</div>



<!-- FIN HTML -->

<?php

require_once('inc/footer.inc.php');

?>