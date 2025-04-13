<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvocquery_ruvoc.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Russe: interrogation traduction FR->RU             -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruvocquery.inc.php");

	include_once("ruutil.inc.php");
	ouvertureSession();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,vocable,query,prononciation">
<link href="../styles.css" rel="stylesheet" type="text/css">
<link href="../topmenu.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en russe - Traduction du fran&ccedil;ais au russe</title>
</head>
<body>
<?php include("ru_menu.inc.php"); ?>
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
<div id = "principal">
<h2>Vocabulaire russe - Traduction du fran&ccedil;ais au russe</h2>
</div>
<form name="formulaire" id="formulaire" action="ruvocquery_ruvoc.php" method="post">
<?php
	include_once("../util/app_sql.inc.php");

   /* Connexion et sélection de la base de données */
    $dbh = connect_db();

	/* Tirage aléatoire */
	
	if (isset($_POST["id_lgrtest"])) {
		$lgrtestDef = 15; // Valeur par défaut
		$lgrtest = intval($_POST["id_lgrtest"]);
		if (($lgrtest <= 0) || ($lgrtest > 100))
			$lgrtest = $lgrtestDef;
		
		if ((isset($_POST["id_lisDef_ruvoc"]))
			&& (isset($_POST["etendue"])) && ($_POST["etendue"]=="L"))
			$selstr = random_ids_fromlist($lgrtest, intval($_POST["id_lisDef_ruvoc"]));
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
    $query =
    	"SELECT id, str_trafr, str_frctx, str_ruvoc, str_ructx "
	    	. " FROM ruvoc"
    		. " WHERE id in ({$selstr})";
    		
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
    		
    /* Tableau HTML des questions */
    print "<table width='900px' style='font-size:14pt'>\n";
    print "\t<tr>\n";
    print "\t\t<th>Vocable</th>\n";
    print "\t\t<th with='250px'>Votre traduction</th>\n";
    print "\t\t<th with='12px'></th>\n";
    print "\t\t<th>R&eacute;ponse</th>\n";
    print "\t</tr>\n";
	$fPair = false;
	for ($i=1; $line = $result->fetch(PDO::FETCH_ASSOC); $i++) {
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		$str_id = $line['id'];

	    print "\t<tr class=\"{$trClass}\">\n";
        print "\t\t<td id=\"id_ruvoc{$i}\">{$line['str_trafr']}";
        if (strlen($line['str_frctx']) == 0)
            print "</td>\n";
        else {
            $str = $line['str_frctx'];
            print " <span class=\"ctx\">{$str}</span></td>\n";
        }
        print "\t\t<td>"
        	. "<input type='text' id='id_test{$i}' class='russe' size='32' maxlength='64'"
        		. " onblur='onblur_test({$i})'/></td>\n";
        print "\t\t<td><img src='../redbullet.gif' id='id_bullet{$i}'"
        		. " onclick='onclick_bullet({$i})'></td>\n";
        print "\t\t<td id=\"id_rep{$i}\" onclick=\"on_reponse('{$str_id}')\" style='visibility: hidden'>"
        . change_accent_HTML($line['str_ruvoc']) . "</td>\n";
        print "\t</tr>\n";
    }
    print "</table>\n";
    /* Libration du résultat */
    $result = NULL;
    
    /* Déconnexion */
    disconnect_db($dbh);

?>
<br />
<input type='submit' value='Nouveau test'/>
</form>
<script language="javascript" type="text/javascript" src="ru_scripts.js"></script>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
