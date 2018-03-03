<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilit                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. esvocbrowse.php                                    -->
<!-- Dernire MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brve description... Gestion des vocables en espagnol - navigation      -->
<!-- Emplacement......... \espagnol                                          -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="espagnol,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en espagnol - Gestion</title>
</head>
<body>
<?php include("menu_espagnol.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variables globales														  */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

/*----------------------------------------------------------------------------*/
/*-- SOUMISSION en sélection pour modification ou suppression                -*/
/*----------------------------------------------------------------------------*/
function onsel(str) {
    document.formulaire.action = "esvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "esvocedit_mod", "maj");
	addNomValVariableToForm("formulaire", "esvocedit_sel", str);
	
    document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/*-- SOUMISSION en création                                                  -*/
/*----------------------------------------------------------------------------*/
function onnew(str) {
    document.formulaire.action = "esvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "esvocedit_mod", "ins");

    document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/*-- SOUMISSION en recherche                                                 -*/
/*----------------------------------------------------------------------------*/
function onsearch() {
    document.formulaire.action = "esvocbrowse.php";
    // Bouton de type submit...
}

/*----------------------------------------------------------------------------*/
/*-- SOUMISSION en repositionnement                                          -*/
/*----------------------------------------------------------------------------*/
function onposition(idx, id) {
	document.formulaire.esvocbrowse_pos_val.value = idx.substr(0, 64);
	document.formulaire.esvocbrowse_pos_id.value = id;

    document.formulaire.action = "esvocbrowse.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les vocables de la base de données      --> 
<!----------------------------------------------------------------------------->
<h1>Vocabulaire espagnol - Gestion</h1>
<form name="formulaire" id="formulaire" action="esvocedit.php" method="POST">

<table width="700px" border="0"><tr>
<td><input type="button" name="new" id="new"
  value="Cr&eacute;er" onClick="onnew()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="esvocbrowse_cont_col">
    <option value="str_esvoc"
		<?php if (isset($_POST['esvocbrowse_cont_col'])
			&& $_POST['esvocbrowse_cont_col']=="str_esvoc")
				echo "selected"; ?> >Entr&eacute;e espagnole</option>
    <option value="str_esidx"	
		<?php if (isset($_POST['esvocbrowse_cont_col'])
			&& $_POST['esvocbrowse_cont_col']=="str_esidx")
				echo "selected"; ?> >Index espagnol</option>
    <option value="str_trafr"	
		<?php if (isset($_POST['esvocbrowse_cont_col'])
			&& $_POST['esvocbrowse_cont_col']=="str_trafr")
				echo "selected"; ?> >Entr&eacute;e fran&ccedil;aise</option>
    <option value="str_fridx"
		<?php if (isset($_POST['esvocbrowse_cont_col'])
			&& $_POST['esvocbrowse_cont_col']=="str_fridx")
				echo "selected"; ?> >Index fran&ccedil;ais</option>
</select>
<input type="submit" value="Contenant" onClick="onsearch()"/>
<input type="text" name="esvocbrowse_cont_txt" id="esvocbrowse_cont_txt"
	size="16" maxlength="80"
	<?php 
	if (isset($_POST['esvocbrowse_cont_txt']))
		echo "value=\"" . hed_he($_POST['esvocbrowse_cont_txt']) . "\"";
	?>/>
</fieldset></td>
</tr></table>

<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_ESVOC_PAGELENGTH", 12);
	define ("D_APPW_LOC_ESVOC_PAGES", 14);
	define ("D_APPW_LOC_ESVOC_LIMIT", (D_APPW_LOC_ESVOC_PAGELENGTH * D_APPW_LOC_ESVOC_PAGES + 1));
	define ("D_APPW_LOC_ESVOC_INDEXBYLINE", 7);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_ESVOC_INDEXBYLINE) == 0)
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
	if (isset($_POST['esvocbrowse_pos_val']) && isset($_POST['esvocbrowse_pos_id'])
		&& strlen($_POST['esvocbrowse_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["esvocbrowse_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_esidx";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
					. " AND id >= " . $_POST["esvocbrowse_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_esvoc";
	if (isset($_POST['esvocbrowse_cont_col']))
		switch($_POST['esvocbrowse_cont_col']) {
		case "str_esidx": $where_col = "str_esidx"; break;
		case "str_trafr": $where_col = "str_trafr"; break;
		case "str_fridx": $where_col = "str_fridx"; break;
		}
	if (array_key_exists("esvocbrowse_cont_txt", $_POST)
		&& strlen(ltrim($_POST["esvocbrowse_cont_txt"])) > 0)
		$where_cond = "(" . $where_col . " LIKE \"%"
			. addslashes($_POST["esvocbrowse_cont_txt"]) . "%\")";
		
	$query = "SELECT id, str_esvoc, str_esidx, str_esctx "
		. "FROM esvoc WHERE {$where_pos} AND {$where_cond} "
		. "ORDER BY str_esidx, id "
		. "LIMIT " . D_APPW_LOC_ESVOC_LIMIT;

	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}

    /* Printing results in HTML */
    print "<table width='700px' style='font-size:14pt'>\n";
	
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
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		/* By page walking: line and page counting */
		$iLine++;
		if ($iLine > D_APPW_LOC_ESVOC_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_esvoc'], $line['str_esidx'], $line['id']));
		}

		if ($iPage == 1) {
			print "\t<tr class=\"{$trClass}\" onclick=\"onsel('{$line['id']}')\">\n";
			
			print "\t\t<td>"
			  . $line['str_esvoc'];
			  if (strlen($line['str_esctx']) == 0)
				print "</td>\n";
			  else
				print " <i>({$line['str_esctx']})</i></td>\n";
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
<input type="hidden" name="esvocbrowse_pos_val" id="esvocbrowse_pos_val" value=""/>
<input type="hidden" name="esvocbrowse_pos_id" id="esvocbrowse_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
