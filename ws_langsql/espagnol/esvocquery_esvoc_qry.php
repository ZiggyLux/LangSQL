<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. esvocquery_esvoc.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Espagnol: interrogation traduction FR->ES          -->
<!-- Emplacement......... \espagnol                                          -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("esvocquery.inc.php");
	include_once("../util/app_ses.inc.php");

	ouvertureSession();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="espagnol,vocable,query">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Vocables en espagnol - Traduction du fran&ccedil;ais &agrave; l'espagnol</title>
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
/* Affichage de la fiche du vocable                                           */
/*     paramètre: identification du vocable                                   */
/*----------------------------------------------------------------------------*/
function on_reponse(id_voc) {
    document.formulaire.action = "esvocedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "esvocedit_mod", "visu");
	addNomValVariableToForm("formulaire", "esvocedit_sel", id_voc);

    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des lments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Vocabulaire russe - Traduction du fran&ccedil;ais &agrave; l'espagnol</h1>
<form name="formulaire" id="formulaire" action="esvocquery_esvoc.php" method="POST">
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
    $query = "SELECT id, str_trafr, str_frctx, str_esvoc "
	    	. " FROM esvoc"
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
        print "\t\t<td id=\"id_esvoc{$i}\">{$line['str_trafr']}";
          if (strlen($line['str_frctx']) == 0)
            print "</td>\n";
          else
            print " <i>({$line['str_frctx']})</i></td>\n";
        print "\t\t<td>"
        	. "<input type='text' id='id_test{$i}' size='32' maxlength='64'"
        		. " onblur='onblur_test({$i})'/></td>\n";
        print "\t\t<td><img src='../redbullet.gif' id='id_bullet{$i}'"
        		. " onclick='onclick_bullet({$i})'></td>\n";
        print "\t\t<td id=\"id_rep{$i}\" onclick=\"on_reponse('{$str_id}')\"" 
			. "style='visibility: hidden'>{$line['str_esvoc']}</td>\n";
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
<script language="javascript" type="text/javascript" src="es_scripts.js"></script>
<hr >
<?php include_once("../util/app_mod_panel.inc.php"); ?>
</body>
</html>
