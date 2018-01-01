<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 4, MySQL, Javascript                 -->
<!-- Source.............. ruvocquery_prono.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Russe: interrogation sur la prononciation          -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruvocquery.inc.php");

	ouvertureSession();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,vocable,query,prononciation">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en russe - Exercice de prononciation</title>
</head>
<body>
<?php include("menu_russe.inc.php"); ?>
<script language="javascript" type="text/javascript">
<!--

/*----------------------------------------------------------------------------*/
/* Variables globales														  */
/*----------------------------------------------------------------------------*/
const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

/*----------------------------------------------------------------------------*/
/* Affichage de la fiche du vocable                                           */
/*     paramètre: identification du vocable                                   */
/*----------------------------------------------------------------------------*/
function on_reponse(id_voc) {
    document.formulaire.action = "ruvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvocedit_mod", "visu");
	addNomValVariableToForm("formulaire", "ruvocedit_sel", id_voc);
	
    document.formulaire.submit();
}
//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des éléments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Vocabulaire russe - Exercice de prononciation</h1>
<form name="formulaire" id="formulaire" action="ruvocquery_prono.php" method="POST">
<?php
	include_once("../util/app_sql.inc.php");

   /* Connexion et selection de la base de données */
    $link = connect_db();

	/* Tirage aléatoire */
	
	if (isset($_POST["id_lgrtest"])) {
		$lgrtestDef = 15; // default value
		$lgrtest = intval($_POST["id_lgrtest"]);
		if (($lgrtest <= 0) || ($lgrtest > 100))
			$lgrtest = $lgrtestDef;
		
		if ((isset($_POST["id_lisDef_prono"]))
			&& (isset($_POST["etendue"])) && ($_POST["etendue"]=="L"))
			$selstr = random_ids_fromlist($lgrtest, intval($_POST["id_lisDef_prono"]));
		else
			$selstr = random_ids($lgrtest);

		// Sauvegarde en session le résultat du tirage
		$_SESSION["lgrtest"] = $lgrtest;
		$_SESSION["selstr"] = $selstr;
	} else {
		// Cas du repositionnement ou de la réentrance
		$lgrtest = $_SESSION["lgrtest"];
		$selstr = $_SESSION["selstr"];
	}

	/* Sélection des éléments tirés au hasard */
    $result = exec_query(
    	"SELECT id, str_ruvoc, str_ructx, str_prono "
	    	. " FROM ruvoc"
    		. " WHERE id in ({$selstr})");

    /* Tableau HTML des questions */
    print "<table width='900px' style='font-size:14pt'>\n";
    print "\t<tr>\n";
    print "\t\t<th>Vocable</th>\n";
    print "\t\t<th width='300px'>Votre prononciation</th>\n";
    print "\t\t<th with='12px'></th>\n";
    print "\t\t<th>R&eacute;ponse</th>\n";
    print "\t</tr>\n";
	$fPair = false;
    for ($i=1; $line = mysql_fetch_array($result, MYSQL_ASSOC); $i++) {
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		$str_id = $line['id'];

	    print "\t<tr class=\"{$trClass}\">\n";
		
		$str_ruvoc = str_replace("^", "", $line['str_ruvoc']);
        print "\t\t<td id=\"id_ruvoc{$i}\">{$str_ruvoc}";
		if (strlen($line['str_ructx']) == 0)
			print "</td>\n";
		else {
			$str_ructx = str_replace("^", "", $line['str_ructx']);
			print " <i>({$str_ructx})</i></td>\n";
		}

        print "\t\t<td>"
        	. "<input type='text' id='id_test{$i}' size='32' maxlength='64'"
        		. " onblur='onblur_test({$i})'/></td>\n";
        print "\t\t<td><img src='../redbullet.gif' id='id_bullet{$i}'"
        		. " onclick='onclick_bullet({$i})'></td>\n";
        print "\t\t<td id=\"id_rep{$i}\" onclick=\"on_reponse('{$str_id}')\" style='visibility: hidden'>"
        	. "{$line['str_prono']}</td>\n";
        print "\t</tr>\n";
    }
    print "</table>\n";
    /* Libération du résultat */
    mysql_free_result($result);
    
    /* Déconnexion */
    disconnect_db($link);
?>
<br />
<input type='submit' value='Nouveau test'/>
</form>
<script language="javascript" type="text/javascript" src="ru_scripts.js"></script>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
