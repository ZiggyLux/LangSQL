<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php /*
<!----------------------------------------------------------------------------->
<!-- Application......... LangSql                                            -->
<!-- Version............. 1.0                                                -->
<!-- Plateforme.......... Portabilité                                        -->
<!--                      HTML 4.0, PHP 5, MySQL, Javascript                 -->
<!-- Source.............. ruvrbquery_ruvrb.php                               -->
<!-- Dernière MAJ........                                                    -->
<!-- Auteur..............                                                    -->
<!-- Remarque............ HTML 4.0 strict (sans deprecated, sans frames)     -->
<!-- Brève description... Russe: interrogation conjugaison                   -->
<!-- Emplacement......... \russe                                             -->
<!----------------------------------------------------------------------------->
*/
	include_once("../util/app_mod.inc.php");
	include_once("../util/app_cod.inc.php");
	include_once("../util/app_ses.inc.php");
	include_once("ruvrbquery.inc.php");

	ouvertureSession();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="Author" content="Marc Cesarini">
<meta name="keywords" content="russe,verbe,query,conjugaison">
<link href="../styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../scripts.js"></script>
<title>Verbes russes - Conjugaison</title>
</head>
<body>
<?php include("menu_russe.inc.php"); ?>

<script language="javascript" type="text/javascript">
<!--

/*                               Variables globales                           */
/*----------------------------------------------------------------------------*/

const SCRIPT_NAME = <?php echo "\"" . $_SERVER['SCRIPT_NAME'] . "\""; ?>;

/*----------------------------------------------------------------------------*/
/* Affichage de la conjugaison du verbe                                       */
/*     paramètre: identification du verbe                                     */
/*----------------------------------------------------------------------------*/
function on_reponse(id_vrb) {
    document.formulaire.action = "ruvrbedit.php";
	addReturnPageToForm("formulaire", SCRIPT_NAME);
	addNomValVariableToForm("formulaire", "ruvrbedit_mod", "visu");
	addNomValVariableToForm("formulaire", "ruvrbedit_sel", id_vrb);
	
    document.formulaire.submit();
}

//-->
</script>
<!----------------------------------------------------------------------------->
<!-- DESCRIPTION DU FORMULAIRE                                               -->
<!--     Chargement des éléments constituant l'exercice                      --> 
<!----------------------------------------------------------------------------->
<h2>Verbes russes - Conjugaison</h1>
<form name="formulaire" id="formulaire" action="ruvrbquery_ruvrb.php" method="POST">
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
		
		if ((isset($_POST["id_lisDef_ruvrb"]))
			&& (isset($_POST["etendue"])) && ($_POST["etendue"]=="L"))
			$selstr = random_ids_fromlist($lgrtest, intval($_POST["id_lisDef_ruvrb"]));
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
    	"SELECT id, str_indic,"
			. " str_ruvip_inf, str_ruvpd_inf, str_ruvpi_inf,"
			. " str_ruvip_pre_1s, str_ruvip_pre_2s, str_ruvip_pre_3p,"
			. " str_ruvip_pas_ms, str_ruvip_pas_fs, str_ruvip_pas_ns, str_ruvip_pas_pl,"
			. " str_ruvpd_pre_1s, str_ruvpd_pre_2s, str_ruvpd_pre_3p,"
			. " str_ruvpd_pas_ms, str_ruvpd_pas_fs, str_ruvpd_pas_ns, str_ruvpd_pas_pl,"
			. " str_ruvpi_pre_1s, str_ruvpi_pre_2s, str_ruvpi_pre_3p,"
			. " str_ruvpi_pas_ms, str_ruvpi_pas_fs, str_ruvpi_pas_ns, str_ruvpi_pas_pl"
	    	. " FROM ruvrb"
    		. " WHERE id in ({$selstr})";
    		
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
		
    /* Tableau HTML des questions */
    print "<table width='1000px' style='font-size:14pt'>\n";
    print "\t<tr>\n";
    print "\t\t<th colspan=\"2\">Sens du verbe<br>Aspect</th>\n";
	print "\t\t<th colspan=\"2\">Temps et personne</th>\n";
    print "\t</tr><tr>\n";
    print "\t\t<th width='275px'>Votre infinitif</th>\n";
    print "\t\t<th width='150px'>R&eacute;ponse</th>\n";
    print "\t\t<th width='275px'>Votre conjugaison</th>\n";
    print "\t\t<th width='150px'>R&eacute;ponse</th>\n";
    print "\t</tr>\n";
	$fPair = false;
	for ($i=1; $line = $result->fetch(PDO::FETCH_ASSOC); $i++) {
		$fPair = !$fPair;
		$trClass = ($fPair)? "pair" : "impair";

		// Choix de l'aspect du verbe
		$f_imperf_d = (strlen(trim($line['str_ruvip_inf'])) > 0);
		$f_imperf_i = (strlen(trim($line['str_ruvpi_inf'])) > 0);
		$f_perf = (strlen(trim($line['str_ruvpd_inf'])) > 0);
		if (!$f_imperf_d && !$f_imperf_i && !$f_perf)
			// Ce cas ne devrait cependant pas se présenter
			continue;
		
		$fnd=FALSE; $x=0; $str_aspect="";
		while (!$fnd) {
			$x = mt_rand(1, 3);
			
			/* On n'accepte le tirage que si le verbe est indiqué */
			switch($x) {
			case 1:
				$fnd = $f_imperf_d;
				if($fnd) {
					$str_aspect = " l'imperfectif"; 
					$str_rep = $line['str_ruvip_inf'];
				}
				break;
			case 3:
				$fnd = $f_imperf_i;
				if ($fnd) {
					$str_aspect = " l'ind&eacute;termin&eacute;"; 
					$str_rep = $line['str_ruvpi_inf'];
				}
				break;
			case 2:
				$fnd = $f_perf;
				if ($fnd) {
					$str_aspect="au perfectif";
					$str_rep = $line['str_ruvpd_inf'];
				}
				break;
			}
		}

		// Choix du temps et de la personne
		$c=0; $str_temps=""; $str_pers=""; $str_rep2="";
		while (strlen(trim($str_rep2)) == 0) {
			$c = mt_rand(1, 7);
			
			// On n'accepte le tirage que si la conjugaison est indiqué
			switch($c) {
			case 1:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pre_1s']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pre_1s']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pre_1s']; break;
				}
				$str_temps = "pr&eacute;sent";
				$str_pers = "1 sing.";
				break;
			case 2:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pre_2s']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pre_2s']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pre_2s']; break;
				}
				$str_temps = "pr&eacute;sent";
				$str_pers = "3 sing.";
				break;
			case 3:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pre_3p']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pre_3p']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pre_3p']; break;
				}
				$str_temps = "pr&eacute;sent";
				$str_pers = "3 plur.";
				break;
			case 4:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pas_ms']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pas_ms']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pas_ms']; break;
				}
				$str_temps = "pass&eacute;";
				$str_pers = "masc.sing.";
				break;
			case 5:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pas_fs']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pas_fs']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pas_fs']; break;
				}
				$str_temps = "pass&eacute;";
				$str_pers = "fm.sing.";
				break;
			case 6:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pas_ns']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pas_ns']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pas_ns']; break;
				}
				$str_temps = "pass&eacute;";
				$str_pers = "neutre sing.";
				break;
			case 7:
				switch($x) {
			    	case 1: $str_rep2 = $line['str_ruvip_pas_pl']; break;
			    	case 2: $str_rep2 = $line['str_ruvpd_pas_pl']; break;
			    	case 3: $str_rep2 = $line['str_ruvpi_pas_pl']; break;
				}
				$str_temps = "pass&eacute;";
				$str_pers = "pluriel";
				break;
			}
		}

		$str_id = $line['id'];

		print "\t<tr  class=\"{$trClass}\">\n";
		print "\t\t<td id=\"id_ruvrb{$i}\" colspan=\"2\">{$line['str_indic']}<br><b>{$str_aspect}</b></td>\n";
		print "\t\t<td colspan=\"2\">{$str_temps}, {$str_pers}</td>\n";
		print "\t</tr>\n";
		
		print "\t<tr  class=\"{$trClass}\">\n";
		print "\t\t<td style='border-top: hidden'>"
			. "<input type='text' id='id_test{$i}' class='russe_sm' size='32' maxlength='64'"
			. " onblur='onblur_test({$i})'/>"
			. "&nbsp;<img src='../redbullet.gif' id='id_bullet{$i}'"
			. " onclick='onclick_bullet({$i})'></td>\n";
		print "\t\t<td id=\"id_rep{$i}\" onclick=\"on_reponse('{$str_id}')\" style='visibility: hidden'>"
			. "{$str_rep}</td>\n";
		print "\t\t<td style='border-top: hidden'>"
			. "<input type='text' id='id_test2{$i}' class='russe_sm' size='32' maxlength='64'"
			. " onblur='onblur_test2({$i})'/>"
			. "&nbsp;<img src='../redbullet.gif' id='id_bullet2{$i}'"
			. " onclick='onclick_bullet2({$i})'></td>\n";
		print "\t\t<td id=\"id_rep2{$i}\" onclick=\"on_reponse('{$str_id}')\" style='visibility: hidden'>"
			. "{$str_rep2}</td>\n";
		print "\t</tr>\n";
    }
    print "</table>\n";
    /* Libration du résultat */
    $result = NULL;
    
    /* Dconnexion */
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
