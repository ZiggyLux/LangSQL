<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvrblistis.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Gestion du catalogues des verbes russes            -->
<!--                      Vocables de la liste sélectionnée                  -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../liste/liste.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruutil.inc.php");
	
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
<meta name="keywords" content="langue,russe,verbe">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Verbes de la liste "<?php print $listenom; ?>"</title>
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

var listenom = <?php print	"\"{$listenom}\"" ?>;
var listetype = <?php echo D_LISTE_RUVRB; ?>;
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
	document.formulaire.action = "ruvrblistis_sup.php";
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
	document.formulaire.action = "ruvrbpickup.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "id_liste", id_liste);
	addNomVarRetToForm("formulaire", "id_item_acreer");
	
	document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en sélection pour modification ou suppression                -->
<!----------------------------------------------------------------------------->
function onsel(str) {
    document.formulaire.action = "ruvrbedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvrbedit_mod", "maj");
	addNomValVariableToForm("formulaire", "ruvrbedit_sel", str);
	
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.ruvrblistis_pos_val.value = idx.substr(0, 64);
	document.formulaire.ruvrblistis_pos_id.value = id;

    document.formulaire.action = "ruvrblistis.php";
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement de la table avec les verbes de la base de données        --> 
<!----------------------------------------------------------------------------->
<div id = "principal">
<h2>Verbes de la liste "<?php print $listenom; ?>" - Gestion</h2>
</div>
<form name="formulaire" id="formulaire" method="post">

<table width="700px" border="0"><tr>
<td><input type="button" name="return" id="return"
  value="Retour" onclick="onreturn()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onclick="pickup_item()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="ruvrblistis_cont_col">
    <option value="str_ruvrb_inf"	
		<?php if (isset($_POST['ruvrblistis_cont_col'])
			&& $_POST['ruvrblistis_cont_col']=="str_ruvrb_inf") 
				echo "selected"; ?> >Infinitif russe</option>
    <option value="str_indic"
		<?php if (isset($_POST['ruvrblistis_cont_col'])
			&& $_POST['ruvrblistis_cont_col']=="str_indic") 
				echo "selected"; ?> >Traduction</option>
</select>
<input type="submit" name="contenant_btn" id="contenant_btn" 
  value="Contenant" onclick="onsearch()"/>
<input type="text" name="ruvrblistis_cont_txt" id="ruvrblistis_cont_txt" size="16" maxlength="16"
	<?php 
	if (isset($_POST['ruvrblistis_cont_txt']))
		echo "value=\"" . hed_he($_POST['ruvrblistis_cont_txt']) . "\"";
	?>/>
</fieldset></td>
</tr></table>
<?php
	include_once("../util/app_sql.inc.php");

	/* By page walking: definition */
	include_once("../util/app_pag.inc.php");
	
	define ("D_APPW_LOC_RUVRB_PAGELENGTH", 12);
	define ("D_APPW_LOC_RUVRB_PAGES", 14);
	define ("D_APPW_LOC_RUVRB_LIMIT", (D_APPW_LOC_RUVRB_PAGELENGTH * D_APPW_LOC_RUVRB_PAGES + 1));
	define ("D_APPW_LOC_RUVRB_INDEXBYLINE", 7);

	function arrayWalkPageValue($item, $key) {
		if (($key % D_APPW_LOC_RUVRB_INDEXBYLINE) == 0)
			print "<br>\n";
		$str_val = htmlentities($item->val, ENT_COMPAT, "UTF-8");
		$str_idx = htmlentities($item->idx, ENT_COMPAT, "UTF-8");
		print "\t\t<span class=\"page_index\" "
			. "onclick=\"onposition('{$str_idx}', '{$item->id}')\">"
			. "&gt; <b>" . remove_accent($item->idx) . "</b></span>&nbsp;&nbsp;\n";
	}

    /* Connecting, selecting database */
    $dbh = connect_db();

	/* Insertion éventuelle d'un nouvel item (retour du pickup) */
	if (isset($_POST['id_item_acreer'])	&& strlen($_POST['id_item_acreer'])>0) {
		$sql = "INSERT INTO item SET"
			. " id_liste = {$id_liste},"
			. " id_item = {$_POST['id_item_acreer']},"
			. " id_type =" . D_LISTE_RUVRB;
		if (($result = $dbh->exec($sql)) === FALSE) {
		    echo "Erreur DB à l'insertion : ";
		    echo $sql;
		    exit();
		}
	}

    /* Performing SQL query */

	/* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['ruvrblistis_pos_val']) 
		&& isset($_POST['ruvrblistis_pos_id']) && strlen($_POST['ruvrblistis_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["ruvrblistis_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_ruvipna_inf";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND id >= " . $_POST["ruvrblistis_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_ruvrb_inf";
	if (isset($_POST['ruvrblistis_cont_col']))
		switch($_POST['ruvrblistis_cont_col']) {
		case "str_indic": $where_col = "str_indic"; break;
		}
	if (array_key_exists("ruvrblistis_cont_txt", $_POST) 
		&& strlen(ltrim($_POST["ruvrblistis_cont_txt"])) > 0)
	    if ($where_col == "str_ruvrb_inf") {
	        $where_cond	= "(str_ruvipna_inf LIKE \"%"
                . addslashes($_POST["ruvrblistis_cont_txt"]) . "%\"";
            $where_cond = $where_cond . " OR str_ruvpdna_inf LIKE \"%"
                . addslashes($_POST["ruvrblistis_cont_txt"]) . "%\"";
            $where_cond = $where_cond . " OR str_ruvpina_inf LIKE \"%"
                . addslashes($_POST["ruvrblistis_cont_txt"]) . "%\"";
            $where_cond .= ")";
	    } else
	        $where_cond = "(" . $where_col . " LIKE \"%"
                . addslashes($_POST["ruvrblistis_cont_txt"]) . "%\")";
    
    $query = "SELECT ruvrb.id, ruvrb.str_ruvip_inf, ruvrb.str_ruvipna_inf, "
        .           "ruvrb.str_ruvpd_inf, ruvrb.str_ruvpi_inf "
		. "FROM item LEFT JOIN ruvrb ON item.id_item=ruvrb.id "
		. "WHERE item.id_type =" . D_LISTE_RUVRB . " "
		. "   AND item.id_liste = {$id_liste} "
		. "   AND {$where_pos} AND {$where_cond} "
		. "ORDER BY str_ruvipna_inf, id "
		. "LIMIT " . D_APPW_LOC_RUVRB_LIMIT;

	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
		
    /* Printing results in HTML */
    print "<table width='700px'>\n";
	
    /* Header */
    print "\t<tr>\n";
    print "\t\t<th>Imperfectif</th>\n";
    print "\t\t<th>Perfectif</th>\n";
    print "\t\t<th>Ind&eacute;termin&eacute;</th>\n";
    print "\t\t<th>Actions</th>\n";
    print "\t</tr>\n";

	/* By page walking: loop initialisation */
	$iPage = 1;
	$iLine = 0;
	$arrPage = array();
    
	$fPair = false;
	while ($line = $result->fetch(PDO::FETCH_ASSOC)) {

		/* By page walking: line and page counting */
		$iLine++;
		if ($iLine > D_APPW_LOC_RUVRB_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_ruvip_inf'], $line['str_ruvipna_inf'], $line['id']));
		}

		if ($iPage == 1) {
			$fPair = !$fPair;
			$trClass = ($fPair)? "pair" : "impair";
			
			print "\t<tr class='{$trClass}'>\n";
			print "\t\t<td onclick=\"onsel('{$line['id']}')\">" .
			     change_accent_HTML($line['str_ruvip_inf']) . "</td>\n";
			print "\t\t<td onclick=\"onsel('{$line['id']}')\">" .
			     change_accent_HTML($line['str_ruvpd_inf']) . "</td>\n";
			print "\t\t<td onclick=\"onsel('{$line['id']}')\">" .
			     change_accent_HTML($line['str_ruvpi_inf']) . "</td>\n";
			print "\t\t<td onclick=\"onsup('{$line['id']}')\" align=\"center\">"
				. "<img src=\"../ico16-assoc-delete.gif\" alt=\"Supprimer de la liste\"/></td>\n";
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
<input type="hidden" name="ruvrblistis_pos_val" id="ruvrblistis_pos_val" value=""/>
<input type="hidden" name="ruvrblistis_pos_id" id="ruvrblistis_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
