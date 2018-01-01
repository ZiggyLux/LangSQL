<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. ruphrlistis.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Gestion du catalogues des phrases russes           -->
<!--                      Phrases de la liste sélectionnée                   -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../liste/liste.inc.php");
	include_once("../util/app_ses.inc.php");

	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
	$listenom =
		transfereVariablePostEnSession($_SERVER['SCRIPT_NAME'], 'listenom');
	$id_liste =
		transfereVariablePostEnSession($_SERVER['SCRIPT_NAME'], 'id_liste');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="langue,russe,phrase">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Phrases de la liste "<?php print $listenom; ?>"</title>
</head>
<body>
<?php include("menu_russe.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var pageRetour =
	<?php print "\"{$_SESSION['arrRetPag'][$_SERVER['SCRIPT_NAME']]}\"" ?>;

var listenom = <?php print	"\"{$listenom}\"" ?>;
var listetype = <?php echo D_LISTE_RUPHR; ?>;
var id_liste = <?php print	"\"{$id_liste}\"" ?>;

/*----------------------------------------------------------------------------*/
/* Page de retour                                                             */
/*----------------------------------------------------------------------------*/
function onreturn(str) {
  document.formulaire.action = pageRetour;
  document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Suppression de l'item sélectionné de la liste                              */
/*----------------------------------------------------------------------------*/
function onsup(str) {
	document.formulaire.action = "ruphrlistis_sup.php";
	addNomValVariableToForm("formulaire", "id_liste", id_liste);
	addNomValVariableToForm("formulaire", "id_item_asup", str);
	
	document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Fonction de pickup d'un nouvel item pour cette liste                       */
/* rmq: A la différence d'un pick-up habituel qui retourne une valeur 'id'    */
/*      pour le formulaire, il s'agit ici de créer une nouvelle association   */
/*      dans la table item. Cette association sera visible au rechargement    */
/*      de cette page                                                         */
/*----------------------------------------------------------------------------*/
function pickup_item() {
	document.formulaire.action = "ruphrpickup.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "id_liste", id_liste);
	addNomVarRetToForm("formulaire", "id_item_acreer");
	
	document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en sélection pour modification ou suppression                -->
<!----------------------------------------------------------------------------->
function onsel(str) {
    document.formulaire.action = "ruphredit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruphredit_mod", "maj");
	addNomValVariableToForm("formulaire", "ruphredit_sel", str);
	
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.ruphrlistis_pos_val.value = idx.substr(0, 64);
	document.formulaire.ruphrlistis_pos_id.value = id;

    document.formulaire.action = "ruphrlistis.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les phrases de la base de données       --> 
<!----------------------------------------------------------------------------->
<h1>Phrases de la liste "<?php print $listenom; ?>" - Gestion</h1>
<form name="formulaire" id="formulaire" method="POST">

<table width="700px" border="0"><tr>
<td><input type="button" name="return" id="return"
  value="Retour" onClick="onreturn()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onClick="pickup_item()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="ruphrlistis_cont_col">
    <option value="str_ruphr"
		<?php if (isset($_POST['ruphrlistis_cont_col'])
			&& $_POST['ruphrlistis_cont_col']=="str_ruphr")
				echo "selected"; ?> >Phrase russe</option>
    <option value="str_ruidx"	
		<?php if (isset($_POST['ruphrlistis_cont_col'])
			&& $_POST['ruphrlistis_cont_col']=="str_ruidx")
				echo "selected"; ?> >Index russe</option>
    <option value="str_frphr"	
		<?php if (isset($_POST['ruphrlistis_cont_col'])
			&& $_POST['ruphrlistis_cont_col']=="str_frphr")
				echo "selected"; ?> >Phrase fran&ccedil;aise</option>
    <option value="str_indic"
		<?php if (isset($_POST['ruphrlistis_cont_col'])
			&& $_POST['ruphrlistis_cont_col']=="str_indic")
				echo "selected"; ?> >Indication</option>
</select>
<input type="submit" value="Contenant" onClick="onsearch()"/>
<input type="text" name="ruphrlistis_cont_txt" id="ruphrlistis_cont_txt" size="16" maxlength="80"
	<?php 
	if (isset($_POST['ruphrlistis_cont_txt']))
		echo "value=\"" . hed_he($_POST['ruphrlistis_cont_txt']) . "\"";
	?>/>
</fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_RUPHR_PAGELENGTH", 12);
	define ("D_APPW_LOC_RUPHR_PAGES", 14);
	define ("D_APPW_LOC_RUPHR_LIMIT", (D_APPW_LOC_RUPHR_PAGELENGTH * D_APPW_LOC_RUPHR_PAGES + 1));
	define ("D_APPW_LOC_RUPHR_INDEXBYLINE", 7);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_RUPHR_INDEXBYLINE) == 0)
			print "<br>\n";
		$str_val = htmlentities($item->val, ENT_COMPAT, "UTF-8");
		$str_idx = htmlentities($item->idx, ENT_COMPAT, "UTF-8");
		print "\t\t<span class=\"page_index\" "
			. "onclick=\"onposition('{$str_idx}', '{$item->id}')\">"
			. "&gt; <b>" . $item->idx . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $link = connect_db();

	/* Insertion éventuelle d'un nouvel item (retour du pickup) */
	if (isset($_POST['id_item_acreer'])	&& strlen($_POST['id_item_acreer'])>0) {
		$result = exec_query("INSERT INTO item SET"
			. " id_liste = {$id_liste},"
			. " id_item = {$_POST['id_item_acreer']},"
			. " id_type =" . D_LISTE_RUPHR);
	}

    /* Performing SQL query */
	
	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['ruphrlistis_pos_val'])
		&& isset($_POST['ruphrlistis_pos_id']) && strlen($_POST['ruphrlistis_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["ruphrlistis_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_ruidx";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0" 
				. " AND id >= " . $_POST["ruphrlistis_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_ruphr";
	if (isset($_POST['ruphrlistis_cont_col']))
		switch($_POST['ruphrlistis_cont_col']) {
		case "str_ruidx": $where_col = "str_ruidx"; break;
		case "str_frphr": $where_col = "str_frphr"; break;
		case "str_indic": $where_col = "str_indic"; break;
		}
	if (array_key_exists("ruphrlistis_cont_txt", $_POST)
		&& strlen(ltrim($_POST["ruphrlistis_cont_txt"])) > 0)
		$where_cond = "(ruphr." . $where_col . " LIKE \"%"
			. addslashes($_POST["ruphrlistis_cont_txt"]) . "%\")";
	
    $query = "SELECT ruphr.id, ruphr.str_ruphr, ruphr.str_ruidx, ruphr.str_indic "
		. "FROM item LEFT JOIN ruphr ON item.id_item=ruphr.id "
		. "WHERE item.id_type =" . D_LISTE_RUPHR . " "
		. "   AND item.id_liste = {$id_liste} "
		. "   AND {$where_pos} AND {$where_cond} "
		. "ORDER BY ruphr.str_ruidx, ruphr.id "
		. "LIMIT " . D_APPW_LOC_RUPHR_LIMIT;

	$result = exec_query($query);

    /* Printing results in HTML */
    print "<table width='700px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Phrase</th>\n";
	print "\t\t<th>Actions</th>\n";
    print "\t</tr>\n";

	/* By page walking: loop initialisation */
	$iPage = 1;
	$iLine = 0;
	$arrPage = array();
    
	$fPair = false;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

		/* By page walking: line and page counting */
		$iLine++;
		if ($iLine > D_APPW_LOC_RUPHR_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_ruphr'], $line['str_ruidx'], $line['id']));
		}

		if ($iPage == 1) {
			$fPair = !$fPair;
			$trClass = ($fPair)? "pair" : "impair";
			print "\t<tr class='{$trClass}'>\n";
			print "\t\t<td onclick=\"onsel('{$line['id']}')\">{$line['str_ruphr']}";
			if (strlen($line['str_indic']) == 0)
				print "</td>\n";
			else
				print ", <i>{$line['str_indic']}</i></td>\n";
			print "\t\t<td onclick=\"onsup('{$line['id']}')\" align=\"center\">"
				. "<img src=\"../ico16-assoc-delete.gif\" alt=\"Supprimer de la liste\"/></td>\n";
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
<!-- By page walking: http data -->
<input type="hidden" name="ruphrlistis_pos_val" id="ruphrlistis_pos_val" value=""/>
<input type="hidden" name="ruphrlistis_pos_id" id="ruphrlistis_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
