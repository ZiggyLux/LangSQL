<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. listeedit_upd.php                                  -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Répertoire des listes - Edition                    -->
<!--                      MAJ DB                                             -->
<!-- Emplacement......... liste                                              -->
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
<meta name="keywords" content="liste">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Liste - Edition</title>
</head>

<body>
<?php include("../russe/menu_russe.inc.php"); ?>
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
<h1>Mise &agrave; jour d'une liste - Confirmation</h1>
<hr >
<?php
/*----------------------------------------------------------------------------*/
/* Retourne requête d'insertion d'un objet dans la base de données            */
/*----------------------------------------------------------------------------*/
function insert_row () {
    /* Construction de la requête SQL */
	$prep_nom = addslashes($_POST['nom']);
	$prep_notes = addslashes($_POST['notes']);
    $query = "INSERT INTO liste SET"
		. " id_type={$_POST['listetype']},"
        . " str_nom=\"{$prep_nom}\","
        . " str_notes=\"{$prep_notes}\"";
    $query = $query	. ";";
	
    return $query;
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de mise à jour d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function update_row () {
    /* Construction de la requête SQL */
	$prep_nom = addslashes($_POST['nom']);
	$prep_notes = addslashes($_POST['notes']);
    $query = "UPDATE liste SET"
		. " id_type={$_POST['listetype']},"
        . " str_nom=\"{$prep_nom}\","
        . " str_notes=\"{$prep_notes}\"";
    $query = $query
	. " WHERE id=\"{$_POST['id']}\" ;";

    return $query;			 
}

/*----------------------------------------------------------------------------*/
/* Retourne requête de suppression d'un objet dans la base de données         */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM liste WHERE id={$_POST['id']} ;";
    return $query;
}

function delete_row_items () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM item WHERE id_liste={$_POST['id']} AND id_type={$_POST['listetype']} ;";
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
	
    print "<br>\nLa liste " . htmlspecialchars($_POST['nom'], ENT_QUOTES)
		. " a &eacute;t&eacute; mise &agrave; jour."; 
?>
<form name="formulaire" id="formulaire" method="POST">
<p><input type="submit" onclick="onreturn()" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
