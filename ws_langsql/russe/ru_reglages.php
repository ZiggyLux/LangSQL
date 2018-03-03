<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ru_reglages.html                                   -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur.............. Marc CESARINI                                      -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, avec frames)     -->
<!-- Brève description... ru_reglages                                        -->
<!--                                                                         -->
<!-- Emplacement......... russe\ru_reglages.php                              -->
<!----------------------------------------------------------------------------->
<!-- GRANDES EVOLUTIONS                                                      -->
<!-- Date      Par    Description                                            
     17/7/12   Marc   Version initiale
     06/01/17  Marc   Accès DB avec PDO
-->
<!-- POINTS A TERMINER
-->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_sql.inc.php");
	include_once("../util/app_cod.inc.php");
	
	// Gestion des variables mutables côté serveur
	include_once("../util/app_mut.inc.php");

	include_once("../liste/liste.inc.php");

	include_once("app_ref_ruvoc.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc CESARINI">
<meta name="keywords" content="html,russe">
<link rel="stylesheet" type="text/css" href="../styles.css">
<script type="text/javascript" language="javascript" src="../scripts.js"></script>
<title>LangSQL Russe - R&eacute;glages</title>
</head>

<body onload="init_form()">
<?php include("menu_russe.inc.php"); ?>

<script type="text/javascript" language="javascript" src="../util/app_mut.js"></script>
<script type="text/javascript" language="javascript" >
<!--

/*----------------------------------------------------------------------------*/
/* Variable globales														  */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

// Variable de validation des champs
var goingto_pickup;

// Gestion des variables mutables côté navigateur
var tabMAA = new O_MutableAssocArray();

/*----------------------------------------------------------------------------*/
/* Chargement des données de la page                                          */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
    /* Connexion à la base de données */
    $dbh = connect_db();

	/* Initialisation du tableau des valeurs mutables */
	$tabMAA = new O_MutableAssocArray();

	/* Jointure sur l'identifiant de list ruvoc pour en récupérer le nom */
	if (isset($_POST["id_lisDef_ruvocInit"])
		&& isset($_POST["id_lisDef_ruvoc"])) {
		$id_lisDef_ruvocInit = $_POST["id_lisDef_ruvocInit"];
		$id_lisDef_ruvoc = $_POST["id_lisDef_ruvoc"];
	} else {
		$id_lisDef_ruvocInit = peek_ref_lstdef($dbh, D_LSQW_REF_USR_VOC_LSTDEF);
		$id_lisDef_ruvoc = $id_lisDef_ruvocInit;
	}
	if ($id_lisDef_ruvoc != 0) {
		$query =
			"SELECT str_nom FROM liste where id={$id_lisDef_ruvoc}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruvoc = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	if ($id_lisDef_ruvocInit != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_ruvocInit}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruvocInit = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	$tabMAA->push("id_lisDef_ruvoc",
		new O_Mutable($id_lisDef_ruvocInit, $id_lisDef_ruvoc));

	/* Jointure sur l'identifiant de list prono pour en récupérer le nom */
	if (isset($_POST["id_lisDef_pronoInit"])
		&& isset($_POST["id_lisDef_prono"])) {
		$id_lisDef_pronoInit = $_POST["id_lisDef_pronoInit"];
		$id_lisDef_prono = $_POST["id_lisDef_prono"];
	} else {
		$id_lisDef_pronoInit = peek_ref_lstdef($dbh, D_LSQW_REF_USR_PRO_LSTDEF);
		$id_lisDef_prono = $id_lisDef_pronoInit;
	}
	if ($id_lisDef_prono != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_prono}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_prono = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	if ($id_lisDef_pronoInit != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_pronoInit}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_pronoInit = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	$tabMAA->push("id_lisDef_prono",
		new O_Mutable($id_lisDef_pronoInit, $id_lisDef_prono));

	/* Jointure sur l'identifiant de list trafr pour en récupérer le nom */
	if (isset($_POST["id_lisDef_trafrInit"])
		&& isset($_POST["id_lisDef_trafr"])) {
		$id_lisDef_trafrInit = $_POST["id_lisDef_trafrInit"];
		$id_lisDef_trafr = $_POST["id_lisDef_trafr"];
	} else {
		$id_lisDef_trafrInit = peek_ref_lstdef($dbh, D_LSQW_REF_USR_TRA_LSTDEF);
		$id_lisDef_trafr = $id_lisDef_trafrInit;
	}
	if ($id_lisDef_trafr != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_trafr}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_trafr = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	if ($id_lisDef_trafrInit != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_trafrInit}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_trafrInit = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	$tabMAA->push("id_lisDef_trafr",
		new O_Mutable($id_lisDef_trafrInit, $id_lisDef_trafr));

	/* Jointure sur l'identifiant de list ruvrb pour en récupérer le nom */
	if (isset($_POST["id_lisDef_ruvrbInit"])
		&& isset($_POST["id_lisDef_ruvrb"])) {
		$id_lisDef_ruvrbInit = $_POST["id_lisDef_ruvrbInit"];
		$id_lisDef_ruvrb = $_POST["id_lisDef_ruvrb"];
	} else {
		$id_lisDef_ruvrbInit = peek_ref_lstdef($dbh, D_LSQW_REF_USR_VRB_LSTDEF);
		$id_lisDef_ruvrb = $id_lisDef_ruvrbInit;
	}
	if ($id_lisDef_ruvrb != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_ruvrb}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruvrb = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	if ($id_lisDef_ruvrbInit != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_ruvrbInit}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruvrbInit = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	$tabMAA->push("id_lisDef_ruvrb",
		new O_Mutable($id_lisDef_ruvrbInit, $id_lisDef_ruvrb));

	/* Jointure sur l'identifiant de list ruphr pour en récupérer le nom */
	if (isset($_POST["id_lisDef_ruphrInit"])
		&& isset($_POST["id_lisDef_ruphr"])) {
		$id_lisDef_ruphrInit = $_POST["id_lisDef_ruphrInit"];
		$id_lisDef_ruphr = $_POST["id_lisDef_ruphr"];
	} else {
		$id_lisDef_ruphrInit = peek_ref_lstdef($dbh, D_LSQW_REF_USR_PHR_LSTDEF);
		$id_lisDef_ruphr = $id_lisDef_ruphrInit;
	}
	if ($id_lisDef_ruphr != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_ruphr}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruphr = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	if ($id_lisDef_ruphrInit != 0) {
		$query = 
			"SELECT str_nom FROM liste where id={$id_lisDef_ruphrInit}";
		if (($result = $dbh->query($query)) === FALSE) {
		    echo 'Erreur dans la requête SQL : ';
		    echo $query;
		    exit();
		}
		
		$line_lisDef_ruphrInit = $result->fetch(PDO::FETCH_ASSOC)
			or die("Query failed");
		
		// Libère le resultset
		$result = NULL;
	}
	$tabMAA->push("id_lisDef_ruphr",
		new O_Mutable($id_lisDef_ruphrInit, $id_lisDef_ruphr));

	/* Mise à jour éventuelle si demandée et réinitialisation */
	if (isset($_POST["code_action"])
		&& ($_POST["code_action"] == "maj")) {
		
		if ($tabMAA->isEltDirty("id_lisDef_ruvoc")) {
			// Mise à jour dans la base de données
			poke_ref_lstdef($dbh, D_LSQW_REF_USR_VOC_LSTDEF, $id_lisDef_ruvoc);
			// Réalignement de la variable postée
			$id_lisDef_ruvocInit = $id_lisDef_ruvoc;
			$_POST["id_lisDef_ruvocInit"] = $id_lisDef_ruvocInit;
			// Réalignement de la valeur initiale sur la valeur courante dans tabMAA
			$tabMAA->commit("id_lisDef_ruvoc");
		}
		if ($tabMAA->isEltDirty("id_lisDef_prono")) {
			// Mise à jour dans la base de données
		    poke_ref_lstdef($dbh, D_LSQW_REF_USR_PRO_LSTDEF, $id_lisDef_prono);
			// Réalignement de la variable postée
			$id_lisDef_pronoInit = $id_lisDef_prono;
			$_POST["id_lisDef_pronoInit"] = $id_lisDef_pronoInit;
			// Réalignement de la valeur initiale sur la valeur courante dans tabMAA
			$tabMAA->commit("id_lisDef_prono");
		}
		if ($tabMAA->isEltDirty("id_lisDef_trafr")) {
			// Mise à jour dans la base de données
		    poke_ref_lstdef($dbh, D_LSQW_REF_USR_TRA_LSTDEF, $id_lisDef_trafr);
			// Réalignement de la variable postée
			$id_lisDef_trafrInit = $id_lisDef_trafr;
			$_POST["id_lisDef_trafrInit"] = $id_lisDef_trafrInit;
			// Réalignement de la valeur initiale sur la valeur courante dans tabMAA
			$tabMAA->commit("id_lisDef_trafr");
		}
		if ($tabMAA->isEltDirty("id_lisDef_ruvrb")) {
			// Mise à jour dans la base de données
		    poke_ref_lstdef($dbh, D_LSQW_REF_USR_VRB_LSTDEF, $id_lisDef_ruvrb);
			// Réalignement de la variable postée
			$id_lisDef_ruvrbInit = $id_lisDef_ruvrb;
			$_POST["id_lisDef_ruvrbInit"] = $id_lisDef_ruvrbInit;
			// Réalignement de la valeur initiale sur la valeur courante dans tabMAA
			$tabMAA->commit("id_lisDef_ruvrb");
		}
		if ($tabMAA->isEltDirty("id_lisDef_ruphr")) {
			// Mise à jour dans la base de données
		    poke_ref_lstdef($dbh, D_LSQW_REF_USR_PHR_LSTDEF, $id_lisDef_ruphr);
			// Réalignement de la variable postée
			$id_lisDef_ruphrInit = $id_lisDef_ruphr;
			$_POST["id_lisDef_ruphrInit"] = $id_lisDef_ruphrInit;
			// Réalignement de la valeur initiale sur la valeur courante dans tabMAA
			$tabMAA->commit("id_lisDef_ruphr");
		}
	}

    /* Déconnexion */
	disconnect_db($dbh);

?>
}
/*----------------------------------------------------------------------------*/
/* Validation champ par champ au chargement de la page                        */
/*----------------------------------------------------------------------------*/
function init_form() {
	goingto_pickup = false;
	
	// Initialisation du tableau associatif de variables mutables
	tabMAA.push("id_lisDef_ruvoc", new O_Mutable(
			<?php print "\"{$id_lisDef_ruvocInit}\"" ?>,
			<?php print "\"{$id_lisDef_ruvoc}\"" ?>));
	tabMAA.push("id_lisDef_prono", new O_Mutable(
			<?php print "\"{$id_lisDef_pronoInit}\"" ?>,
			<?php print "\"{$id_lisDef_prono}\"" ?>));
	tabMAA.push("id_lisDef_trafr", new O_Mutable(
			<?php print "\"{$id_lisDef_trafrInit}\"" ?>,
			<?php print "\"{$id_lisDef_trafr}\"" ?>));
	tabMAA.push("id_lisDef_ruvrb", new O_Mutable(
			<?php print "\"{$id_lisDef_ruvrbInit}\"" ?>,
			<?php print "\"{$id_lisDef_ruvrb}\"" ?>));
	tabMAA.push("id_lisDef_ruphr", new O_Mutable(
			<?php print "\"{$id_lisDef_ruphrInit}\"" ?>,
			<?php print "\"{$id_lisDef_ruphr}\"" ?>));

	// Affecte la visibilité de l'icône undo
	document.getElementById("udo_lisDef_ruvoc").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_ruvoc"))? "visible" : "hidden";
	document.getElementById("udo_lisDef_prono").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_prono"))? "visible" : "hidden";
	document.getElementById("udo_lisDef_trafr").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_trafr"))? "visible" : "hidden";
	document.getElementById("udo_lisDef_ruvrb").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_ruvrb"))? "visible" : "hidden";
	document.getElementById("udo_lisDef_ruphr").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_ruphr"))? "visible" : "hidden";
	
	// Affecte l'état activé/désactivé du bouton de mise à jour
	verifieActivationMAJ();
}

/*----------------------------------------------------------------------------*/
/* Fonction de pickup d'une liste                                             */
/*----------------------------------------------------------------------------*/
function pickup_liste(lsttyp) {
	var strEntType;
	var strNomVariableRetour;
	switch(lsttyp) {
		case 1: 
			strNomVariableRetour = "id_lisDef_ruvoc";
			strEntType = <?php echo D_LISTE_RUVOC ?>;
			break;
		case 2: 
			strNomVariableRetour = "id_lisDef_trafr";
			strEntType = <?php echo D_LISTE_RUVOC ?>;
			break;
		case 3: 
			strNomVariableRetour = "id_lisDef_prono";
			strEntType = <?php echo D_LISTE_RUVOC ?>;
			break;
		case 4: 
			strNomVariableRetour = "id_lisDef_ruvrb";
			strEntType = <?php echo D_LISTE_RUVRB ?>;
			break;
		case 5: 
			strNomVariableRetour = "id_lisDef_ruphr";
			strEntType = <?php echo D_LISTE_RUPHR ?>;
			break;
  	}
	document.formulaire.action = "../liste/listepickup.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomVarRetToForm("formulaire", strNomVariableRetour);
	addNomValVariableToForm("formulaire", "listetype", strEntType);
	
	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Restauration d'une variable à sa valeur initiale							  */
/*   provoquée par le clic sur l'icône UNDO									  */
/*----------------------------------------------------------------------------*/
function restaure_init(
	id,		// Identifiant du conteneur DOM de la variable à réinitialiser
	nom, 	// Identifiant du conteneur DOM du libellé de la variable
	udo		// Identifiant de l'icône UNDO à masquer (après l'action)
) {
    var idInit = id + "Init";
	var nomInit = nom + "Init";

	document.getElementById(id).value =
		document.getElementById(idInit).value;
	document.getElementById(nom).firstChild.nodeValue =
		document.getElementById(nomInit).value;

	// Cache l'icône UNDO
	document.getElementById(udo).style.visibility = "hidden";

	// Réinitialise la variable mutable dans le tableau côté navigateur
	tabMAA.reset(id);
	
	// Redétermine l'état du bouton d'invite de mise à jour
	verifieActivationMAJ();
}

/*----------------------------------------------------------------------------*/
/* Pilotage de l'apparence du bouton de mise à jour							  */
/*----------------------------------------------------------------------------*/
function verifieActivationMAJ() {
	// Vérifie l'existence de l'attribut disabled
	var fDejaDisabled =
		(document.getElementById("maj").attributes.getNamedItem("disabled") != null);
	if (tabMAA.isDirty()) {
		if (fDejaDisabled)
			// L'attribut existe, il faut le supprimer
			document.getElementById("maj").attributes.removeNamedItem("disabled");
	} else {
		if (!fDejaDisabled) {
			// L'attribut n'existe pas, il faut le créer
			var attr = document.createAttribute("disabled");
			document.getElementById("maj").setAttributeNode(attr);
		}
	}
}

/*----------------------------------------------------------------------------*/
/* Fonction de mise à jour des paramètres									  */
/*----------------------------------------------------------------------------*/
function maj_parametres() {
	document.formulaire.code_action.value = "maj";
	document.formulaire.action = "ru_reglages.php";
	document.formulaire.submit();
}

//-->
</script>
<h1>Russe - R&eacute;glages</h1>
<form name="formulaire" id="formulaire" method="POST">

<!-- Identifiants masqués servant au pickup -->
<input type="hidden" name="id_lisDef_ruvocInit" id="id_lisDef_ruvocInit"
	value=<?php print "'{$id_lisDef_ruvocInit}'" ?>/>
<input type="hidden" name="id_lisDef_ruvoc" id="id_lisDef_ruvoc"
	value=<?php print "'{$id_lisDef_ruvoc}'" ?>/>

<input type="hidden" name="id_lisDef_pronoInit" id="id_lisDef_pronoInit"
	value=<?php print "'{$id_lisDef_pronoInit}'" ?>/>
<input type="hidden" name="id_lisDef_prono" id="id_lisDef_prono"
	value=<?php print "'{$id_lisDef_prono}'" ?>/>

<input type="hidden" name="id_lisDef_trafrInit" id="id_lisDef_trafrInit"
	value=<?php print "'{$id_lisDef_trafrInit}'" ?>/>
<input type="hidden" name="id_lisDef_trafr" id="id_lisDef_trafr"
	value=<?php print "'{$id_lisDef_trafr}'" ?>/>

<input type="hidden" name="id_lisDef_ruvrbInit" id="id_lisDef_ruvrbInit"
	value=<?php print "'{$id_lisDef_ruvrbInit}'" ?>/>
<input type="hidden" name="id_lisDef_ruvrb" id="id_lisDef_ruvrb"
	value=<?php print "'{$id_lisDef_ruvrb}'" ?>/>

<input type="hidden" name="id_lisDef_ruphrInit" id="id_lisDef_ruphrInit"
	value=<?php print "'{$id_lisDef_ruphrInit}'" ?>/>
<input type="hidden" name="id_lisDef_ruphr" id="id_lisDef_ruphr"
	value=<?php print "'{$id_lisDef_ruphr}'" ?>/>

<!-- Libellés servant au reset -->
<!--   permet l'affichage du libellé de la variable initiale au clic de l'UNDO -->
<input type="hidden" name="nom_lisDef_ruvocInit" id="nom_lisDef_ruvocInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_ruvocInit['str_nom']))? hed_he($line_lisDef_ruvocInit['str_nom']): "")
		. "\""; ?>/>
<input type="hidden" name="nom_lisDef_pronoInit" id="nom_lisDef_pronoInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_pronoInit['str_nom']))? hed_he($line_lisDef_pronoInit['str_nom']): "")
		. "\""; ?>/>
<input type="hidden" name="nom_lisDef_trafrInit" id="nom_lisDef_trafrInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_trafrInit['str_nom']))? hed_he($line_lisDef_trafrInit['str_nom']): "")
		. "\""; ?>/>
<input type="hidden" name="nom_lisDef_ruvrbInit" id="nom_lisDef_ruvrbInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_ruvrbInit['str_nom']))? hed_he($line_lisDef_ruvrbInit['str_nom']): "")
		. "\""; ?>/>
<input type="hidden" name="nom_lisDef_ruphrInit" id="nom_lisDef_ruphrInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_ruphrInit['str_nom']))? hed_he($line_lisDef_ruphrInit['str_nom']): "")
		. "\""; ?>/>

<?php
    /* Connexion à la base de données */
    $dbh = connect_db();

    /* Requêtes SQL pour le chargement de la page */
	
    /* Printing results in HTML */
    print "<table width='800px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Param&egrave;tre</th>\n";
	print "\t\t<th width='400px'>Valeur</th>\n";
	print "\t\t<th width='50px'>Actions</th>\n";
    print "\t</tr>\n";

	/* Construction du tableau de paramètres */
	$iLine = 0;
    $iLineMax = 5;
	$fPair = false;
    while ($iLine < $iLineMax ) {

		/* By page walking: line and page counting */
		$iLine++;

		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		switch($iLine) {
		case 1:
				print "\t<tr class=\"{$trClass}\""
				  . " onclick=\"\">\n";
				print "\t\t<td>Liste par d&eacute;fault des mots russes &agrave; trouver</td>\n";
				print "\t\t<td id='nom_lisDef_ruvoc'>";
				if ($id_lisDef_ruvoc !=0) echo $line_lisDef_ruvoc['str_nom']; 
				print "</td>\n";
				print "\t\t<td>\n";
				print "\t\t\t<img onclick=\"pickup_liste(1)\""
				  . " src=\"../ico16-gen-pickup.gif\""
				  . " alt=\"Changer le choix\""
				  . "/>\n";
				print "\t\t\t<img id='udo_lisDef_ruvoc'" 
				  . " onclick=\"restaure_init("
				  . 	"'id_lisDef_ruvoc', 'nom_lisDef_ruvoc', 'udo_lisDef_ruvoc')\""
				  . " src=\"../ico16-undo.gif\""
				  . " alt=\"Revenir &agrave; la valeur initiale\""
				  . "/>\n";
				print "\t\t\t</td>\n";
				print "\t</tr>\n";
				break;
		case 2:
				print "\t<tr class=\"{$trClass}\""
				  . " onclick=\"\">\n";
				print "\t\t<td>Liste par d&eacute;fault des mots russes &agrave; traduire</td>\n";
				print "\t\t<td id='nom_lisDef_trafr'>";
				if ($id_lisDef_trafr !=0) echo $line_lisDef_trafr['str_nom']; 
				print "</td>\n";
				print "\t\t<td>\n";
				print "\t\t\t<img onclick=\"pickup_liste(2)\""
				  . " src=\"../ico16-gen-pickup.gif\""
				  . " alt=\"Changer le choix\""
				  . "/>\n";
				print "\t\t\t<img id='udo_lisDef_trafr'" 
				  . " onclick=\"restaure_init("
				  . 	"'id_lisDef_trafr', 'nom_lisDef_trafr', 'udo_lisDef_trafr')\""
				  . " src=\"../ico16-undo.gif\""
				  . " alt=\"Revenir &agrave; la valeur initiale\""
				  . "/>\n";
				print "\t\t\t</td>\n";
				print "\t</tr>\n";
				break;
		case 3:
				print "\t<tr class=\"{$trClass}\""
				  . " onclick=\"\">\n";
				print "\t\t<td>Liste par d&eacute;fault des mots russe &agrave; prononcer</td>\n";
				print "\t\t<td id='nom_lisDef_prono'>";
				if ($id_lisDef_prono !=0) echo $line_lisDef_prono['str_nom']; 
				print "</td>\n";
				print "\t\t<td>\n";
				print "\t\t\t<img onclick=\"pickup_liste(3)\""
				  . " src=\"../ico16-gen-pickup.gif\""
				  . " alt=\"Changer le choix\""
				  . "/>\n";
				print "\t\t\t<img id='udo_lisDef_prono'" 
				  . " onclick=\"restaure_init("
				  . 	"'id_lisDef_prono', 'nom_lisDef_prono', 'udo_lisDef_prono')\""
				  . " src=\"../ico16-undo.gif\""
				  . " alt=\"Revenir &agrave; la valeur initiale\""
				  . "/>\n";
				print "\t\t\t</td>\n";
				print "\t</tr>\n";
				break;
		case 4:
				print "\t<tr class=\"{$trClass}\""
				  . " onclick=\"\">\n";
				print "\t\t<td>Liste par d&eacute;fault des verbes</td>\n";
				print "\t\t<td id='nom_lisDef_ruvrb'>";
				if ($id_lisDef_ruvrb !=0) echo $line_lisDef_ruvrb['str_nom']; 
				print "</td>\n";
				print "\t\t<td>\n";
				print "\t\t\t<img onclick=\"pickup_liste(4)\""
				  . " src=\"../ico16-gen-pickup.gif\""
				  . " alt=\"Changer le choix\""
				  . "/>\n";
				print "\t\t\t<img id='udo_lisDef_ruvrb'" 
				  . " onclick=\"restaure_init("
				  . 	"'id_lisDef_ruvrb', 'nom_lisDef_ruvrb', 'udo_lisDef_ruvrb')\""
				  . " src=\"../ico16-undo.gif\""
				  . " alt=\"Revenir &agrave; la valeur initiale\""
				  . "/>\n";
				print "\t\t\t</td>\n";
				print "\t</tr>\n";
				break;
		case 5:
				print "\t<tr class=\"{$trClass}\""
				  . " onclick=\"\">\n";
				print "\t\t<td>Liste par d&eacute;fault des phrases</td>\n";
				print "\t\t<td id='nom_lisDef_ruphr'>";
				if ($id_lisDef_ruphr !=0) echo $line_lisDef_ruphr['str_nom']; 
				print "</td>\n";
				print "\t\t<td>\n";
				print "\t\t\t<img onclick=\"pickup_liste(5)\""
				  . " src=\"../ico16-gen-pickup.gif\""
				  . " alt=\"Changer le choix\""
				  . "/>\n";
				print "\t\t\t<img id='udo_lisDef_ruphr'" 
				  . " onclick=\"restaure_init("
				  . 	"'id_lisDef_ruphr', 'nom_lisDef_ruphr', 'udo_lisDef_ruphr')\""
				  . " src=\"../ico16-undo.gif\""
				  . " alt=\"Revenir &agrave; la valeur initiale\""
				  . "/>\n";
				print "\t\t\t</td>\n";
				print "\t</tr>\n";
				break;
		}
    }
    print "</table>\n";

    /* Déconnexion de la BD */
	disconnect_db($dbh);
?>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<br>
<input type="hidden" name="code_action" id="code_action" value=""/><?php
	print "\n<input type='submit' name='maj' id='maj'" .
		"\n\t value='Mettre &agrave; jour'" .
		"\n\tonclick=\"maj_parametres()\"/>";
?>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
