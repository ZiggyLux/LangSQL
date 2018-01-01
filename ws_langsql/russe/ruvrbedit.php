<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. ruvrbedit.php                                      -->
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

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,verbes">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Russe - Verbes</title>
</head>
<body onLoad="init_form()">
<?php include("menu_russe.inc.php"); ?>
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
var valid_ruvip_inf=true;		var bullet_ruvip_inf;
var valid_ruvpd_inf=true;		var bullet_ruvpd_inf;
var valid_ruvpi_inf=true;		var bullet_ruvpi_inf;

<!----------------------------------------------------------------------------->
<!-- RETOUR                                                                  -->
<!----------------------------------------------------------------------------->
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
        return;

    if (valid_ruvip_inf==false) { // Problème sur la zone "ruvip_inf"
        alert("Un infinitif imperfectif doit être indiqué");
        document.formulaire.ruvip_inf.focus();
        window.event.returnValue=false;
        return;
    }

    if (valid_ruvd_inf==false) { // Problème sur la zone "ruvpd_inf"
        alert("Un infinitif perfectif déterminé doit être indiqué");
        document.formulaire.ruvpd_inf.focus();
        window.event.returnValue=false;
        return;
    }

    if (valid_ruvpi_inf==false) { // Problème sur la zone "ruvpi_inf"
        alert("Un infinitif imperfectif indéterminé doit être indiqué");
        document.formulaire.ruvpi.focus();
        window.event.returnValue=false;
        return;
    }
}

/*----------------------------------------------------------------------------*/
/* Fonction servant au chargement de la page d'édition                        */
/*     parametres: $_POST['sel']......l'identifiant de l'élément à éditer     */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
	include_once("../util/app_sql.inc.php");
	
    if ($_POST["ruvrbedit_mod"]!="ins") {
		/* Connecting, selecting database */
		$link = connect_db();
    
        /* Performing SQL query */
        $result = exec_query("SELECT * FROM ruvrb WHERE id={$_POST['ruvrbedit_sel']}");
    
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
    onblur_ruvip_inf();
    onblur_ruvpd_inf();
    onblur_ruvpi_inf();
}

/*----------------------------------------------------------------------------*/
/* Voir si fonction encore utile                                              */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = (valid_ruvip_inf && valid_ruvpd_inf && valid_ruvpi_inf);
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruvip_inf"                                            */
/*     retour: valid_ruvip_inf est faux si la zone est vide et si une         */
/*             conjugaison est non vide                                       */
/*             bull_ruvip_inf est rattaché à une bulle ok ou nok              */
/*----------------------------------------------------------------------------*/
function onblur_ruvip_inf() {
    // Suppression des espaces de tête
    document.formulaire.ruvip_inf.value = 
      str_supesptete(document.formulaire.ruvip_inf.value);

//    valid_ruvrb=(document.formulaire.ruvrb.value.length != 0);
    valid_ruvip_inf=true;

    bullet_ruvip_inf=(valid_ruvip_inf)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruvip_inf.src=bullet_ruvip_inf;

    issubmitable();
    return valid_ruvip_inf;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruvpd_inf"                                            */
/*     retour: valid_ruvpd_inf est faux si la zone est vide et si une         */
/*             conjugaison est non vide                                       */
/*             bull_ruvpd_inf est rattaché à une bulle ok ou nok              */
/*----------------------------------------------------------------------------*/
function onblur_ruvpd_inf() {
    // Suppression des espaces de tête
    document.formulaire.ruvpd_inf.value = 
      str_supesptete(document.formulaire.ruvpd_inf.value);

//    valid_ruvrb=(document.formulaire.ruvrb.value.length != 0);
    valid_ruvpd_inf=true;

    bullet_ruvpd_inf=(valid_ruvpd_inf)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruvpd_inf.src=bullet_ruvpd_inf;

    issubmitable();
    return valid_ruvpd_inf;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruvpi_inf"                                            */
/*     retour: valid_ruvpi_inf est faux si la zone est vide et si une         */
/*             conjugaison est non vide                                       */
/*             bull_ruvpi_inf est rattaché à une bulle ok ou nok              */
/*----------------------------------------------------------------------------*/
function onblur_ruvpi_inf() {
    // Suppression des espaces de tête
    document.formulaire.ruvpi_inf.value = 
      str_supesptete(document.formulaire.ruvpi_inf.value);

//    valid_ruvrb=(document.formulaire.ruvrb.value.length != 0);
    valid_ruvpi_inf=true;

    bullet_ruvpi_inf=(valid_ruvpi_inf)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruvpi_inf.src=bullet_ruvpi_inf;

    issubmitable();
    return valid_ruvpi_inf;
}

/*----------------------------------------------------------------------------*/
/* Affichage de la rubrique d'aide liée à un champ                            */
/*     paramètre: le nom du champ faisant office de signet dans l'aide        */
/*     UI: Ouverture de la fenêtre d'aide et positionnement sur le signet     */
/*----------------------------------------------------------------------------*/
function on_help(signet) {
    var str_uri = "ruvrbedit_hlp.php"
    var str_opt = "toolbar=no," + "resizable=yes," + "scrollbars=yes," +
        "status=yes";
    if (signet!="")
        str_uri = str_uri + "#" + signet;
    window.open(str_uri,null,str_opt);
}

/*----------------------------------------------------------------------------*/
/*  Insère un nouveau verbe													  */
/*----------------------------------------------------------------------------*/
function onInsert() {
	document.formulaire.code_action.value = "ins";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Modifie le verbe sélectionné											  */
/*----------------------------------------------------------------------------*/
function onModify() {
	document.formulaire.code_action.value = "maj";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Supprime le verbe sélectionné											  */
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
<h1>Mise &agrave; jour d'un verbe russe</h1>
<form name="formulaire" id="formulaire" action="ruvrbedit_upd.php" method="POST" 
  onsubmit="return isvalid();">

<!--  Identifiant (masqué)                                                   -->
<input type="hidden" name="id" id="id" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["id"] . "\"";
    else
        echo "\"\"";
?>/>
<table>
<!--  Indication pour la traduction                                          -->
<tr>
<td><a onClick="on_help('indic')"><label for="indic">Indication</label></a></td>
<td><input type="text" name="indic" id="indic" size="64" maxlength="255"
  value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_indic"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<!--  Verbe imperfectif                                                      -->
<tr>
<td><a onClick="on_help('ruvip_inf')"><label for="ruvip_inf"><b>IMPERFECTIF</b></label></a></td>
<td><input class="russe" type="text" name="ruvip_inf" id="ruvip_inf" size="20" maxlength="64"
	onblur="onblur_ruvip_inf()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_inf"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruvip_inf" id="bull_ruvip_inf" src="../redbullet.gif"></td>
</tr>

<tr>
<td><a onClick="on_help('ruvip_pre')">
	<label for="ruvip_pre">Pr&eacute;sent</label></a></td>
<td>1s: <input class="russe_sm" type="text" name="ruvip_pre_1s" id="ruvip_pre_1s" size="20" maxlength="64"
  onblur="onblur_ruvip_pre_1s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pre_1s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3s: <input class="russe_sm" type="text" name="ruvip_pre_2s" id="ruvip_pre_2s" size="20" maxlength="64"
  onblur="onblur_ruvip_pre_2s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pre_2s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3p: <input class="russe_sm" type="text" name="ruvip_pre_3p" id="ruvip_pre_3p" size="20" maxlength="64"
  onblur="onblur_ruvip_pre_3p()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pre_3p"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<tr>
<td><a onClick="on_help('ruvip_pas')">
	<label for="ruvip_pre">Pass&eacute;</label></a></td>
<td>ms: <input class="russe_sm" type="text" name="ruvip_pas_ms" id="ruvip_pre_ms" size="20" maxlength="64"
  onblur="onblur_ruvip_pas_ms()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pas_ms"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;fs: <input class="russe_sm" type="text" name="ruvip_pas_fs" id="ruvip_pas_fs" size="20" maxlength="64"
  onblur="onblur_ruvip_pas_fs()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pas_fs"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;ns: <input class="russe_sm" type="text" name="ruvip_pas_ns" id="ruvip_pas_ns" size="20" maxlength="64"
  onblur="onblur_ruvip_pas_ns()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pas_ns"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;pl: <input class="russe_sm" type="text" name="ruvip_pas_pl" id="ruvip_pas_pl" size="20" maxlength="64"
  onblur="onblur_ruvip_pas_pl()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvip_pas_pl"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<!--  Verbe perfectif déterminé                                             -->
<tr>
<td><a onClick="on_help('ruvpd_inf')">
	<label for="ruvip_inf"><b>PERFECTIF<br>D&Eacute;TERMIN&Eacute;</b></label></a></td>
<td><input class="russe" type="text" name="ruvpd_inf" id="ruvpd_inf" size="20" maxlength="64"
  onblur="onblur_ruvpd_inf()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_inf"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruvpd_inf" id="bull_ruvpd_inf" src="../redbullet.gif"></td>
</tr>

<tr>
<td><a onClick="on_help('ruvpd_pre')">
	<label for="ruvpd_pre">Pr&eacute;sent</label></a></td>
<td>1s: <input class="russe_sm" type="text" name="ruvpd_pre_1s" id="ruvpd_pre_1s" size="20" maxlength="64"
  onblur="onblur_ruvpd_pre_1s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pre_1s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3s: <input class="russe_sm" type="text" name="ruvpd_pre_2s" id="ruvpd_pre_2s" size="20" maxlength="64"
  onblur="onblur_ruvpd_pre_2s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pre_2s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3p: <input class="russe_sm" type="text" name="ruvpd_pre_3p" id="ruvpd_pre_3p" size="20" maxlength="64"
  onblur="onblur_ruvpd_pre_3p()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pre_3p"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<tr>
<td><a onClick="on_help('ruvpd_pas')">
	<label for="ruvpd_pre">Pass&eacute;</label></a></td>
<td>ms: <input class="russe_sm" type="text" name="ruvpd_pas_ms" id="ruvpd_pre_ms" size="20" maxlength="64"
  onblur="onblur_ruvpd_pas_ms()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pas_ms"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;fs: <input class="russe_sm" type="text" name="ruvpd_pas_fs" id="ruvpd_pas_fs" size="20" maxlength="64"
  onblur="onblur_ruvpd_pas_fs()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pas_fs"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;ns: <input class="russe_sm" type="text" name="ruvpd_pas_ns" id="ruvpd_pas_ns" size="20" maxlength="64"
  onblur="onblur_ruvpd_pas_ns()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pas_ns"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;pl: <input class="russe_sm" type="text" name="ruvpd_pas_pl" id="ruvpd_pas_pl" size="20" maxlength="64"
  onblur="onblur_ruvpd_pas_pl()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpd_pas_pl"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<!--  Verbe imperfectif indéterminé                                          -->
<tr>
<td><a onClick="on_help('ruvpi_inf')">
	<label for="ruvpi_inf"><b>IND&Eacute;TERMIN&Eacute;</b></label></a></td>
<td><input class="russe" type="text" name="ruvpi_inf" id="ruvpi_inf" size="20" maxlength="64"
  onblur="onblur_ruvpi_inf()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_inf"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruvpi_inf" id="bull_ruvpi_inf" src="../redbullet.gif"></td>
</tr>

<tr>
<td><a onClick="on_help('ruvpi_pre')">
	<label for="ruvpi_pre">Pr&eacute;sent</label></a></td>
<td>1s: <input class="russe_sm" type="text" name="ruvpi_pre_1s" id="ruvpi_pre_1s" size="20" maxlength="64"
  onblur="onblur_ruvpi_pre_1s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pre_1s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3s: <input class="russe_sm" type="text" name="ruvpi_pre_2s" id="ruvpi_pre_2s" size="20" maxlength="64"
  onblur="onblur_ruvpi_pre_2s()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pre_2s"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;3p: <input class="russe_sm" type="text" name="ruvpi_pre_3p" id="ruvpi_pre_3p" size="20" maxlength="64"
  onblur="onblur_ruvpi_pre_3p()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pre_3p"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

<tr>
<td><a onClick="on_help('ruvpi_pas')">
	<label for="ruvpi_pre">Pass&eacute;</label></a></td>
<td>ms: <input class="russe_sm" type="text" name="ruvpi_pas_ms" id="ruvpi_pre_ms" size="20" maxlength="64"
  onblur="onblur_ruvpi_pas_ms()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pas_ms"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;fs: <input class="russe_sm" type="text" name="ruvpi_pas_fs" id="ruvpi_pas_fs" size="20" maxlength="64"
  onblur="onblur_ruvpi_pas_fs()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pas_fs"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;ns: <input class="russe_sm" type="text" name="ruvpi_pas_ns" id="ruvpi_pas_ns" size="20" maxlength="64"
  onblur="onblur_ruvpi_pas_ns()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pas_ns"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;pl: <input class="russe_sm" type="text" name="ruvpi_pas_pl" id="ruvpi_pas_pl" size="20" maxlength="64"
  onblur="onblur_ruvpi_pas_pl()" value=<?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo "\"" . $line["str_ruvpi_pas_pl"] . "\"";
    else
        echo "\"\"";
?>/></td>
</tr>

</table>

<br>
<!--  Notes                                                                -->
<a onClick="on_help('notes')"><label for="notes">Notes</label></a><br>
<textarea name="notes" rows="4" cols="60" wrap="soft"><?php
    if ($_POST["ruvrbedit_mod"]!="ins")
        echo $line["str_notes"]; 
?></textarea>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<hr>
<input type="button" name="ann" id="ann"
	value=<?php print (($_POST["ruvrbedit_mod"]!="visu")? "\"Annuler\"": "\"Retour\""); ?>
	onClick="onreturn()"/>
  &nbsp;&nbsp;<input type="hidden" name="code_action" id="code_action"/><?php
    if ($_POST["ruvrbedit_mod"]=="ins") {
        print "\n<input type='submit' name='ins' id='ins'" .
            "\n\tvalue='Cr&eacute;er' onclick=\"onInsert()\"/>";
    } else {
		if ($_POST["ruvrbedit_mod"]!="visu") {
			print "\n<input type='submit' name='maj' id='maj'" .
				"\n\t value='Mettre &aacute; jour' onclick=\"onModify()\"/>";
			print "\n<input type='submit' name='sup' id='sup'" .
				"\n\tvalue='Supprimer' onclick=\"onSuppress()\"/>";
		}
    }
?> 
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
