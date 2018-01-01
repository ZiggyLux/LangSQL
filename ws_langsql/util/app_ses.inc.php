<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_ses.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions de gestion des variables de sessions
  
  Emplacement......... util
*****************************************************************************/

/*----------------------------------------------------------------------------
  Fonction encapsulant l'ouverture de session PHP
----------------------------------------------------------------------------*/
function ouvertureSession() {
	session_start();
}

/*----------------------------------------------------------------------------
  Fonction associant la page de retour (communiquée par la page appelante) à
  la page actuelle (strScriptName)
----------------------------------------------------------------------------*/
function associePageRetour($strScriptName, $strRetPagVarName) {
	if (isset($_POST[$strRetPagVarName])) {
		// La page appelante a indiquée (intentionnellement) une page de retour
		// On met à jour la page de retour associée à cette page dans la session
		if (isset($_SESSION['arrRetPag'])) {
			// On suppose que c'est le tableau attendu
			// On recherche si une page de retour a déjà été enregistrée pour cette page
			$_SESSION['arrRetPag'][$strScriptName] = $_POST[$strRetPagVarName];
		} else {
			// Le tableau n'a pas encore été créé
			$key = $strScriptName;
			$val = $_POST[$strRetPagVarName];
			$_SESSION['arrRetPag'] = array($key => $val);
		}
		// Comme sécurité, on supprime $strRetPagVarName de $_POST
		// Cela évite l'insertion de la variable cachée par hidePostedVar
		unset($_POST[$strRetPagVarName]);
	}
}

/*----------------------------------------------------------------------------
  Pour les pages de pickup, cette fonction pertmet de récupérer le nom de la
  variable devant recevoir la donnée sélectionnée. On associe ce nom à la page
  actuelle (strScriptName)
----------------------------------------------------------------------------*/
function associeNomVariableRetour($strScriptName, $strNomVarRetour) {
	if (isset($_POST[$strNomVarRetour])) {
		// La page appelante a indiquée (intentionnellement) le nom de la variable
		// à affecter (pickup).
		// On met à jour le nom de la variable associée à cette page dans la session
		if (isset($_SESSION['arrNomVarRetour'])) {
			// On suppose que c'est le tableau attendu
			// On recherche si une page de retour a déjà été enregistrée pour cette page
			$_SESSION['arrNomVarRetour'][$strScriptName] = $_POST[$strNomVarRetour];
		} else {
			// Le tableau n'a pas encore été créé
			$key = $strScriptName;
			$val = $_POST[$strNomVarRetour];
			$_SESSION['arrNomVarRetour'] = array($key => $val);
		}
		// Comme sécurité, on supprime $strNomVarRetour de $_POST
		// Cela évite l'insertion de la variable cachée par hidePostedVar
		unset($_POST[$strNomVarRetour]);
	}
}

/*----------------------------------------------------------------------------
  
----------------------------------------------------------------------------*/
function transfereVariablePostEnSession($strScriptName, $strNomVarPost) 	{
	if (isset($_POST[$strNomVarPost])) {
		if (isset($_SESSION['arrNomValPost'][$strScriptName])) {
			$_SESSION['arrNomValPost'][$strScriptName][$strNomVarPost]
				= $_POST[$strNomVarPost];
		} else {
			// Le tableau n'a pas encore été créé
			$key = $strNomVarPost;
			$val = $_POST[$strNomVarPost];
			$_SESSION['arrNomValPost'][$strScriptName] = array($key => $val);
		}
		// Comme sécurité, on supprime $strNomVarRetour de $_POST
		// Cela évite l'insertion de la variable cachée par hidePostedVar
		unset($_POST[$strNomVarPost]);
	}
	return $_SESSION['arrNomValPost'][$strScriptName][$strNomVarPost];
}

?>
