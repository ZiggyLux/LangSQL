<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvrbedit_upd.php                                  -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des verbes russes - Edition - MAJ DB    -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_sql.inc.php");
	include_once("../liste/liste.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,verbes">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Mise &agrave; jour verbes russes - Confirmation</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
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
<div id = "principal">
<h2>Mise &agrave; jour verbes russes - Confirmation</h2>
</div>
<hr >
<?php
/*----------------------------------------------------------------------------*/
/* Retourne requête d'insertion d'un objet dans la base de données            */
/*----------------------------------------------------------------------------*/
function insert_row () {
    /* Construction de la requête SQL */
    $query = "INSERT INTO ruvrb SET"
		. " str_indic			=\"{$_POST['indic']}\","
		. " str_notes			=\"{$_POST['notes']}\","
		. " str_ruvip_inf		=\"{$_POST['ruvip_inf']}\","
		. " str_ruvip_pre_1s	=\"{$_POST['ruvip_pre_1s']}\","
		. " str_ruvip_pre_2s	=\"{$_POST['ruvip_pre_2s']}\","
		. " str_ruvip_pre_3p	=\"{$_POST['ruvip_pre_3p']}\","
		. " str_ruvip_pas_ms	=\"{$_POST['ruvip_pas_ms']}\","
		. " str_ruvip_pas_fs	=\"{$_POST['ruvip_pas_fs']}\","
		. " str_ruvip_pas_ns	=\"{$_POST['ruvip_pas_ns']}\","
		. " str_ruvip_pas_pl	=\"{$_POST['ruvip_pas_pl']}\","
		. " str_ruvpd_inf		=\"{$_POST['ruvpd_inf']}\","
		. " str_ruvpd_pre_1s	=\"{$_POST['ruvpd_pre_1s']}\","
		. " str_ruvpd_pre_2s	=\"{$_POST['ruvpd_pre_2s']}\","
		. " str_ruvpd_pre_3p	=\"{$_POST['ruvpd_pre_3p']}\","
		. " str_ruvpd_pas_ms	=\"{$_POST['ruvpd_pas_ms']}\","
		. " str_ruvpd_pas_fs	=\"{$_POST['ruvpd_pas_fs']}\","
		. " str_ruvpd_pas_ns	=\"{$_POST['ruvpd_pas_ns']}\","
		. " str_ruvpd_pas_pl	=\"{$_POST['ruvpd_pas_pl']}\","
		. " str_ruvpi_inf		=\"{$_POST['ruvpi_inf']}\","
		. " str_ruvpi_pre_1s	=\"{$_POST['ruvpi_pre_1s']}\","
		. " str_ruvpi_pre_2s	=\"{$_POST['ruvpi_pre_2s']}\","
		. " str_ruvpi_pre_3p	=\"{$_POST['ruvpi_pre_3p']}\","
		. " str_ruvpi_pas_ms	=\"{$_POST['ruvpi_pas_ms']}\","
		. " str_ruvpi_pas_fs	=\"{$_POST['ruvpi_pas_fs']}\","
		. " str_ruvpi_pas_ns	=\"{$_POST['ruvpi_pas_ns']}\","
		. " str_ruvpi_pas_pl	=\"{$_POST['ruvpi_pas_pl']}\"";
    $query = $query
	. ";";
	
    return $query;
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de mise à jour d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function update_row () {
    /* Construction de la requête SQL */
    $query = "UPDATE ruvrb SET"
		. " str_indic			=\"{$_POST['indic']}\","
		. " str_notes			=\"{$_POST['notes']}\","
		. " str_ruvip_inf		=\"{$_POST['ruvip_inf']}\","
		. " str_ruvip_pre_1s	=\"{$_POST['ruvip_pre_1s']}\","
		. " str_ruvip_pre_2s	=\"{$_POST['ruvip_pre_2s']}\","
		. " str_ruvip_pre_3p	=\"{$_POST['ruvip_pre_3p']}\","
		. " str_ruvip_pas_ms	=\"{$_POST['ruvip_pas_ms']}\","
		. " str_ruvip_pas_fs	=\"{$_POST['ruvip_pas_fs']}\","
		. " str_ruvip_pas_ns	=\"{$_POST['ruvip_pas_ns']}\","
		. " str_ruvip_pas_pl	=\"{$_POST['ruvip_pas_pl']}\","
		. " str_ruvpd_inf		=\"{$_POST['ruvpd_inf']}\","
		. " str_ruvpd_pre_1s	=\"{$_POST['ruvpd_pre_1s']}\","
		. " str_ruvpd_pre_2s	=\"{$_POST['ruvpd_pre_2s']}\","
		. " str_ruvpd_pre_3p	=\"{$_POST['ruvpd_pre_3p']}\","
		. " str_ruvpd_pas_ms	=\"{$_POST['ruvpd_pas_ms']}\","
		. " str_ruvpd_pas_fs	=\"{$_POST['ruvpd_pas_fs']}\","
		. " str_ruvpd_pas_ns	=\"{$_POST['ruvpd_pas_ns']}\","
		. " str_ruvpd_pas_pl	=\"{$_POST['ruvpd_pas_pl']}\","
		. " str_ruvpi_inf		=\"{$_POST['ruvpi_inf']}\","
		. " str_ruvpi_pre_1s	=\"{$_POST['ruvpi_pre_1s']}\","
		. " str_ruvpi_pre_2s	=\"{$_POST['ruvpi_pre_2s']}\","
		. " str_ruvpi_pre_3p	=\"{$_POST['ruvpi_pre_3p']}\","
		. " str_ruvpi_pas_ms	=\"{$_POST['ruvpi_pas_ms']}\","
		. " str_ruvpi_pas_fs	=\"{$_POST['ruvpi_pas_fs']}\","
		. " str_ruvpi_pas_ns	=\"{$_POST['ruvpi_pas_ns']}\","
		. " str_ruvpi_pas_pl	=\"{$_POST['ruvpi_pas_pl']}\"";
    $query = $query
	. " WHERE id=\"{$_POST['id']}\" ;";

    return $query;			 
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de suppression d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM ruvrb WHERE id=\"{$_POST['id']}\" ;";
    return $query;
}

function delete_row_items () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM item WHERE id_item={$_POST['id']} AND id_type=\"" . D_LISTE_RUVRB . "\";";
    return $query;
}
?>

<!----------------------------------------------------------------------------->
<!-- Point d'entrée PHP                                                      -->
<!--     connexion, exécution SQL suivant $_POST['code_action'], déconnexion -->
<!----------------------------------------------------------------------------->
<?php
    $dbh = connect_db();

    switch ($_POST["code_action"]) {
    case "ins":
        $sql = insert_row();
        if (($result = $dbh->exec($sql)) === FALSE) {
            echo "Erreur DB à l'insertion : ";
            echo $sql;
            exit();
        }
        break;
    case "maj":
        $sql = update_row();
        if (($result = $dbh->exec($sql)) === FALSE) {
            echo "Erreur DB à la mise à jour : ";
            echo $sql;
            exit();
        }
        break;
    case "sup":
        $sql = delete_row_items();
        if (($result = $dbh->exec($sql)) === FALSE) {
            echo "Erreur DB à la mise à jour : ";
            echo $sql;
            exit();
        }
        $sql = delete_row();
        if (($result = $dbh->exec($sql)) === FALSE) {
            echo "Erreur DB à la mise à jour : ";
            echo $sql;
            exit();
        }
        break;
    default:
        die("Action not implemented");
    }

    disconnect_db($dbh);
	
    echo "L'objet {$_POST['id']} a &eacute;t&eacute; mis &agrave; jour."; 
?>
<form name="formulaire" id="formulaire" method="post">
<p><input type="submit" onclick="onreturn()" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
