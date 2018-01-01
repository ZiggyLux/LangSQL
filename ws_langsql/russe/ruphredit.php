<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. ruphredit.php                                      -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des phrases - Edition                   -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");

	include_once("ruphr.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,phrase">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Russe - Phrases</title>
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
var valid_ruphr;		var bullet_ruphr;
var valid_ruidx;		var bullet_ruidx;
var valid_frphr;		var bullet_frphr;

/*----------------------------------------------------------------------------*/
/* RETOUR                                                                  	  */
/*----------------------------------------------------------------------------*/
function onreturn() {
	document.formulaire.action = pageRetour;
	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Validation au SUBMIT                                                       */
/*     UI: La fonction affiche une "alert" si une zone pose un problème       */
/*     retour: window.event.returnValue=false en cas de problème              */
/*             document.frm_auteur.XXX.focus() sur la zone à problème         */
/*----------------------------------------------------------------------------*/
function isvalid() {
    if (document.formulaire.code_action.value=="sup")
        // Pas de validation au SUBMIT en cas de suppression
        return true;

    if (valid_ruphr==false) { // Problème sur la zone "ruphr"
        alert("Une phrase correcte doit être indiquée");
        document.formulaire.ruphr.focus();
        return false;
    }

    if (valid_ruidx==false) { // Problème sur la zone "ruidx"
        alert("Une entrée d'index russe correcte doit être indiquée");
        document.formulaire.ruidx.focus();
        return false;
    }

    if (valid_frphr==false) { // Problème sur la zone "frphr"
        alert("Une traduction francaise correcte doit être indiquée");
        document.formulaire.frphr.focus();
        return false;
    }
	
	return true;
}

/*----------------------------------------------------------------------------*/
/* Fonction servant au chargement de la page d'édition                        */
/*     parametres: $_POST['sel']......l'identifiant de l'élément à éditer     */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
	include_once("../util/app_sql.inc.php");

    if ($_POST["ruphredit_mod"]!="ins") {
		/* Connecting, selecting database */
		$link = connect_db();
    
        /* Performing SQL query */
        $result = exec_query("SELECT * FROM ruphr WHERE id={$_POST['ruphredit_sel']}");
    
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
	// Validation des champs de la page
    onblur_ruphr();
    onblur_ruidx();
    onblur_frphr();
}

/*----------------------------------------------------------------------------*/
/* Calcule la validité générale de la page                                    */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = (valid_ruphr && valid_ruidx && valid_frphr);
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruphr"                                                */
/*     retour: valid_ruphr est faux si la zone est vide                       */
/*             bull_ruphr est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_ruphr() {
    // Suppression des espaces de tête
    document.formulaire.ruphr.value = str_supesptete(document.formulaire.ruphr.value);

    valid_ruphr=(document.formulaire.ruphr.value.length != 0);
    bullet_ruphr=(valid_ruphr)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruphr.src=bullet_ruphr;

    issubmitable();
    return valid_ruphr;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruidx"                                                */
/*     retour: valid_ruidx est faux si la zone est vide                       */
/*             bull_ruidx est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_ruidx() {
    // Suppression des espaces de tête
    document.formulaire.ruidx.value = str_supesptete(document.formulaire.ruidx.value);

    valid_ruidx=(document.formulaire.ruidx.value.length != 0);
    bullet_ruidx=(valid_ruidx)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruidx.src=bullet_ruidx;

    issubmitable();
    return valid_ruidx;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "frphr"                                                */
/*     retour: valid_frphr est faux si la zone est vide                       */
/*             bull_frphr est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_frphr() {
    // Suppression des espaces de tête
    document.formulaire.frphr.value = str_supesptete(document.formulaire.frphr.value);

    valid_frphr=(document.formulaire.frphr.value.length != 0);
    bullet_frphr=(valid_frphr)? bullet_ok: bullet_nok;
    document.formulaire.bull_frphr.src=bullet_frphr;

    issubmitable();
    return valid_frphr;
}

/*----------------------------------------------------------------------------*/
/* Affichage de la rubrique d'aide liée à un champ                            */
/*     paramtère: le nom du champ faisant office de signet dans l'aide        */
/*     UI: Ouverture de la fenêtre d'aide et positionnement sur le signet     */
/*----------------------------------------------------------------------------*/
function on_help(signet) {
    var str_uri = "ruphredit_hlp.php"
    var str_opt = "toolbar=no," + "resizable=yes," + "scrollbars=yes," +
        "status=yes";
    if (signet!="")
        str_uri = str_uri + "#" + signet;
    window.open(str_uri,null,str_opt);
}

/*----------------------------------------------------------------------------*/
/*  Insère une nouvelle phrase												  */
/*----------------------------------------------------------------------------*/
function onInsert() {
	document.formulaire.code_action.value = "ins";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Modifie la phrase sélectionnée											  */
/*----------------------------------------------------------------------------*/
function onModify() {
	document.formulaire.code_action.value = "maj";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Supprime la phrase sélectionnée											  */
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
<h1>Mise &agrave; jour d'une phrase russe</h1>
<form name="formulaire" id="formulaire" action="ruphredit_upd.php" method="POST" 
  onsubmit="return isvalid();">

<!--  Identifiant (masqué)                                                   -->
<input type="hidden" name="id" id="id"
	value=<?php echo "\"" . ((isset($_POST["id"]))? $_POST["id"]: 
  	($_POST['ruphredit_mod']!="ins")? $line["id"]: "") . "\""; ?>/>

<table>
<!--  Index russe                                                          -->
<tr>
<td><a onClick="on_help('ruidx')"><label for="ruidx">Index russe</label></a></td>
<td><input class="russe" type="text" name="ruidx" id="ruidx" size="32" maxlength="64"
  onblur="onblur_ruidx()" value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_ruidx"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruidx" id="bull_ruidx" src="../redbullet.gif"></td>
</tr>

<!--  Vocable français                                                       -->
<tr>
<td><a onClick="on_help('frphr')"><label for="frphr">Traduc.Fran.</label></a></td>
<td><input type="text" name="frphr" id="frphr" size="64" maxlength="255"
  onblur="onblur_frphr()" value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_frphr"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_frphr" id="bull_frphr" src="../redbullet.gif"></td>
</tr>

<!--  Indication pour la traduction                                          -->
<tr>
<td><a onClick="on_help('indic')"><label for="indic">Indication</label></a></td>
<td><input type="text" name="indic" id="indic" size="64" maxlength="255"
  value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_indic"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>


<!--  Phrase russe                                                          -->
<tr>
<td><a onClick="on_help('ruphr')"><label for="ruphr">Phrase russe</label></a></td>
<td><input class="russe" type="text" name="ruphr" id="ruphr" size="64" maxlength="255"
  onblur="onblur_ruphr()" value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_ruphr"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruphr" id="bull_ruphr" src="../redbullet.gif"></td>
</tr>

<!--  Phonétique                                          -->
<tr>
<td><a onClick="on_help('rupho')"><label for="rupho">Phon&eacute;tique</label></a></td>
<td><input type="text" name="rupho" id="rupho" size="64" maxlength="255"
  value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_rupho"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<!--  Fichier audio                                          -->
<tr>
<td><a onClick="on_help('audio')"><label for="audio">Audio</label></a></td>
<td><input type="text" name="audio" id="audio" size="64" maxlength="255"
  value=<?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo "\"" . $line["str_audio"] . "\"";
    else
        echo "\"\"";
?>/>
<?php if ($_POST["ruphredit_mod"]!="ins") echo "(" . $line["id"] . ")";?>
</td>

</tr>
<tr><td>&nbsp;</td><td><?php
  if ($_POST["ruphredit_mod"]!="ins") genObjAudio($line["str_audio"]);
  ?></td>
</tr>

</table>

<hr>
<!--  Notes                                                                -->
<a onClick="on_help('notes')"><label for="notes">Notes</label></a><br>
<textarea name="notes" rows="3" cols="60" wrap="soft"><?php
    if ($_POST["ruphredit_mod"]!="ins")
        echo $line["str_notes"]; 
?></textarea>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<hr>
<input type="button" name="ann" id="ann"
	value=<?php print (($_POST["ruphredit_mod"]!="visu")? "\"Annuler\"": "\"Retour\""); ?> 
	onClick="onreturn()"/>
  &nbsp;&nbsp;<input type="hidden" name="code_action" id="code_action"/><?php
    if ($_POST["ruphredit_mod"]=="ins") {
        print "\n<input type='submit' name='ins' id='ins'" .
            "\n\tvalue='Cr&eacute;er' onclick=\"onInsert()\"/>";
    } else {
		if ($_POST["ruphredit_mod"]!="visu") {
			print "\n<input type='submit' name='maj' id='maj'" .
				"\n\t value='Mettre &aacute; jour' onclick=\"onModify()\"/>";
			print "\n<input type='submit' name='sup' id='sup'" .
				"\n\tvalue='Supprimer' onclick=\"onSuppress()\"/>";
		}
    }
?> 
</form>
<hr>
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
