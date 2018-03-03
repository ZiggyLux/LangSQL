<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruphrquery_ruphr.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Russe: interrogation phrases FR->RU                -->
<!--					  Première partie: entrée paramètres 				 -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_sql.inc.php");
	include_once("../util/app_cod.inc.php");

	include_once("../liste/liste.inc.php");

	include_once("app_ref_ruvoc.inc.php");

	include_once("../debug_tools/common.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,phrase,query,traduction">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Phrases en russe - Traduction du fran&ccedil;ais au russe</title>
</head>
<body onload="init_form()">
<?php include("menu_russe.inc.php"); ?>

<script type="text/javascript" language="javascript" src="../util/app_mut.js"></script>

<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variable globales														  */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var listetype = <?php echo D_LISTE_RUPHR; ?>;

/*---------------------------- Variables de validation -----------------------*/
var bullet_ok= "../greenbullet.gif";
var bullet_nok= "../redbullet.gif";

// Variable de validation des champs
var valid_form;
var goingto_pickup;		

	// Variables de validation champ par champ
	var valid_lgrtest;		var bullet_lgrtest;
	var LGRMIN = 1;
	var LGRMAX = 30;

// Gestion du mode tout/liste
var testTout = <?php
	if ((isset($_POST["etendue"])) && ($_POST["etendue"]=="T"))
		echo "true";
	else
		echo "false";
?>;

// Gestion des variables mutables côté navigateur
var tabMAA = new O_MutableAssocArray();

/*----------------------------------------------------------------------------*/
/* Chargement des données de la page                                          */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
    /* Connexion à la base de données */
    $dbh = connect_db();

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
		$query = "SELECT str_nom FROM liste where id={$id_lisDef_ruphr}";
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
	tabMAA.push("id_lisDef_ruphr", new O_Mutable(
			<?php print "\"{$id_lisDef_ruphrInit}\"" ?>,
			<?php print "\"{$id_lisDef_ruphr}\"" ?>));

	// Affecte la visibilité de l'icône undo
	document.getElementById("udo_lisDef_ruphr").style.visibility =
		(tabMAA.isEltDirty("id_lisDef_ruphr"))? "visible" : "hidden";

	// Validation des champs de la page
    onblur_lgrtest();
}

/*----------------------------------------------------------------------------*/
/* Validation au SUBMIT                                                       */
/*     UI: La fonction affiche une "alert" si une zone pose un problème       */
/*     retour: window.event.returnValue=false en cas de problème              */
/*             document.formulaire.XXX.focus() sur la zone à problème          */
/*----------------------------------------------------------------------------*/
function isvalid() {
    if (!valid_lgrtest) { // Problème sur la zone "lgrtest"
        alert("La longueur du test doit être comprise entre " + LGRMIN + " et " + LGRMAX);
        document.getElementById("id_lgrtest").focus();
        return false;
    }
	
	return true;
}

/*----------------------------------------------------------------------------*/
/* Affecte le drapeau de validité générale de la page                         */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = valid_lgrtest;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "id_lgrtest"                                           */
/*     retour: valid_lgrtest est faux si la zone est vide ou hors [1, 30]	  */
/*             bull_lgrtest est rattaché à une bulle ok ou nok                */
/*----------------------------------------------------------------------------*/
function onblur_lgrtest() {

    // Suppression des espaces de tête
	document.formulaire.id_lgrtest.value = 
		str_supesptete(document.formulaire.id_lgrtest.value);

	if ((document.formulaire.id_lgrtest.value.length != 0)
		&& ((document.formulaire.id_lgrtest.value * 1) >= LGRMIN)
		&& ((document.formulaire.id_lgrtest.value * 1) <= LGRMAX))
		valid_lgrtest = true;
	else
		valid_lgrtest = false;
	
    bullet_lgrtest=(valid_lgrtest)? bullet_ok: bullet_nok;
    document.formulaire.bull_lgrtest.src=bullet_lgrtest;

    issubmitable();
    return valid_lgrtest;
}

/*----------------------------------------------------------------------------*/
/* Fonction de pickup d'une liste                                             */
/*----------------------------------------------------------------------------*/
function pickup_liste(lsttyp) {
	document.formulaire.action = "../liste/listepickup.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomVarRetToForm("formulaire", "id_lisDef_ruphr");
	addNomValVariableToForm("formulaire", "listetype", listetype);

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
}

/*----------------------------------------------------------------------------*/
/* Gestion du bouton radio Tout/Liste										  */
/*----------------------------------------------------------------------------*/
function on_etendue(str) {
	switch(str) {
	case "tout":
		testTout = true;
		
		// Désactive la ligne de sélection de la liste
		document.getElementById("lig_lisDef_ruphr").style.visibility = "hidden";
		break;
	case "liste":
	default:
		testTout = false;
		
		// Active la ligne de sélection de la liste
		document.getElementById("lig_lisDef_ruphr").style.visibility = "visible";
	}
}
//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des éléments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Phrases russes - Traduction du fran&ccedil;ais au russe</h1>
<form name="formulaire" id="formulaire" method="POST" 
	action="ruphrquery_ruphr_qry.php" onsubmit="return isvalid();">

<!-- Identifiants masqués servant au pickup -->
<input type="hidden" name="id_lisDef_ruphrInit" id="id_lisDef_ruphrInit"
	value=<?php print "'{$id_lisDef_ruphrInit}'" ?>/>
<input type="hidden" name="id_lisDef_ruphr" id="id_lisDef_ruphr"
	value=<?php print "'{$id_lisDef_ruphr}'" ?>/>

<!-- Libellés servant au reset -->
<!--   permet l'affichage du libellé de la variable initiale au clic de l'UNDO -->
<input type="hidden" name="nom_lisDef_ruphrInit" id="nom_lisDef_ruphrInit"
	value=<?php print "\"" 
		. ((isset($line_lisDef_ruphrInit['str_nom']))? hed_he($line_lisDef_ruphrInit['str_nom']): "")
		. "\""; ?>/>

<fieldset style="width:400px">
<legend>&Eacute;tendue du test</legend>
<label for="tout">Toutes les phrases</label>
<input type="radio" name="etendue" id="tout" value="T" onclick="on_etendue('tout')"
	<?php if (isset($_POST["etendue"])) print ($_POST["etendue"]=="T")? "checked='checked'": ""; ?>
/>
<label for="liste">Les phrases d'une liste</label>
<input type="radio" name="etendue" id="liste" value="L" onclick="on_etendue('liste')"
	<?php if (isset($_POST["etendue"]))
			  print ($_POST["etendue"]=="L")? "checked='checked'": "";
		  else
		  	  print "checked='checked'";
	?>
/>
</fieldset>
<br/>
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
    $iLineMax = 2;
	$fPair = false;
    while ($iLine < $iLineMax ) {

		/* By page walking: line and page counting */
		$iLine++;

		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		switch($iLine) {
		case 1:
			print "\t<tr class=\"{$trClass}\" id=\"lig_lisDef_ruphr\""
			  . " onclick=\"\">\n";
			print "\t\t<td>Liste des vocables &agrave; tester</td>\n";
			print "\t\t<td id='nom_lisDef_ruphr'>";
			if ($id_lisDef_ruphr !=0) echo $line_lisDef_ruphr['str_nom']; 
			print "</td>\n";
			print "\t\t<td>\n";
			print "\t\t\t<img onclick=\"pickup_liste()\""
			  . " src=\"../ico16-gen-pickup.gif\""
			  . " alt=\"Changer le choix\""
			  . "/>\n";
			print "\t\t\t<img id='udo_lisDef_ruphr'" 
			  . " onclick=\"restaure_init("
			  . 	"'id_lisDef_ruphr', 'nom_lisDef_ruphr', 'udo_lisDef_ruphr')\""
			  . " src=\"../ico16-undo.gif\""
			  . " alt=\"Revenir à&agrave; la valeur initiale\""
			  . "/>\n";
			print "\t\t\t</td>\n";
			print "\t</tr>\n";
			break;
		case 2:
			print "\t<tr class=\"{$trClass}\" onclick=\"\">\n";
			print "\t\t<td>Nombre de vocables &agrave; tester</td>\n";
			print "\t\t<td>"
			  . "<input type='text' name='id_lgrtest' id='id_lgrtest' size='2' maxlength='2'"
        	  . " value='" . ((isset($_POST["id_lgrtest"]))? $_POST["id_lgrtest"]: "8"). "'"
			  .		" onblur='onblur_lgrtest()'/>"
			  . "&nbsp;<img name='bull_lgrtest' id='bull_lgrtest' src='../greenbullet.gif'>"
			  . "</td>\n";
			print "\t\t<td></td>\n";
			print "\t</tr>\n";
		}
    }
    print "</table>\n";

    /* Déconnexion de la BD */
	disconnect_db($dbh);

?>
<br/>
<input type='submit' value='Commencer'>
</form>
<script language="javascript" type="text/javascript" src="ru_scripts.js"></script>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
