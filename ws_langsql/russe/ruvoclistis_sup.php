<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. ruvoclistis_sup.php                                -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Vocable d'une liste - SUP DB                       -->
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
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocable d'une liste - Suppression</title>
</head>
<body>
<?php include("../russe/menu_russe.inc.php"); ?>
<h1>Suppression d'un vocable d'une liste - Confirmation</h1>
<hr >
<?php
/*----------------------------------------------------------------------------*/
/* Retourne requête de suppression d'un vocable dans la base de données       */
/*----------------------------------------------------------------------------*/
function delete_row () {
    /* Construction de la requête SQL */
    $query = "DELETE FROM item"
		. " WHERE id_liste = {$_POST['id_liste']}"
		. "   AND id_item = {$_POST['id_item_asup']}"
		. "   AND id_type = " . D_LISTE_RUVOC . ";";
    return $query;
}
?>

<!----------------------------------------------------------------------------->
<!-- Point d'entrée PHP                                                      -->
<!--     connexion, exécution SQL suivant $_POST['code_action'], déconnexion -->
<!----------------------------------------------------------------------------->
<?php
    $link = connect_db();

    exec_query(delete_row());

    disconnect_db($link);
	
    print "<br>\nL'association a &eacute;t&eacute; supprim&eacute;e."; 
?>
<form name="formulaire" id="formulaire" action="ruvoclistis.php" method="POST">
<p><input type="submit" value="Retour"/></p>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
