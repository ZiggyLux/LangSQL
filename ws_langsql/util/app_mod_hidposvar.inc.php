<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_mod_hidposvar.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Récupère et place en masqué les variables $_POST                                                       
                       Pour gestion du pick-up      
  Emplacement.........                                                       
*****************************************************************************/

/*
reset($_POST);
while(list($k, $v) = each($_POST)) {
  // Nécessité de ne pas masquer les variables locales des pickup
  if (strcmp($k,"pick_pos_val") && strcmp($k,"pick_pos_id"))
	  echo "<input type=\"hidden\" name=\"" . $k . "\" id=\"" . $k . "\" " .
    	"value=\"" . htmlentities($v, ENT_COMPAT, "UTF-8") . "\"/>\n" ;
}
*/

function hidePostedVarWithExclusion($strExcludingPrefix="") { // Obsolete
	reset($_POST);
	while(list($k, $v) = each($_POST)) {
	  
		if (!(strcmp($k,"pick_pos_val") && strcmp($k,"pick_pos_id")))
			// Nécessité de ne pas masquer les variables locales des pickup
		  	continue;

		$nExcludingPrefixLen = strlen($strExcludingPrefix);
		if (($nExcludingPrefixLen > 0)
			&& !(strncmp($k, $strExcludingPrefix, $nExcludingPrefixLen)))
			// Cas probable d'une variable de la page en cours de réentrance.
			// On ne génère pas la variable qui le sera par la page elle-même.
			continue;
			
	  	// Génère la variable cachée avec la valeur postée
		echo "<input type=\"hidden\" name=\"" . $k . "\" id=\"" . $k . "\" " .
			"value=\"" . htmlentities($v, ENT_COMPAT, "UTF-8") . "\"/>\n" ;
	}
}

function hidePostedVar() {
	reset($_POST);
	while(list($k, $v) = each($_POST)) {
	  
		if (!(strcmp($k,"pick_pos_val") && strcmp($k,"pick_pos_id")))
			// Nécessité de ne pas masquer les variables locales des pickup
		  	continue;

		if (!(strcmp($k,"retpag")))
			// Les pages de retour ne doivent pas persister
		  	continue;

	  	// Génère la variable cachée avec la valeur postée
		echo "<input type=\"hidden\" name=\"" . $k . "\" id=\"" . $k . "\" " .
			"value=\"" . htmlentities($v, ENT_COMPAT, "UTF-8") . "\"/>\n" ;
	}
}

?>
