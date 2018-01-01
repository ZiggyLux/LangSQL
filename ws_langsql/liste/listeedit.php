<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. listeedit.php                                      -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Modification d'une liste - Edition                 -->
<!-- Emplacement......... liste                                              -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_ses.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="manuel,liste">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Listes <?php 
	switch($_POST['listetype']) {
	case "1": print("de vocables russes"); break;
	case "2": print("de verbes russes"); break;
	case "3": print("de phrases russes"); break;
	default: print("de ???");
	}
?> - Edition</title>
</head>

<body onLoad="init_form()">
<?php include("../russe/menu_russe.inc.php"); ?>
<script language="javascript" type="text/javascript" name="valid" id="valid">
<!--

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var pageRetour = <?php print "\"{$_SESSION['arrRetPag'][$_SERVER['SCRIPT_NAME']]}\"" ?>;

/*---------------------------- Variables de validation -----------------------*/
var bullet_ok= "../greenbullet.gif";
var bullet_nok= "../redbullet.gif";

// Variables de validation vocable global
var valid_form;

// Variables de validation champ par champ
var valid_nom;		var bullet_nom;

/*----------------------------------------------------------------------------*/
/* Fonction de retour de navigation							                  */
/*----------------------------------------------------------------------------*/
function onreturn() {
	document.formulaire.action = pageRetour;
	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Validation au SUBMIT                                                       */
/*     UI: La fonction affiche une "alert" si une zone pose un problème       */
/*     retour: window.event.returnValue=false en cas de problème              */
/*             document.formulaire.XXX.focus() sur la zone à problème          */
/*----------------------------------------------------------------------------*/
function isvalid() {
    if (document.formulaire.code_action.value=="sup")
        // Pas de validation au SUBMIT en cas de suppression
        return true;
		
    if (valid_nom==false) { // Problème sur la zone "nom"
        alert("Un nom de liste doit être indiqué.");
        document.formulaire.nom.focus();
        return false;
    }
	return true;
}

/*----------------------------------------------------------------------------*/
/* Voir si fonction encore utile                                              */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = valid_nom;
}

/*----------------------------------------------------------------------------*/
/* Fonction servant au chargement de la page d'édition                        */
/*     parametres: $_POST['sel']......l'identifiant de l'élément à éditer     */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
	include_once("../util/app_sql.inc.php");

    if ($_POST["listeedit_mod"]!="ins") {
		/* Connecting, selecting database */
		$link = connect_db();
		
        /* Performing SQL query */
		$result = exec_query("SELECT * FROM liste WHERE id={$_POST['listeedit_sel']}");
    
        $line = mysql_fetch_array($result, MYSQL_ASSOC) or die("Query failed");
    
        /* Free resultset */
        mysql_free_result($result);

		/* Closing connection */
		disconnect_db($link);
    }
?>
}

/*----------------------------------------------------------------------------*/
/* Validation champ par champ au chargement de la page                        */
/*----------------------------------------------------------------------------*/
function init_form() {
	onblur_nom();
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "nom"                                                  */
/*     retour: valid_nom est faux si la zone est vide                         */
/*             bull_nom est rattaché à une bulle ok ou nok                    */
/*----------------------------------------------------------------------------*/
function onblur_nom() {
    // Suppression des espaces de tête
    document.formulaire.nom.value = str_supesptete(document.formulaire.nom.value);

    valid_nom=(document.formulaire.nom.value.length != 0);
    bullet_nom=(valid_nom)? bullet_ok: bullet_nok;
    document.formulaire.bull_nom.src=bullet_nom;

    issubmitable();
    return valid_nom;
}

/*----------------------------------------------------------------------------*/
/* Affichage de la rubrique d'aide liée à un champ                            */
/*     paramtère: le nom du champ faisant office de signet dans l'aide        */
/*     UI: Ouverture de la fenêtre d'aide et positionnement sur le signet     */
/*----------------------------------------------------------------------------*/
function on_help(signet) {
    var str_uri = "listeedit_hlp.php"
    var str_opt = "toolbar=no," + "resizable=yes," + "scrollbars=yes," +
        "status=yes";
    if (signet!="")
        str_uri = str_uri + "#" + signet;
    window.open(str_uri,null,str_opt);
}

/*----------------------------------------------------------------------------*/
/*  Insère une nouvelle liste												  */
/*----------------------------------------------------------------------------*/
function onInsert() {
	document.formulaire.code_action.value = "ins";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Modifie la liste sélectionnée											  */
/*----------------------------------------------------------------------------*/
function onModify() {
	document.formulaire.code_action.value = "maj";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Supprime la liste sélectionnée											  */
/*----------------------------------------------------------------------------*/
function onSuppress() {
	document.formulaire.code_action.value = "sup";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               --> 
<!----------------------------------------------------------------------------->
<h1>Mise &agrave; jour d'une liste <?php 
	switch($_POST['listetype']) {
	case "1": print("de vocables russes"); break;
	case "2": print("de verbes russes"); break;
	case "3": print("de phrases russes"); break;
	default: print("de ???");
	}
?> </h1>
<form name="formulaire" id="formulaire" action="listeedit_upd.php" method="POST"
	onSubmit="return isvalid();">
<?php
//	include_once("../util/app_mod_hidposvar.inc.php");
//	hidePostedVar();
?>
<!--  Identifiant (masqué)  -->
<input type="hidden" name="id" id="id" value=<?php
    if ($_POST['listeedit_mod']!="ins")
        print "\"{$line['id']}\"";
    else
        print "\"\"";
?>/>

<!--  Type (masqué)  -->
<input type="hidden" name="listetype" id="listetype" value=<?php
        print "\"{$_POST['listetype']}\"";
?>/>

<table>
<!--  Nom de liste  -->
<tr>
<td><a onClick="on_help('nom')"><label for="nom">Nom de liste</label></a></td>
<td><input type="text" name="nom" id="nom" size="64" maxlength="64"
  onblur="onblur_nom()" value=<?php
    if ($_POST['listeedit_mod']!="ins")
        print "\"" . htmlspecialchars($line['str_nom'], ENT_QUOTES) . "\"";
    else
        print "\"\"";
?>/>&nbsp;<img name="bull_nom" id="bull_nom" src="../redbullet.gif"></td>
</tr>
</table>

<!--  Notes  -->
<a onClick="on_help('notes')"><label for="notes">Notes</label></a><br>
<textarea name="notes" rows="15" cols="60" wrap="soft"><?php
    if ($_POST['listeedit_mod']!="ins")
        print $line['str_notes']; 
?></textarea>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<hr>
<input type="button" name="ann" id="ann" value="Retour" onClick="onreturn()"/>
<input type="hidden" name="code_action" id="code_action"
    value=<?php echo "\"" . $_POST['listeedit_mod'] . "\""; ?>/>
	
<?php
     if ($_POST['listeedit_mod']=="ins") {
        print "\n<input type='submit' name='ins' id='ins'" .
            "\n\tvalue='Cr&eacute;er' onclick=\"onInsert()\"/>";
    } else {
//	    print "\n<input type=\"hidden\" name=\"listeedit_sel\""
//			. " id=\"listeedit_sel\" value=\"{$_POST['listeedit_sel']}\"/>";
		print "\n<input type='submit' name='maj' id='maj'" .
			"\n\t value='Mettre &aacute; jour' onclick=\"onModify()\"/>";
		print "\n<input type='submit' name='sup' id='sup'" .
			"\n\tvalue='Supprimer' onclick=\"onSuppress()\"/>";
    }
?> 
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
