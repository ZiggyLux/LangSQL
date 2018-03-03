<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruphredit_upd.php                                  -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des phrases russes - Edition - MAJ DB   -->
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
<meta name="keywords" content="russe,phrase">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Mise &agrave; jour phrases russes - Confirmation</title>
</head>
<body>
<?php include("menu_russe.inc.php"); ?>
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
<h1>Mise &agrave; jour d'une phrase russe - Confirmation</h1>
<hr >
<?php
/*----------------------------------------------------------------------------*/
/* Retourne requête d'insertion d'un objet dans la base de données            */
/*----------------------------------------------------------------------------*/
function insert_row () {
    /* Construction de la requte SQL */
    $query = "INSERT INTO ruphr SET"
        . " str_ruphr=\"{$_POST['ruphr']}\","
        . " str_ruidx=\"{$_POST['ruidx']}\","
        . " str_indic=\"{$_POST['indic']}\","
        . " str_frphr=\"{$_POST['frphr']}\","
	    . " str_notes=\"{$_POST['notes']}\","
        . " str_rupho=\"{$_POST['rupho']}\","
        . " str_audio=\"{$_POST['audio']}\"";
    $query = $query
	. ";";
	
    return $query;
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de mise à jour d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function update_row () {
    /* Construction de la requête SQL */
    $query = "UPDATE ruphr SET"
        . " str_ruphr=\"{$_POST['ruphr']}\","
        . " str_ruidx=\"{$_POST['ruidx']}\","
        . " str_indic=\"{$_POST['indic']}\","
        . " str_frphr=\"{$_POST['frphr']}\","
	    . " str_notes=\"{$_POST['notes']}\","
        . " str_rupho=\"{$_POST['rupho']}\","
        . " str_audio=\"{$_POST['audio']}\"";
    $query = $query
	. " WHERE id=\"{$_POST['id']}\" ;";

    return $query;			 
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de suppression d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM ruphr WHERE id=\"{$_POST['id']}\" ;";
    return $query;
}

function delete_row_items () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM item WHERE id_item={$_POST['id']} AND id_type=\"" . D_LISTE_RUPHR . "\";";
    return $query;
}
?>

<!----------------------------------------------------------------------------->
<!-- Point d'entrée PHP                                                      -->
<!--     connexion, exécution SQL suivant $_POST['action'], déconnexion      -->
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
<form name="formulaire" id="formulaire" method="POST">
<p><input type="submit" onclick="onreturn()" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
