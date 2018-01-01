<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. esvocedit.php                                      -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des vocables - Edition                  -->
<!-- Emplacement......... \espagnol                                          -->
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
<meta name="keywords" content="espagnol,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Espagnol - Vocabulaire</title>
</head>

<body onLoad="init_form()">
<?php include("menu_espagnol.inc.php"); ?>
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
var valid_esvoc;		var bullet_esvoc;
var valid_prono;		var bullet_prono;
var valid_esidx;		var bullet_esidx;
var valid_trafr;		var bullet_trafr;
var valid_fridx;		var bullet_fridx;

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
/*     retour: document.frm_auteur.XXX.focus() sur la zone à problème         */
/*----------------------------------------------------------------------------*/
function isvalid() {
    if (document.formulaire.action.value=="sup")
        // Pas de validation au SUBMIT en cas de suppression
        return true;

    if (valid_esvoc==false) { // Problme sur la zone "esvoc"
        alert("Un vocable correct doit etre indique");
        document.formulaire.esvoc.focus();
        return false;
    }

    if (valid_prono==false) { // Problme sur la zone "prono"
        alert("Une prononciation correcte doit etre indiquee");
        document.formulaire.prono.focus();
        return false;
    }

    if (valid_esidx==false) { // Problme sur la zone "esidx"
        alert("Une entree d'index espagnol correcte doit etre indiquee");
        document.formulaire.esidx.focus();
        return false;
    }

    if (valid_trafr==false) { // Problme sur la zone "trafr"
        alert("Une traduction francaise correcte doit etre indiquee");
        document.formulaire.trafr.focus();
        return false;
    }

    if (valid_fridx==false) { // Problme sur la zone "fridx"
        alert("Une entree d'index francais correcte doit etre indiquee");
        document.formulaire.fridx.focus();
        return false;
    }
	return true;
}

/*----------------------------------------------------------------------------*/
/* Voir si fonction encore utile                                              */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = (valid_esvoc && valid_prono && valid_esidx && valid_trafr 
                 && valid_fridx);
}

/*----------------------------------------------------------------------------*/
/* Fonction servant au chargement de la page d'édition                        */
/*     parametres: $_POST['sel']......l'identifiant de l'élément à éditer     */
/*----------------------------------------------------------------------------*/
function load_data() {
<?php
	include_once("../util/app_sql.inc.php");
	
    if ($_POST["esvocedit_mod"]!="ins") {
        /* Connecting, selecting database */
		$link = connect_db();
    
        /* Performing SQL query */
        $result = exec_query("SELECT * FROM esvoc WHERE id={$_POST['esvocedit_sel']}");
    
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
	onblur_esvoc();
	onblur_prono();
	onblur_esidx();
	onblur_trafr();
	onblur_fridx();
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "esvoc"                                                */
/*     retour: valid_esvoc est faux si la zone est vide                       */
/*             bull_esvoc est rattach  une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_esvoc() {
    // Suppression des espaces de tte
    document.formulaire.esvoc.value = str_supesptete(document.formulaire.esvoc.value);

    valid_esvoc=(document.formulaire.esvoc.value.length != 0);
    bullet_esvoc=(valid_esvoc)? bullet_ok: bullet_nok;
    document.formulaire.bull_esvoc.src=bullet_esvoc;

    issubmitable();
    return valid_esvoc;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "prono"                                                */
/*     retour: valid_prono est faux si la zone est vide                       */
/*             bull_prono est rattach  une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_prono() {
    // Suppression des espaces de tte
    document.formulaire.prono.value = str_supesptete(document.formulaire.prono.value);

    valid_prono=(document.formulaire.prono.value.length != 0);
    bullet_prono=(valid_prono)? bullet_ok: bullet_nok;
    document.formulaire.bull_prono.src=bullet_prono;

    issubmitable();
    return valid_prono;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "esidx"                                                */
/*     retour: valid_esidx est faux si la zone est vide                       */
/*             bull_esidx est rattach  une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_esidx() {
    // Suppression des espaces de tte
    document.formulaire.esidx.value = str_supesptete(document.formulaire.esidx.value);

    if (document.formulaire.esidx.value.length == 0) {
       if (valid_esvoc)
           document.formulaire.esidx.value = document.formulaire.esvoc.value;
    }

    valid_esidx=(document.formulaire.esidx.value.length != 0);
    bullet_esidx=(valid_esidx)? bullet_ok: bullet_nok;
    document.formulaire.bull_esidx.src=bullet_esidx;

    issubmitable();
    return valid_esidx;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "trafr"                                                */
/*     retour: valid_trafr est faux si la zone est vide                       */
/*             bull_trafr est rattach  une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_trafr() {
    // Suppression des espaces de tte
    document.formulaire.trafr.value = str_supesptete(document.formulaire.trafr.value);

    valid_trafr=(document.formulaire.trafr.value.length != 0);
    bullet_trafr=(valid_trafr)? bullet_ok: bullet_nok;
    document.formulaire.bull_trafr.src=bullet_trafr;

    issubmitable();
    return valid_trafr;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "fridx"                                                */
/*     retour: valid_fridx est faux si la zone est vide                       */
/*             bull_fridx est rattach  une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_fridx() {
    // Suppression des espaces de tte
    document.formulaire.fridx.value = str_supesptete(document.formulaire.fridx.value);

    if (document.formulaire.fridx.value.length == 0) {
       if (valid_trafr)
           document.formulaire.fridx.value = document.formulaire.trafr.value;
    }

    valid_fridx=(document.formulaire.fridx.value.length != 0);
    bullet_fridx=(valid_fridx)? bullet_ok: bullet_nok;
    document.formulaire.bull_fridx.src=bullet_fridx;

    issubmitable();
    return valid_fridx;
}

/*----------------------------------------------------------------------------*/
/* Affichage de la rubrique d'aide liée à un champ                            */
/*     paramètre: le nom du champ faisant office de signet dans l'aide        */
/*     UI: Ouverture de la fenêtre d'aide et positionnement sur le signet     */
/*----------------------------------------------------------------------------*/
function on_help(signet) {
    var str_uri = "esvocedit_hlp.php"
    var str_opt = "toolbar=no," + "resizable=yes," + "scrollbars=yes," +
        "status=yes";
    if (signet!="")
        str_uri = str_uri + "#" + signet;
    window.open(str_uri,null,str_opt);
}

/*----------------------------------------------------------------------------*/
/*  Insère un nouveau vocable												  */
/*----------------------------------------------------------------------------*/
function onInsert() {
	document.formulaire.code_action.value = "ins";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Modifie le vocable sélectionné 											  */
/*----------------------------------------------------------------------------*/
function onModify() {
	document.formulaire.code_action.value = "maj";
	addReturnPageToForm("formulaire", pageRetour);

	// Bouton SUBMIT...
}

/*----------------------------------------------------------------------------*/
/*  Supprime le vocable sélectionné											  */
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
<h1>Mise &agrave; jour d'un vocable espagnol</h1>
<form name="formulaire" id="formulaire" action="esvocedit_upd.php" method="POST" 
  onsubmit="return isvalid();">

<!--  Identifiant (masqué)                                                   -->
<input type="hidden" name="id" id="id"
	value=<?php echo "\"" . ((isset($_POST["id"]))? $_POST["id"]: 
  	($_POST['esvocedit_mod']!="ins")? $line["id"]: "") . "\""; ?>/>

<table>
<!--  Vocable espagnol                                                          -->
<tr>
<td><a onClick="on_help('esvoc')"><label for="esvoc">Vocable espagnol</label></a></td>
<td><input type="text" name="esvoc" id="esvoc" size="32" maxlength="64" onblur="onblur_esvoc()"
	value=<?php	echo "\"" . ((isset($_POST['str_esvoc']))? hed_he($_POST['str_esvoc']): 
	(($_POST['esvocedit_mod']!="ins")? hed_he($line['str_esvoc']): "")) . "\""; ?>/>
&nbsp;<img name="bull_esvoc" id="bull_esvoc" src="../redbullet.gif"></td>
<td><a onClick="on_help('esidx')"><label for="esidx">Index espagnol</label></a></td>
<td><input type="text" name="esidx" id="esidx" size="32" maxlength="64"
  onblur="onblur_esidx()" value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_esidx"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_esidx" id="bull_esidx" src="../redbullet.gif"></td>
</tr>

<!--  Catgorie vocable espagnol et prononciation                          -->
<tr>
<td><a onClick="on_help('escat')"><label for="rucat">Cat.voc.esp.</label></a></td>
<td><input type="text" name="escat" id="escat" size="32" maxlength="32"
  value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_escat"] . "\"";
    else
        echo "\"\"";
?>/></td>
<td><a onClick="on_help('prono')"><label for="prono">Prononciation</label></a></td>
<td><input type="text" name="prono" id="prono" size="32" maxlength="64"
  onblur="onblur_prono()" value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_prono"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_prono" id="bull_prono" src="../redbullet.gif"></td>
</tr>
</table>

<!--  Contexte vocable espagnol                                               -->
<a onClick="on_help('esctx')"><label for="esctx">Contexte</label></a><br>
<textarea name="esctx" rows="2" cols="60" wrap="soft"><?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo $line["str_esctx"]; 
?></textarea>
<hr>
<table>
<!--  Vocable français                                                       -->
<tr>
<td><a onClick="on_help('trafr')"><label for="trafr">Traduc.Fran&ccedil;.</label></a></td>
<td><input type="text" name="trafr" id="trafr" size="32" maxlength="64"
  onblur="onblur_trafr()" value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_trafr"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_trafr" id="bull_trafr" src="../redbullet.gif"></td>
<td><a onClick="on_help('fridx')"><label for="fridx">Index fran.</label></a></td>
<td><input type="text" name="fridx" id="fridx" size="32" maxlength="64"
  onblur="onblur_fridx()" value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_fridx"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_fridx" id="bull_fridx" src="../redbullet.gif"></td>
</tr>

<!--  Catégorie vocable français                                            -->
<tr>
<td><a onClick="on_help('frcat')"><label for="frcat">Cat.voc.fran.</label></a></td>
<td><input type="text" name="frcat" id="frcat" size="32" maxlength="32"
  value=<?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo "\"" . $line["str_frcat"] . "\"";
    else
        echo "\"\"";
?>/></td>
<td></td>
</tr>
</table>

<!--  Contexte vocable français                                            -->
<a onClick="on_help('frctx')"><label for="frctx">Contexte</label></a><br>
<textarea name="frctx" rows="2" cols="60" wrap="soft"><?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo $line["str_frctx"]; 
?></textarea>

<hr>
<!--  Notes                                                                -->
<a onClick="on_help('notes')"><label for="notes">Notes</label></a><br>
<textarea name="notes" rows="3" cols="60" wrap="soft"><?php
    if ($_POST["esvocedit_mod"]!="ins")
        echo $line["str_notes"]; 
?></textarea>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<hr>
<input type="button" name="ann" id="ann"
	value=<?php print (($_POST["esvocedit_mod"]!="visu")? "\"Annuler\"": "\"Retour\""); ?> 
	onClick="onreturn()"/>
  &nbsp;&nbsp;<input type="hidden" name="code_action" id="code_action"/><?php
    if ($_POST["esvocedit_mod"]=="ins") {
        print "\n<input type='submit' name='ins' id='ins'" .
            "\n\tvalue='Cr&eacute;er' onclick=\"onInsert()\"/>";
    } else {
		if ($_POST["esvocedit_mod"]!="visu") {
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
