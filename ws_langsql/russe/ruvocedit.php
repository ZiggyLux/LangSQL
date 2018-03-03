<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvocedit.php                                      -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des vocables - Edition                  -->
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
<meta name="keywords" content="russe,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Russe - Vocabulaire</title>
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
var valid_ruvoc;		var bullet_ruvoc;
var valid_prono;		var bullet_prono;
var valid_ruidx;		var bullet_ruidx;
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
    if (document.formulaire.code_action.value=="sup")
        // Pas de validation au SUBMIT en cas de suppression
        return true;

    if (valid_ruvoc==false) { // Problème sur la zone "ruvoc"
        alert("Un vocable correct doit etre indique");
        document.formulaire.ruvoc.focus();
        return false;
    }

    if (valid_prono==false) { // Problème sur la zone "prono"
        alert("Une prononciation correcte doit etre indiquee");
        document.formulaire.prono.focus();
        return false;
    }

    if (valid_ruidx==false) { // Problème sur la zone "ruidx"
        alert("Une entree d'index russe correcte doit etre indiquee");
        document.formulaire.ruidx.focus();
        return false;
    }

    if (valid_trafr==false) { // Problème sur la zone "trafr"
        alert("Une traduction francaise correcte doit etre indiquee");
        document.formulaire.trafr.focus();
        return false;
    }

    if (valid_fridx==false) { // Problème sur la zone "fridx"
        alert("Une entree d'index francais correcte doit etre indiquee");
        document.formulaire.fridx.focus();
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
	
    if ($_POST["ruvocedit_mod"]!="ins") {
		/* Connecting, selecting database */
		$dbh = connect_db();
	
       	/* Performing SQL query */
        $query = "SELECT * FROM ruvoc WHERE id={$_POST['ruvocedit_sel']}";
        if (($result = $dbh->query($query)) === FALSE) {
            echo 'Erreur dans la requête SQL : ';
            echo $query;
            exit();
        }
    
        $line = $result->fetch(PDO::FETCH_ASSOC) or die("Query failed");
    
        /* Free resultset */
        $result = NULL;
		
		/* Closing connection */
		disconnect_db($dbh);
    }
?>
}

/*----------------------------------------------------------------------------*/
/* Validation champ par champ au chargement de la page                        */
/*----------------------------------------------------------------------------*/
function init_form() {
	onblur_ruvoc();
	onblur_prono();
	onblur_ruidx();
	onblur_trafr();
	onblur_fridx();
}

/*----------------------------------------------------------------------------*/
/* Calcule la validité générale de la page                                    */
/*----------------------------------------------------------------------------*/
function issubmitable() {
    valid_form = (valid_ruvoc && valid_prono && valid_ruidx && valid_trafr 
                 && valid_fridx);
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruvoc"                                                */
/*     retour: valid_ruvoc est faux si la zone est vide                       */
/*             bull_ruvoc est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_ruvoc() {
    // Suppression des espaces de tête
	
    document.formulaire.ruvoc.value = str_supesptete(document.formulaire.ruvoc.value);

    valid_ruvoc=(document.formulaire.ruvoc.value.length != 0);
    bullet_ruvoc=(valid_ruvoc)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruvoc.src=bullet_ruvoc;

    issubmitable();
    return valid_ruvoc;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "prono"                                                */
/*     retour: valid_prono est faux si la zone est vide                       */
/*             bull_prono est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_prono() {
    // Suppression des espaces de tête
    document.formulaire.prono.value = str_supesptete(document.formulaire.prono.value);

    valid_prono=(document.formulaire.prono.value.length != 0);
    bullet_prono=(valid_prono)? bullet_ok: bullet_nok;
    document.formulaire.bull_prono.src=bullet_prono;

    issubmitable();
    return valid_prono;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "ruidx"                                                */
/*     retour: valid_ruidx est faux si la zone est vide                       */
/*             bull_ruidx est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_ruidx() {
    // Suppression des espaces de tête
    document.formulaire.ruidx.value = str_supesptete(document.formulaire.ruidx.value);

    if (document.formulaire.ruidx.value.length == 0) {
       if (valid_ruvoc)
           document.formulaire.ruidx.value = document.formulaire.ruvoc.value;
    }

    valid_ruidx=(document.formulaire.ruidx.value.length != 0);
    bullet_ruidx=(valid_ruidx)? bullet_ok: bullet_nok;
    document.formulaire.bull_ruidx.src=bullet_ruidx;

    issubmitable();
    return valid_ruidx;
}

/*----------------------------------------------------------------------------*/
/* Validation du champ "trafr"                                                */
/*     retour: valid_trafr est faux si la zone est vide                       */
/*             bull_trafr est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_trafr() {
    // Suppression des espaces de tête
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
/*             bull_fridx est rattaché à une bulle ok ou nok                  */
/*----------------------------------------------------------------------------*/
function onblur_fridx() {
    // Suppression des espaces de tête
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
/*     paramtère: le nom du champ faisant office de signet dans l'aide        */
/*     UI: Ouverture de la fenêtre d'aide et positionnement sur le signet     */
/*----------------------------------------------------------------------------*/
function on_help(signet) {
    var str_uri = "ruvocedit_hlp.php"
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
<h1>Mise &agrave; jour d'un vocable russe</h1>
<form name="formulaire" id="formulaire" action="ruvocedit_upd.php" method="POST" 
  onsubmit="return isvalid();">

<!--  Identifiant (masqué)                                                   -->
<input type="hidden" name="id" id="id"
	value=<?php echo "\"" . ((isset($_POST["id"]))? $_POST["id"]: 
  	($_POST['ruvocedit_mod']!="ins")? $line["id"]: "") . "\""; ?>/>

<table>
<!--  Vocable russe                                                          -->
<tr>
<td><a onClick="on_help('ruvoc')"><label for="ruvoc">Vocable russe</label></a></td>
<td><input class="russe" type="text" name="ruvoc" id="ruvoc" size="32" maxlength="64" onBlur="onblur_ruvoc()"
	value=<?php	echo "\"" . ((isset($_POST['str_ruvoc']))? hed_he($_POST['str_ruvoc']): 
	(($_POST['ruvocedit_mod']!="ins")? hed_he($line['str_ruvoc']): "")) . "\""; ?>/>
&nbsp;<img name="bull_ruvoc" id="bull_ruvoc" src="../redbullet.gif"></td>

<td><a onClick="on_help('ruidx')"><label for="ruidx">Index russe</label></a></td>
<td><input class="russe" type="text" name="ruidx" id="ruidx" size="32" maxlength="64"
  onblur="onblur_ruidx()" value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo "\"" . $line["str_ruidx"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_ruidx" id="bull_ruidx" src="../redbullet.gif"></td>
</tr>

<!--  Catégorie vocable russe et prononciation                          -->
<tr>
<td><a onClick="on_help('rucat')"><label for="rucat">Cat.voc.russe</label></a></td>
<td><input type="text" name="rucat" id="rucat" size="32" maxlength="32"
  value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo "\"" . $line["str_rucat"] . "\"";
    else
        echo "\"\"";
?>/></td>
<td><a onClick="on_help('prono')"><label for="prono">Prononciation</label></a></td>
<td><input type="text" name="prono" id="prono" size="32" maxlength="64"
  onblur="onblur_prono()" value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo "\"" . $line["str_prono"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_prono" id="bull_prono" src="../redbullet.gif"></td>
</tr>
</table>

<!--  Contexte vocable russe                                               -->
<a onClick="on_help('ructx')"><label for="ructx">Contexte</label></a><br>
<textarea class="russe" name="ructx" rows="2" cols="60" wrap="soft"><?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo $line["str_ructx"]; 
?></textarea>
<hr>
<table>
<!--  Vocable français                                                       -->
<tr>
<td><a onClick="on_help('trafr')"><label for="trafr">Traduc.Fran&ccedil;.</label></a></td>
<td><input type="text" name="trafr" id="trafr" size="32" maxlength="64"
  onblur="onblur_trafr()" value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo "\"" . $line["str_trafr"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_trafr" id="bull_trafr" src="../redbullet.gif"></td>
<td><a onClick="on_help('fridx')"><label for="fridx">Index fran&ccedil;.</label></a></td>
<td><input type="text" name="fridx" id="fridx" size="32" maxlength="64"
  onblur="onblur_fridx()" value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo "\"" . $line["str_fridx"] . "\"";
    else
        echo "\"\"";
?>/>&nbsp;<img name="bull_fridx" id="bull_fridx" src="../redbullet.gif"></td>
</tr>

<!--  Catégorie vocable français                                            -->
<tr>
<td><a onClick="on_help('frcat')"><label for="frcat">Cat.voc.fran&ccedil;.</label></a></td>
<td><input type="text" name="frcat" id="frcat" size="32" maxlength="32"
  value=<?php
    if ($_POST["ruvocedit_mod"]!="ins")
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
    if ($_POST["ruvocedit_mod"]!="ins")
        echo $line["str_frctx"]; 
?></textarea>

<br><br>
<!--  Notes                                                                -->
<a onClick="on_help('notes')"><label for="notes">Notes</label></a><br>
<textarea name="notes" rows="7" cols="60" wrap="soft"><?php
    if ($_POST["ruvocedit_mod"]!="ins")
        echo $line["str_notes"]; 
?></textarea>

<!----------------------------------------------------------------------------->
<!--                                  BOUTONS                                -->
<!----------------------------------------------------------------------------->
<hr>
<input type="button" name="ann" id="ann"
	value=<?php print (($_POST["ruvocedit_mod"]!="visu")? "\"Annuler\"": "\"Retour\""); ?> 
	onClick="onreturn()"/>
  &nbsp;&nbsp;<input type="hidden" name="code_action" id="code_action"/><?php
    if ($_POST["ruvocedit_mod"]=="ins") {
        print "\n<input type='submit' name='ins' id='ins'" .
            "\n\tvalue='Cr&eacute;er' onclick=\"onInsert()\"/>";
    } else {
		if ($_POST["ruvocedit_mod"]!="visu") {
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
