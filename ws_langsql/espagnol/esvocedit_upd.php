<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. esvocedit_upd.php                                  -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des vocables espagnols                  -->
<!--                      - Edition - MAJ DB                                 -->
<!-- Emplacement......... \espagnol                                          -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_sql.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="espagnol,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocabulaire espagnol - Edition</title>
</head>
<body>
<?php include("menu_espagnol.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

var pageRetour = <?php print "\"{$_POST['retpag']}\""; ?>;

/*----------------------------------------------------------------------------*/
/* Page de retour                                                             */
/*----------------------------------------------------------------------------*/
function onreturn(str) {
	document.formulaire.action = pageRetour;
	// Bouton de type submit...
}

//-->
</script>
<h1>Mise &agrave; jour vocabulaire espagnol - Confirmation</h1>
<hr >
<?php

/*----------------------------------------------------------------------------*/
/* Retourne requête d'insertion d'un objet dans la base de données            */
/*----------------------------------------------------------------------------*/
function insert_row () {
    /* Construction de la requête SQL */
	$prep_esvoc = addslashes($_POST['esvoc']);
    $query = "INSERT INTO esvoc SET"
        . " str_esvoc=\"{$prep_esvoc}\","
        . " str_esidx=\"{$_POST['esidx']}\","
        . " str_escat=\"{$_POST['escat']}\","
        . " str_esctx=\"{$_POST['esctx']}\","
        . " str_prono=\"{$_POST['prono']}\","
        . " str_trafr=\"{$_POST['trafr']}\","
        . " str_fridx=\"{$_POST['fridx']}\","
        . " str_frcat=\"{$_POST['frcat']}\","
        . " str_frctx=\"{$_POST['frctx']}\","
	    . " str_notes=\"{$_POST['notes']}\"";
    $query = $query
	. ";";
	
    return $query;
}

/*----------------------------------------------------------------------------*/
/* Retourne requte de mise  jour d'un objet dans la base de donnes         */
/*----------------------------------------------------------------------------*/
function update_row () {
    /* Construction de la requte SQL */
	$prep_esvoc = addslashes($_POST['esvoc']);
    $query = "UPDATE esvoc SET"
        . " str_esvoc=\"{$prep_esvoc}\","
        . " str_esidx=\"{$_POST['esidx']}\","
        . " str_escat=\"{$_POST['escat']}\","
        . " str_esctx=\"{$_POST['esctx']}\","
        . " str_prono=\"{$_POST['prono']}\","
        . " str_trafr=\"{$_POST['trafr']}\","
        . " str_fridx=\"{$_POST['fridx']}\","
        . " str_frcat=\"{$_POST['frcat']}\","
        . " str_frctx=\"{$_POST['frctx']}\","
	    . " str_notes=\"{$_POST['notes']}\"";
    $query = $query
	. " WHERE id=\"{$_POST['id']}\" ;";

    return $query;			 
}

/*----------------------------------------------------------------------------*/
/* Retourne requte de suppression d'un objet dans la base de donnes         */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requte SQL */
    $query = "DELETE FROM esvoc WHERE id=\"{$_POST['id']}\" ;";
    return $query;
}
?>

<!----------------------------------------------------------------------------->
<!-- Point d'entre PHP                                                      -->
<!--     connexion, excution SQL suivant $_POST['action'], dconnexion      -->
<!----------------------------------------------------------------------------->
<?php
    $link = connect_db();

    switch ($_POST["code_action"]) {
    case "ins":
        exec_query(insert_row());
        break;
    case "maj":
        exec_query(update_row());
        break;
    case "sup":
        exec_query(delete_row());
        break;
    default:
        die("Action not implemented");
    }

    disconnect_db($link);
	
    echo "L'objet " . hed_he($_POST['esvoc']) . " a &eacute;t&eacute; mis &agrave; jour."; 
?>
<form name="formulaire_upd" id="formulaire_upd" method="POST">
<p><input type="submit" onclick="onreturn()" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
