<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... Liste                                              -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. listepickup.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Sélection d'une liste                              -->
<!-- Emplacement......... liste                                              -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
	associeNomVariableRetour($_SERVER['SCRIPT_NAME'], "selvar");
	$listetype =
		transfereVariablePostEnSession($_SERVER['SCRIPT_NAME'], 'listetype');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="liste">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Liste - S&eacute;lection</title>
</head>

<body>
<?php include("../russe/ru_menu.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var pageRetour =
	<?php print "\"{$_SESSION['arrRetPag'][$_SERVER['SCRIPT_NAME']]}\"" ?>;
var strNomVarRetour = 
	<?php print "\"{$_SESSION['arrNomVarRetour'][$_SERVER['SCRIPT_NAME']]}\"" ?>;

var listetype = <?php print	"\"{$listetype}\"" ?>;

/*----------------------------------------------------------------------------*/
/* Page de retour                                                             */
/*----------------------------------------------------------------------------*/
function onreturn() {
  document.formulaire.action = pageRetour;
  document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION en sélection (retour à la page initiale)                        */
/*----------------------------------------------------------------------------*/
function onsel(str) {
  document.formulaire.action = pageRetour;
  addNomValVariableToForm("formulaire", strNomVarRetour, str);
  
  document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION en recherche                                                    */
/*----------------------------------------------------------------------------*/
function onsearch() {
    document.formulaire.action = "listepickup.php";
	// Bouton SUBMIT...
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.pick_pos_val.value = idx.substr(0, 64);
	document.formulaire.pick_pos_id.value = id;

    document.formulaire.action = "listepickup.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les éditeurs de la base de données      --> 
<!----------------------------------------------------------------------------->
<div id = "principal">
<h2>Listes - S&eacute;lection</h2>
</div>
<form name="formulaire" id="formulaire" onsubmit="onsearch()" method="post">
<?php
	include_once("../util/app_mod_hidposvar.inc.php");
	hidePostedVar();
?>

<table width="500px" border="0"><tr>
<td><input type="button" value="Retour" onclick="onreturn()"/></td>
<td align="right" width="300px"><fieldset>
<legend>Recherche suivant nom</legend>
<input type="submit" value="Contenant" onclick="onsearch()"/>
<input type="text" name="listepickup_cont_txt" id="listepickup_cont_txt" size="16" maxlength="16"
	   value=""/>
</fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_LISTE_PAGELENGTH", 16);
	define ("D_APPW_LOC_LISTE_PAGES", 20);
	define ("D_APPW_LOC_LISTE_LIMIT", (D_APPW_LOC_LISTE_PAGELENGTH * D_APPW_LOC_LISTE_PAGES + 1));
	define ("D_APPW_LOC_LISTE_INDEXBYLINE", 7);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_LISTE_INDEXBYLINE) == 0)
			print "<br>\n";
		$str_val = htmlentities($item->val, ENT_COMPAT, "UTF-8");
		$str_idx = htmlentities($item->idx, ENT_COMPAT, "UTF-8");
		print "\t\t<span class=\"page_index\" "
			. "onclick=\"onposition('{$str_idx}', '{$item->id}')\">"
			. "&gt; <b>" . $item->idx . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $dbh = connect_db();

    /* Performing SQL query */

	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['pick_pos_val']) && isset($_POST['pick_pos_id']) && strlen($_POST['pick_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["pick_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_nom";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND liste.id >= " . $_POST["pick_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	if (array_key_exists("listepickup_cont_txt", $_POST)
		&& strlen(ltrim($_POST["listepickup_cont_txt"])) > 0)
		$where_cond = "liste.str_nom LIKE \"%" . addslashes($_POST["listepickup_cont_txt"]) . "%\"";
	
    if (isset($_POST['id_item']) && strlen($_POST['id_item']) > 0) {
		/* Select a list not yet linked with a given item */
		$query = "SELECT liste.id, liste.str_nom "
			. "FROM liste LEFT JOIN item ON liste.id=item.id_liste AND item.id_item={$_POST['id_item']} "
			. "WHERE liste.id_type={$listetype} "
			. "   AND item.id_liste IS NULL "
			. "   AND {$where_pos} AND {$where_cond} "
			. "ORDER BY liste.id_type, liste.str_nom, liste.id "
			. "LIMIT " . D_APPW_LOC_LISTE_LIMIT;
	} else {
		/* Select a list */
		$query = "SELECT liste.id, liste.str_nom "
			. "FROM liste  "
			. "WHERE liste.id_type={$listetype} "
			. "   AND {$where_pos} AND {$where_cond} "
			. "ORDER BY liste.id_type, liste.str_nom, liste.id "
			. "LIMIT " . D_APPW_LOC_LISTE_LIMIT;
	}

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
    print "\t</tr>\n";

	/* By page walking: loop initialisation */
	$iPage = 1;
	$iLine = 0;
	$arrPage = array();
    
	$fPair = false;
	while ($line = $result->fetch(PDO::FETCH_ASSOC)) {
		/* By page walking: line and page counting */
		$iLine++;
		if ($iLine > D_APPW_LOC_LISTE_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_nom'], $line['str_nom'], $line['id']));
		}

		if ($iPage == 1) {
			$fPair = !$fPair;
			$trClass = ($fPair)? "pair" : "impair";
			print "\t<tr class=\"{$trClass}\" onclick=\"onsel('{$line['id']}')\">\n";
			print "\t\t<td>{$line['str_nom']}</td>\n";
			print "\t</tr>\n";
		}
    }
    print "</table>\n";

	/* By page walking: display other pages */
	array_walk($arrPage, 'arrayWalkPageValue');

    /* Free resultset */
	$result = NULL;
	
    /* Closing connection */
	disconnect_db($dbh);
?>
<!-- By page walking: http data -->
<input type="hidden" name="pick_pos_val" id="pick_pos_val" value=""/>
<input type="hidden" name="pick_pos_id" id="pick_pos_id" value="0"/>
</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
