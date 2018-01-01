<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. esvocquery_esvoc.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Espagnol: interrogation traduction FR->ES          -->
<!-- Emplacement......... \espagnol                                          -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_sql.inc.php");
	include_once("../util/app_cod.inc.php");

	include_once("../debug_tools/common.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="espagnol,vocable,query">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en espagnol - Traduction du fran&ccedil;ais &agrave; l'espagnol</title>
</head>
<body onload="init_form()">
<?php include("menu_espagnol.inc.php"); ?>

<script type="text/javascript" language="javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variable globales														  */
/*----------------------------------------------------------------------------*/

/*---------------------------- Variables de validation -----------------------*/
var bullet_ok= "../greenbullet.gif";
var bullet_nok= "../redbullet.gif";

// Variable de validation des champs
var valid_form;

	// Variables de validation champ par champ
	var valid_lgrtest;		var bullet_lgrtest;
	var LGRMIN = 1;
	var LGRMAX = 30;

/*----------------------------------------------------------------------------*/
/* Chargement des données de la page                                          */
/*----------------------------------------------------------------------------*/
function load_data() {
}
/*----------------------------------------------------------------------------*/
/* Validation champ par champ au chargement de la page                        */
/*----------------------------------------------------------------------------*/
function init_form() {
	// Validation des champs de la page
    onblur_lgrtest();
}

/*----------------------------------------------------------------------------*/
/* Validation au SUBMIT                                                       */
/*     UI: La fonction affiche une "alert" si une zone pose un problème       */
/*     retour: document.formulaire.XXX.focus() sur la zone à problème          */
/*----------------------------------------------------------------------------*/
function isvalid() {
    if (!valid_lgrtest) { // Problème sur la zone "lgrtest"
        alert("Le longueur du test doit être comprise entre " + LGRMIN + " et " + LGRMAX);
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

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des lments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Vocabulaire russe - Traduction du fran&ccedil;ais &agrave; l'espagnol</h1>
<form name="formulaire" id="formulaire" method="POST" 
	action="esvocquery_esvoc_qry.php" onsubmit="return isvalid();">
<?php
    /* Connexion à la base de données */
    $link = connect_db();

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
    $iLineMax = 1;
	$fPair = false;
    while ($iLine < $iLineMax ) {

		/* By page walking: line and page counting */
		$iLine++;

		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		switch($iLine) {
		case 1:
			print "\t<tr class=\"{$trClass}\" onclick=\"\">\n";
			print "\t\t<td>Nombre de vocables &agrave; tester</td>\n";
			print "\t\t<td>"
			  . "<input type='text' name='id_lgrtest' id='id_lgrtest' size='2' maxlength='2'"
        	  . " value='" . ((isset($_POST["id_lgrtest"]))? $_POST["id_lgrtest"]: "15"). "'"
			  .		" onblur='onblur_lgrtest()'/>"
			  . "&nbsp;<img name='bull_lgrtest' id='bull_lgrtest' src='../greenbullet.gif'>"
			  . "</td>\n";
			print "\t\t<td></td>\n";
			print "\t</tr>\n";
		}
    }
    print "</table>\n";

    /* Déconnexion de la BD */
	disconnect_db($link);

?>
<br/>
<input type='submit' value='Commencer'>
</form>
<script type="text/javascript" language="javascript" src="es_scripts.js"></script>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
