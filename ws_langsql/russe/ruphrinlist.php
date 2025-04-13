<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruphrinlist.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Gestion du catalogues des vocables                 -->
<!--                      Listes comportant une phrase                       -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("../liste/liste.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
	$id_item =
		transfereVariablePostEnSession($_SERVER['SCRIPT_NAME'], 'id_item');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="langue,russe,phrase,liste">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Phrases russes - Listes d'une phrase</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var pageRetour = <?php print "\"{$_SESSION['arrRetPag'][$_SERVER['SCRIPT_NAME']]}\"" ?>;

var listetype = <?php echo D_LISTE_RUPHR; ?>;

var id_item = <?php print	"\"{$id_item}\"" ?>;

/*----------------------------------------------------------------------------*/
/* Page de retour                                                             */
/*----------------------------------------------------------------------------*/
function onreturn() {
	document.formulaire.action = pageRetour;
	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Page de retour                                                             */
/*----------------------------------------------------------------------------*/
function onsup(str) {
	document.formulaire.action = "ruphrinlist_sup.php";
	addNomValVariableToForm("formulaire", "listetype", listetype);
	addNomValVariableToForm("formulaire", "id_liste_asup", str);
	addNomValVariableToForm("formulaire", "id_item", id_item);

	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Fonction de pickup d'une nouvelle liste pour cette phrase                  */
/* rmq: A la différence d'un pick-up habituel qui retourne une valeur 'id'    */
/*      pour le formulaire, il s'agit ici de créer une nouvelle association   */
/*      dans la table item. Cette association sera visible au rechargement    */
/*      de cette page                                                         */
/*----------------------------------------------------------------------------*/
function pickup_liste() {
	document.formulaire.action = "../liste/listepickup.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomVarRetToForm("formulaire", "id_liste_acreer");
	addNomValVariableToForm("formulaire", "listetype", listetype);
	
	document.formulaire.submit();
}
//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les vocables de la base de données      --> 
<!----------------------------------------------------------------------------->
<div id = "principal">
<h2>Liste d'une phrase - Gestion</h2>
</div>
<form name="formulaire" id="formulaire" method="post">

<table width="500px" border="0"><tr>
<td><input type="button" value="Retour" onclick="onreturn()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onclick="pickup_liste()"/></td>
<td align="right" width="300px"><fieldset>
<legend>Recherche suivant</legend>
<input type="submit" value="Contenant" onclick="onsearch()"/>
<input type="text" name="ruphrinlist_cont_txt" id="ruphrinlist_cont_txt" size="16" maxlength="16"
	   value=""/>
</fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

    /* Connecting, selecting database */
    $dbh = connect_db();

	/* Insertion éventuelle d'une nouvelle liste (retour du pickup) */
	if (isset($_POST['id_liste_acreer'])
		&& strlen($_POST['id_liste_acreer'])>0) {
		$sql = "INSERT INTO item SET"
			. " id_liste = {$_POST['id_liste_acreer']},"
			. " id_item = {$id_item},"
			. " id_type = " . D_LISTE_RUPHR;
		if (($result = $dbh->exec($sql)) === FALSE) {
		    echo "Erreur DB à l'insertion : ";
		    echo $sql;
		    exit();
		}
	}

    /* Performing SQL query */
	$where_cond = "";
	if (array_key_exists("ruphrinlist_cont_txt", $_POST)
		&& strlen(ltrim($_POST['ruphrinlist_cont_txt'])) > 0)
		$where_cond = "AND liste.str_nom LIKE \"%"
			. addslashes($_POST['ruphrinlist_cont_txt']) . "%\" ";
	
    $query =
		"SELECT liste.id, liste.str_nom"
		. " FROM item LEFT JOIN liste ON item.id_liste=liste.id"
		. " WHERE item.id_type = ". D_LISTE_RUPHR
		. "   AND item.id_item = {$id_item}"
		. "   {$where_cond}"
		. " ORDER BY liste.str_nom";

    if (($result = $dbh->query($query)) === FALSE) {
        echo 'Erreur dans la requête SQL : ';
        echo $query;
        exit();
    }
    
    /* Printing results in HTML */
    print "<table width='500px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Liste</th>\n";
	print "\t\t<th>Action</th>\n";
    print "\t</tr>\n";

	$fPair = false;
	while ($line = $result->fetch(PDO::FETCH_ASSOC)) {
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";
	    print "\t<tr class=\"{$trClass}\">\n";
		print "\t\t<td onclick=\"onsel('{$line['id']}')\">{$line['str_nom']}</td>\n";
	    print "\t\t<td onclick=\"onsup('{$line['id']}')\" align=\"center\">"
			. "<img src=\"../ico16-assoc-delete.gif\" alt=\"Supprimer l'objet de la liste\"/></td>\n";
        print "\t</tr>\n";
    }
    print "</table>\n";

    /* Free resultset */
    $result = NULL;

    /* Closing connection */
	disconnect_db($dbh);
?>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
