<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvocpickup.php                                   -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Sélection d'un vocable                             -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruutil.inc.php");
	
	ouvertureSession();
	associePageRetour($_SERVER['SCRIPT_NAME'], "retpag");
	associeNomVariableRetour($_SERVER['SCRIPT_NAME'], "selvar");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="langue,russe,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocable - S&eacute;lection</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
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
    document.formulaire.action = "ruvocpickup.php";
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.pick_pos_val.value = idx.substr(0, 64);
	document.formulaire.pick_pos_id.value = id;

    document.formulaire.action = "ruvocpickup.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec vocables de la base de données          --> 
<!----------------------------------------------------------------------------->
<div id = "principal">
<h2>Vocable - S&eacute;lection</h2>
</div>
<form name="formulaire" id="formulaire" onsubmit="onsearch()" method="post">
<?php
	include_once("../util/app_mod_hidposvar.inc.php");
	hidePostedVar();
?>

<table width="700px" border="0"><tr>
<td><input type="button" value="Retour" onclick="onreturn()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="ruvocpickup_cont_col">
    <option value="str_ruvoc"
		<?php if (isset($_POST['ruvocpickup_cont_col'])
			&& $_POST['ruvocpickup_cont_col']=="str_ruvoc") 
				echo "selected"; ?> >Entr&eacute;e russe</option>
    <option value="str_ruidx"	
		<?php if (isset($_POST['ruvocpickup_cont_col'])
			&& $_POST['ruvocpickup_cont_col']=="str_ruidx") 
				echo "selected"; ?> >Index russe</option>
    <option value="str_trafr"	
		<?php if (isset($_POST['ruvocpickup_cont_col'])
			&& $_POST['ruvocpickup_cont_col']=="str_trafr") 
				echo "selected"; ?> >Entr&eacute;e fran&ccedil;aise</option>
    <option value="str_fridx"
		<?php if (isset($_POST['ruvocpickup_cont_col'])
			&& $_POST['ruvocpickup_cont_col']=="str_fridx") 
				echo "selected"; ?> >Index fran&ccedil;ais</option>
</select>
<input type="submit" name="contenant_btn" id="contenant_btn" 
  value="Contenant" onclick="onsearch()"/>
<input type="text" name="ruvocpickup_cont_txt" id="ruvocpickup_cont_txt" size="16" maxlength="80"
	<?php 
	if (isset($_POST['ruvocpickup_cont_txt']))
		echo "value=\"" . hed_he($_POST['ruvocpickup_cont_txt']) . "\"";
	?>/></fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_RUVOC_PAGELENGTH", 12);
	define ("D_APPW_LOC_RUVOC_PAGES", 14);
	define ("D_APPW_LOC_RUVOC_LIMIT", (D_APPW_LOC_RUVOC_PAGELENGTH * D_APPW_LOC_RUVOC_PAGES + 1));
	define ("D_APPW_LOC_RUVOC_INDEXBYLINE", 7);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_RUVOC_INDEXBYLINE) == 0)
			print "<br>\n";
		$str_val = htmlentities($item->val, ENT_COMPAT, "UTF-8");
		$str_idx = htmlentities($item->idx, ENT_COMPAT, "UTF-8");
		print "\t\t<span class=\"page_index\" "
			. "onclick=\"onposition('{$str_idx}', '{$item->id}')\">"
			. "&gt; <b>" . remove_accent($item->idx) . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $dbh = connect_db();

    /* Performing SQL query */

	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['pick_pos_val']) && isset($_POST['pick_pos_id'])
		&& strlen($_POST['pick_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["pick_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_ruidxna";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND id >= " . $_POST["pick_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_ruvocna";
	if (isset($_POST['ruvocpickup_cont_col']))
		switch($_POST['ruvocpickup_cont_col']) {
		case "str_ruidx": $where_col = "str_ruidx"; break;
		case "str_trafr": $where_col = "str_trafr"; break;
		case "str_fridx": $where_col = "str_fridx"; break;
		}
	if (array_key_exists("ruvocpickup_cont_txt", $_POST) 
		&& strlen(ltrim($_POST["ruvocpickup_cont_txt"])) > 0)
		$where_cond = "ruvoc." . $where_col . " LIKE \"%" 
			. addslashes($_POST["ruvocpickup_cont_txt"]) . "%\"";
	
    $query = "SELECT ruvoc.id, ruvoc.str_ruvoc, ruvoc.str_ruidxna, ruvoc.str_ructx"
		. " FROM ruvoc LEFT JOIN item ON ruvoc.id=item.id_item AND item.id_liste={$_POST['id_liste']}"
		. " WHERE item.id_item IS NULL"
		. "     AND {$where_pos} AND {$where_cond}"
		. " ORDER BY ruvoc.str_ruidxna, ruvoc.id"
		. " LIMIT " . D_APPW_LOC_RUVOC_LIMIT;

	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
		
    /* Printing results in HTML */
    print "<table width='700px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Vocable</th>\n";
    print "\t</tr>\n";

	/* By page walking: loop initialisation */
	$iPage = 1;
	$iLine = 0;
	$arrPage = array();
    
	$fPair = false;
	while ($line = $result->fetch(PDO::FETCH_ASSOC)) {
		/* By page walking: line and page counting */
		$iLine++;
		if ($iLine > D_APPW_LOC_RUVOC_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_ruvoc'], $line['str_ruidxna'], $line['id']));
		}

		if ($iPage == 1) {
			$fPair = !$fPair;
			$trClass = ($fPair)? "pair" : "impair";
			print "\t<tr class=\"{$trClass}\" onclick=\"onsel('{$line['id']}')\">\n";
			print "\t\t<td>" .
			    change_accent_HTML($line['str_ruvoc']);
			if (strlen($line['str_ructx']) == 0)
				print "</td>\n";
			else {
			    $str = change_accent_HTML($line['str_ructx']);
			    print " <span class=\"ctx\">{$str}</span></td>\n";
			}
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
