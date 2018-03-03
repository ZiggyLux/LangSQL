<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruphrquery_ruphr.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Russe: interrogation phrases FR->RU                -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruphrquery.inc.php");

	include_once("ruphr.inc.php");

	ouvertureSession();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,phrase,query,traduction">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Phrases en russe - Traduction du fran&ccedil;ais au russe</title>
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
function on_reponse(id_phr) {
    document.formulaire.action = "ruphredit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruphredit_mod", "visu");
	addNomValVariableToForm("formulaire", "ruphredit_sel", id_phr);
	
    document.formulaire.submit();
}
//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des éléments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Phrases russes - Traduction du fran&ccedil;ais au russe</h1>
<form name="formulaire" id="formulaire" action="ruphrquery_ruphr.php" method="POST">
<?php
	include_once("../util/app_sql.inc.php");

   /* Connexion et selection de la base de données */
    $dbh = connect_db();

	/* Tirage aléatoire */
	
	if (isset($_POST["id_lgrtest"])) {
		$lgrtestDef = 8; // default value
		$lgrtest = intval($_POST["id_lgrtest"]);
		if (($lgrtest <= 0) || ($lgrtest > 100))
			$lgrtest = $lgrtestDef;
		
		if ((isset($_POST["id_lisDef_ruphr"]))
			&& (isset($_POST["etendue"])) && ($_POST["etendue"]=="L"))
			$selstr = random_ids_fromlist($lgrtest, intval($_POST["id_lisDef_ruphr"]));
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
    	"SELECT id, str_frphr, str_indic, str_audio, str_ruphr, str_rupho "
	    	. " FROM ruphr"
    		. " WHERE id in ({$selstr})";
    		
    if (($result = $dbh->query($query)) === FALSE) {
        echo 'Erreur dans la requête SQL : ';
        echo $query;
        exit();
    }
    
    /* Tableau HTML des questions */
    print "<table width='900px' style='font-size:14pt'>\n";
    print "<col span='2'>\n";
    print "<col >\n";
    print "<col span='2'>\n";
    print "<col span='2'>\n";
    print "<col >\n";
    print "\t<tr>\n";
    print "\t\t<th width='300px'>Phrase</th>\n";
    print "\t\t<th rowspan='2'>Audio</th>\n";
    print "\t\t<th width='350px'>Votre traduction</th>\n";
    print "\t\t<th rowspan='2'>Phon&eacute;tique</th>\n";
    print "\t</tr><tr>\n";
    print "\t\t<th width='300px'>Indication</th>\n";
    print "\t\t<th>R&eacute;ponse</th>\n";
    print "\t</tr>\n";
	$fPair = false;
	for ($i=1; $line = $result->fetch(PDO::FETCH_ASSOC); $i++) {
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";
	    print "\t<tr class=\"{$trClass}\">\n";
        print "\t\t<td id=\"id_ruphr{$i}\">{$line['str_frphr']}</td>";
        print "\t\t<td rowspan='2'>";
        genObjAudio ($line["str_audio"]);
        print "</td>\n";
        
        print "\t\t<td>"
          . "<input type='text' id='id_test{$i}' class='russe' size='32' maxlength='255'"
          . " onblur='onblur_test({$i})'/>"
          . "&nbsp;<img src='../redbullet.gif' id='id_bullet{$i}'"
          . " onclick='onclick_bullet12({$i})'></td>\n";
        print "\t\t<td rowspan='2' id=\"id_rep2_{$i}\" style='visibility: hidden'>";
        if (strlen($line['str_rupho']) == 0)
          print "</td>\n";
        else
          print "<i>({$line['str_rupho']})</i></td>\n";
        print "\t\t</tr>";
        print "\t<tr class=\"{$trClass}\" >";
		print "\t\t<td style='border-top: hidden'>";
        if (strlen($line['str_indic']) == 0)
          print "</td>\n";
        else
          print "<i>({$line['str_indic']})</i></td>\n";
        print "\t\t<td id=\"id_rep_{$i}\" style='visibility: hidden; border-top: hidden'>"
          . "{$line['str_ruphr']}</td>";
        print "\t</tr>\n";
    }
    print "</table>\n";
    
    /* Libération du résultat */
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
