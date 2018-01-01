<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. listebrowse.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Défilement des listes                              -->
<!-- Emplacement......... liste                                              -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
	$listetype =
		transfereVariablePostEnSession($_SERVER['SCRIPT_NAME'], 'listetype');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="listes">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Listes <?php
	switch($listetype) {
	case "1": print("de vocables russes"); break;
	case "2": print("de verbes russes"); break;
	case "3": print("de phrases russes"); break;
	default: print("de ???");
	}
?> - Gestion</title>
</head>

<body>
<?php include("../russe/menu_russe.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variables globales														  */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var pageRetour =
	<?php print "\"{$_SESSION['arrRetPag'][$_SERVER['SCRIPT_NAME']]}\"" ?>;

var listetype = <?php print	"\"{$listetype}\"" ?>;

<!----------------------------------------------------------------------------->
<!-- RETOUR                                                                  -->
<!----------------------------------------------------------------------------->
function onreturn() {
	document.formulaire.action = pageRetour;
	document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en création                                                  -->
<!----------------------------------------------------------------------------->
function onnew() {
    document.formulaire.action = "listeedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listeedit_mod", "ins");
	addNomValVariableToForm("formulaire", "listetype", listetype);
	
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en sélection pour modification ou suppression                -->
<!----------------------------------------------------------------------------->
function onedit(liste_id, liste_nom, evt) {
	evt.cancelBubble = true;
    document.formulaire.action = "listeedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listeedit_mod", "maj");
	addNomValVariableToForm("formulaire", "listetype", listetype);
	addNomValVariableToForm("formulaire", "listeedit_sel", liste_id);
	addNomValVariableToForm("formulaire", "listenom", liste_nom);
	
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en création                                                  -->
<!----------------------------------------------------------------------------->
function onlisteobjet(liste_id, liste_nom) {
	document.formulaire.action = <?php
		switch($listetype) {
		case "1": print "\"../russe/ruvoclistis.php\""; break;
		case "2": print "\"../russe/ruvrblistis.php\""; break;
		case "3": print "\"../russe/ruphrlistis.php\""; break;
		}
		?>;
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listetype", listetype);
	addNomValVariableToForm("formulaire", "listenom", liste_nom);
	addNomValVariableToForm("formulaire", "id_liste", liste_id);
	
    document.formulaire.submit();
}
<!----------------------------------------------------------------------------->
<!-- SOUMISSION en recherche                                                 -->
<!----------------------------------------------------------------------------->
function onsearch() {
    document.formulaire.action = "listebrowse.php";
	// Bouton SUBMIT...
}
<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.listebrowse_pos_val.value = idx.substr(0, 64);
	document.formulaire.listebrowse_pos_id.value = id;

    document.formulaire.action = "listebrowse.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les livres de la base de données        --> 
<!----------------------------------------------------------------------------->
<h1>Listes <?php 
	switch($listetype) {
	case "1": print("de vocables russes"); break;
	case "2": print("de verbes russes"); break;
	case "3": print("de phrases russes"); break;
	default: print("de ???");
	}
?> - Gestion</h1>
<form name="formulaire" id="formulaire" onSubmit="onsearch()" method="POST">

<table width="500px" border="0"><tr>
<td><input type="button" name="return" id="return"
  value="Retour" onClick="onreturn()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onClick="onnew()"/></td>
<td align="right" width="300px"><fieldset>
<legend>Recherche suivant nom</legend>
<input type="submit" value="Contenant" onClick="onsearch()"/>
<input type="text" name="listebrowse_cont_txt" id="listebrowse_cont_txt" size="16" maxlength="16"
	   value=""/>
</fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_LISTE_PAGELENGTH", 12);
	define ("D_APPW_LOC_LISTE_PAGES", 14);
	define ("D_APPW_LOC_LISTE_LIMIT", (D_APPW_LOC_LISTE_PAGELENGTH * D_APPW_LOC_LISTE_PAGES + 1));
	define ("D_APPW_LOC_LISTE_INDEXBYLINE", 2);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_LISTE_INDEXBYLINE) == 0)
			print "<br>\n";
		$str_val = htmlspecialchars($item->val, ENT_QUOTES, "UTF-8");
		$str_idx = htmlspecialchars($item->idx, ENT_QUOTES, "UTF-8");
		print "\t\t<span class=\"page_index\" "
			. "onclick=\"onposition('{$str_idx}', '{$item->id}')\">"
			. "&gt; <b>" . $item->idx . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $link = connect_db();

    /* Performing SQL query */
	
	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['listebrowse_pos_val']) && isset($_POST['listebrowse_pos_id'])
		&& strlen($_POST['listebrowse_pos_val']) > 0) {

		$where_pos_val = addslashes($_POST["listebrowse_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_nom";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND id >= " . $_POST["listebrowse_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	if (array_key_exists("listebrowse_cont_txt", $_POST)
		&& strlen(ltrim($_POST["listebrowse_cont_txt"])) > 0)
		$where_cond = "(str_nom LIKE \"%" . addslashes($_POST["listebrowse_cont_txt"]) . "%\")";
	
    $query = "SELECT id, id_type, str_nom "
		. "FROM liste "
		. "WHERE id_type={$listetype} "
		. "   AND {$where_pos} AND {$where_cond} "
		. "ORDER BY id_type, str_nom, id "
		. "LIMIT " . D_APPW_LOC_LISTE_LIMIT;

    $result = exec_query($query);

    /* Printing results in HTML */
    print "<table width='500px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Liste</th>\n";
	print "\t\t<th>Action</th>\n";
    print "\t</tr>\n";

	/* By page walking: loop initialisation */
	$iPage = 1;
	$iLine = 0;
	$arrPage = array();
    
	$fPair = false;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

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
			print "\t<tr class=\"{$trClass}\""
			  . " onclick=\"onlisteobjet('{$line['id']}', '"
			  . addslashes($line['str_nom']) . "')\">\n";
			print "\t\t<td>{$line['str_nom']}</td>\n";
			print "\t\t<td"
			  . " onclick=\"onedit('{$line['id']}', '"
			  . addslashes($line['str_nom']) . "', event)\""
			  . " align=\"center\"><img src=\"../ico16-liste-edit.gif\" alt=\"Editer la description\"/></td>";
			print "\t</tr>\n";
		}
    }
    print "</table>\n";

	/* By page walking: display other pages */
	array_walk($arrPage, 'arrayWalkPageValue');

    /* Free resultset */
    mysql_free_result($result);

    /* Closing connection */
	disconnect_db($link);
?>
<input type="hidden" name="listeedit_sel" id="listeedit_sel" value="0"/>
<input type="hidden" name="listeedit_mod" id="listeedit_mod" value=""/>
<input type="hidden" name="id_liste" id="id_liste" value="0"/>
<input type="hidden" name="listenom" id="listenom" value=""/>

<!-- By page walking: http data -->
<input type="hidden" name="listebrowse_pos_val" id="listebrowse_pos_val" value=""/>
<input type="hidden" name="listebrowse_pos_id" id="listebrowse_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
