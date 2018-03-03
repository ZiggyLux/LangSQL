<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvocbrowse.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Gestion des vocables en russe - navigation         -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../liste/liste.inc.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,vocable">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en russe - Gestion</title>
</head>
<body>
<?php include("menu_russe.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variables globales														  */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var listetype = <?php echo D_LISTE_RUVOC; ?>;

/*----------------------------------------------------------------------------*/
/* SOUMISSION en sélection pour modification ou suppression                   */
/*----------------------------------------------------------------------------*/
function onsel(str) {
    document.formulaire.action = "ruvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvocedit_mod", "maj");
	addNomValVariableToForm("formulaire", "ruvocedit_sel", str);
	
    document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION pour affichage des listes liées                                 */
/*----------------------------------------------------------------------------*/
function onruvocliste(str, evt) {
	document.formulaire.action = "ruvocinlist.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listetype", listetype);
	addNomValVariableToForm("formulaire", "id_item", str);
	
    document.formulaire.submit();
	evt.cancelBubble=true;
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION en création                                                     */
/*----------------------------------------------------------------------------*/
function onnew(str) {
    document.formulaire.action = "ruvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvocedit_mod", "ins");
	
    document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION en recherche                                                    */
/*----------------------------------------------------------------------------*/
function onsearch() {
    document.formulaire.action = "ruvocbrowse.php";
    // Bouton de type submit...
}

/*----------------------------------------------------------------------------*/
/* SOUMISSION en repositionnement                                             */
/*----------------------------------------------------------------------------*/
function onposition(idx, id) {
	document.formulaire.ruvocbrowse_pos_val.value = idx.substr(0, 64);
	document.formulaire.ruvocbrowse_pos_id.value = id;

    document.formulaire.action = "ruvocbrowse.php";
    document.formulaire.submit();
}

/*----------------------------------------------------------------------------*/
/* Gestion par listes                                                         */
/*----------------------------------------------------------------------------*/
function onlistes() {
    document.formulaire.action = "../liste/listebrowse.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listetype", listetype);
	
    document.formulaire.submit();
}
//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les vocables de la base de données      --> 
<!----------------------------------------------------------------------------->
<h1>Vocabulaire russe - Gestion</h1>
<form name="formulaire" id="formulaire" action="ruvocedit.php" method="POST">

<table width="700px" border="0"><tr>
<td><input type="button" name="listes" id="listes"
  value="Listes" onClick="onlistes()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onClick="onnew()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="ruvocbrowse_cont_col">
    <option value="str_ruvoc"
		<?php if (isset($_POST['ruvocbrowse_cont_col'])
			&& $_POST['ruvocbrowse_cont_col']=="str_ruvoc")
				echo "selected"; ?> >Entr&eacute;e russe</option>
    <option value="str_ruidx"	
		<?php if (isset($_POST['ruvocbrowse_cont_col'])
			&& $_POST['ruvocbrowse_cont_col']=="str_ruidx")
				echo "selected"; ?> >Index russe</option>
    <option value="str_trafr"	
		<?php if (isset($_POST['ruvocbrowse_cont_col'])
			&& $_POST['ruvocbrowse_cont_col']=="str_trafr")
				echo "selected"; ?> >Entr&eacute;e fran&ccedil;aise</option>
    <option value="str_fridx"
		<?php if (isset($_POST['ruvocbrowse_cont_col'])
			&& $_POST['ruvocbrowse_cont_col']=="str_fridx")
				echo "selected"; ?> >Index fran&ccedil;ais</option>
</select>
<input type="submit" value="Contenant" onClick="onsearch()"/>
<input type="text" name="ruvocbrowse_cont_txt" id="ruvocbrowse_cont_txt" size="16" maxlength="80"
	<?php 
	if (isset($_POST['ruvocbrowse_cont_txt']))
		echo "value=\"" . hed_he($_POST['ruvocbrowse_cont_txt']) . "\"";
	?>/>
</fieldset></td>
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
			. "&gt; <b>" . $item->idx . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $dbh = connect_db();

    /* Performing SQL query */
	
	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['ruvocbrowse_pos_val'])
		&& isset($_POST['ruvocbrowse_pos_id']) && strlen($_POST['ruvocbrowse_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["ruvocbrowse_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_ruidx";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND id >= " . $_POST["ruvocbrowse_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_ruvoc";
	if (isset($_POST['ruvocbrowse_cont_col']))
		switch($_POST['ruvocbrowse_cont_col']) {
		case "str_ruidx": $where_col = "str_ruidx"; break;
		case "str_trafr": $where_col = "str_trafr"; break;
		case "str_fridx": $where_col = "str_fridx"; break;
		}
	if (array_key_exists("ruvocbrowse_cont_txt", $_POST)
		&& strlen(ltrim($_POST["ruvocbrowse_cont_txt"])) > 0)
		$where_cond = "(" . $where_col . " LIKE \"%" 
			. addslashes($_POST["ruvocbrowse_cont_txt"]) . "%\")";
		
	$query = "SELECT id, str_ruvoc, str_ruidx, str_ructx "
		. "FROM ruvoc WHERE {$where_pos} AND {$where_cond} "
		. "ORDER BY str_ruidx, id "
		. "LIMIT " . D_APPW_LOC_RUVOC_LIMIT;
		
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
		
    /* Printing results in HTML */
    print "<table width='700px' style='font-size:14pt'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th width='50px'>Actions</th>\n";
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
		if ($iLine > D_APPW_LOC_RUVOC_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_ruvoc'], $line['str_ruidx'], $line['id']));
		}

		if ($iPage == 1) {
			print "\t<tr class=\"{$trClass}\" onclick=\"onsel('{$line['id']}')\">\n";

			print "\t\t<td>";
			print "<span onclick=\"onruvocliste('{$line['id']}', event)"
			  	. "\"><img src=\"../ico16-liste.gif\" alt=\"Listes contenant ce vocable\"/></span>";
			print "</td>\n";
			
			print "\t\t<td>"
			  . $line['str_ruvoc'];
			  if (strlen($line['str_ructx']) == 0)
				print "</td>\n";
			  else
				print " <i>({$line['str_ructx']})</i></td>\n";
				
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
<input type="hidden" name="ruvocbrowse_pos_val" id="ruvocbrowse_pos_val" value=""/>
<input type="hidden" name="ruvocbrowse_pos_id" id="ruvocbrowse_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
