<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvrbinlist_sup.php                                -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Verbes russes - Edition - SUP DB                   -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../liste/liste.inc.php");
	include_once("../util/app_sql.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="langue,russe,vocable,liste">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Liste d'un verbe - Suppression</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
<div id = "principal">
<h2>Suppression de la liste d'un verbe - Confirmation</h2>
</div>
<hr >
<?php
/*----------------------------------------------------------------------------*/
/* Retourne requête de suppression d'un verbe dans la base de données         */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM item"
		. " WHERE id_liste = {$_POST['id_liste_asup']}"
		. "   AND id_item = {$_POST['id_item']}"
		. "   AND id_type =" . D_LISTE_RUVRB . ";";
    return $query;
}
?>

<!----------------------------------------------------------------------------->
<!-- Point d'entrée PHP                                                      -->
<!--     connexion, exécution SQL suivant $_POST['code_action'], déconnexion -->
<!----------------------------------------------------------------------------->
<?php
    $dbh = connect_db();

    $sql = delete_row();
    if (($result = $dbh->exec($sql)) === FALSE) {
        echo "Erreur DB à la mise à jour : ";
        echo $sql;
        exit();
    }
    
    disconnect_db($dbh);
	
    print("<br>\nL'association a &eacute;t&eacute; supprim&eacute;e."); 
?>
<form name="formulaire" id="formulaire" action="ruvocinlist.php" method="post">
<p><input type="submit" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
