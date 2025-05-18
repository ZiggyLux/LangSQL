<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvrbbrowse.php                                    -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Gestion des verbes en russe - navigation           -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/ 
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../liste/liste.inc.php");
	include_once("ruutil.inc.php");
	?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,verbes">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Verbes en russe - Gestion</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variables globales														  */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

var listetype = <?php echo D_LISTE_RUVRB; ?>;

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
<!-- SOUMISSION pour affichage des listes liées                              -->
<!----------------------------------------------------------------------------->
function onruvrbliste(str, evt) {
	document.formulaire.action = "ruvrbinlist.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "listetype", listetype);
	addNomValVariableToForm("formulaire", "id_item", str);
	
    document.formulaire.submit();
	evt.cancelBubble=true;
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en création                                                  -->
<!----------------------------------------------------------------------------->
function onnew(str) {
    document.formulaire.action = "ruvrbedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvrbedit_mod", "ins");
	
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en recherche                                                 -->
<!----------------------------------------------------------------------------->
function onsearch() {
    document.formulaire.action = "ruvrbbrowse.php";
    // Bouton de type submit...
}


<!----------------------------------------------------------------------------->
<!-- Effacement du mot à rechercher                                          -->
<!----------------------------------------------------------------------------->
function clearcont() {
	document.formulaire.ruvrbbrowse_cont_txt.value = "";
}

<!----------------------------------------------------------------------------->
<!-- SOUMISSION en repositionnement                                          -->
<!----------------------------------------------------------------------------->
function onposition(idx, id) {
	document.formulaire.ruvrbbrowse_pos_val.value = idx.substr(0, 64);
	document.formulaire.ruvrbbrowse_pos_id.value = id;

    document.formulaire.action = "ruvrbbrowse.php";
    document.formulaire.submit();
}

<!----------------------------------------------------------------------------->
<!-- Gestion par listes                                                      -->
<!----------------------------------------------------------------------------->
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
<!--     Chargement de la table avec les verbes de la base de données        --> 
<!----------------------------------------------------------------------------->
<div id = "principal">
<h2>Verbes en russe - Gestion</h2>
</div>
<form name="formulaire" id="formulaire" action="ruvrbedit.php" method="post">

<table width="700px" border="0"><tr>
<td><input type="button" name="listes" id="listes"
  value="Listes" onclick="onlistes()"/>&nbsp;&nbsp;
<input type="button" name="new" id="new"
  value="Cr&eacute;er" onclick="onnew()"/></td>
<td align="right" width="400px"><fieldset>
<legend>Recherche suivant</legend>
<select name="ruvrbbrowse_cont_col">
    <option value="str_ruvrb_inf"	
		<?php if (isset($_POST['ruvrbbrowse_cont_col'])
			&& $_POST['ruvrbbrowse_cont_col']=="str_ruvrb_inf") 
				echo "selected"; ?> >Infinitif russe</option>
    <option value="str_indic"
		<?php if (isset($_POST['ruvrbbrowse_cont_col'])
			&& $_POST['ruvrbbrowse_cont_col']=="str_indic") 
				echo "selected"; ?> >Traduction</option>
</select>
<input type="submit" name="contenant_btn" id="contenant_btn" 
  value="Contenant" onclick="onsearch()"/>
<input type="text" name="ruvrbbrowse_cont_txt" id="ruvrbbrowse_cont_txt" size="16" maxlength="80"
	<?php 
	if (isset($_POST['ruvrbbrowse_cont_txt']))
		echo "value=\"" . hed_he($_POST['ruvrbbrowse_cont_txt']) . "\"";
	?>/>
<input type="button" name="videcont" id="videcont" value="*" onclick="clearcont()"/>
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

    /* Performing SQL query */


    /* By page walking : SQL condition */
	$where_pos = "(1 = 1)";
	if (isset($_POST['ruvrbbrowse_pos_val']) && isset($_POST['ruvrbbrowse_pos_id']) 
		&& strlen($_POST['ruvrbbrowse_pos_val']) > 0) {
		$where_pos_val = addslashes($_POST["ruvrbbrowse_pos_val"]);
		$where_pos_vallen = strlen($where_pos_val);
		$where_pos_idxval = "str_ruvipna_inf";
		$where_pos = "("
			. "STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val . "\") > 0 "
			. "OR (STRCMP(" . $where_pos_idxval . ", \"" . $where_pos_val ."\") = 0"
				. " AND id >= " . $_POST["ruvrbbrowse_pos_id"] . ")"
			. ")";
	}
	
	/* Other condition */
	$where_cond = "(1 = 1)";
	$where_col = "str_ruvrb_inf";
	if (isset($_POST['ruvrbbrowse_cont_col']))
		switch($_POST['ruvrbbrowse_cont_col']) {
		case "str_indic": $where_col = "str_indic"; break;
		}
	if (array_key_exists("ruvrbbrowse_cont_txt", $_POST) 
		&& strlen(ltrim($_POST["ruvrbbrowse_cont_txt"])) > 0)
	    if ($where_col == "str_ruvrb_inf") {
	        $where_cond	= "(str_ruvipna_inf LIKE \"%"
               . addslashes(remove_accent($_POST["ruvrbbrowse_cont_txt"])) . "%\"";
	        $where_cond = $where_cond . " OR str_ruvpdna_inf LIKE \"%"
               . addslashes(remove_accent($_POST["ruvrbbrowse_cont_txt"])) . "%\"";
	        $where_cond = $where_cond . " OR str_ruvpina_inf LIKE \"%"
               . addslashes(remove_accent($_POST["ruvrbbrowse_cont_txt"])) . "%\"";
             $where_cond .= ")";
	    } else
    		$where_cond = "(" . $where_col . " LIKE \"%" 
    			. addslashes($_POST["ruvrbbrowse_cont_txt"]) . "%\")";
		
    $query = "SELECT id, str_ruvip_inf, str_ruvipna_inf, str_ruvpd_inf, str_ruvpi_inf "
        . "FROM ruvrb WHERE {$where_pos} AND {$where_cond} " 
		. "ORDER BY str_ruvipna_inf, id "
		. "LIMIT " . D_APPW_LOC_RUVRB_LIMIT;

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
    print "\t\t<th>Imperfectif</th>\n";
    print "\t\t<th>Perfectif</th>\n";
    print "\t\t<th>Ind&eacute;termin&eacute;</th>\n";
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
		if ($iLine > D_APPW_LOC_RUVRB_PAGELENGTH) {
			$iLine=1; $iPage++;
			// Milestone the index
			array_push($arrPage, new O_Milestone($line['str_ruvip_inf'], $line['str_ruvipna_inf'], $line['id']));
		}

		if ($iPage == 1) {
			print "\t<tr class=\"{$trClass}\" onclick=\"onsel('{$line['id']}')\">\n";

			print "\t\t<td>";
			print "<span onclick=\"onruvrbliste('{$line['id']}', event)"
			  	. "\"><img src=\"../ico16-liste.gif\" alt=\"Listes contenant ce verbe\"/></span>";
			print "</td>\n";
			
			print "\t\t<td>" . change_accent_HTML($line['str_ruvip_inf']) . "</td>\n";
		   
			print "\t\t<td>" . change_accent_HTML($line['str_ruvpd_inf']) . "</td>\n";
			print "\t\t<td>" . change_accent_HTML($line['str_ruvpi_inf']) . "</td>\n";
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
<input type="hidden" name="ruvrbbrowse_pos_val" id="ruvrbbrowse_pos_val" value=""/>
<input type="hidden" name="ruvrbbrowse_pos_id" id="ruvrbbrowse_pos_id" value="0"/>

</form>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
