<?php
/****************************************************************************
  Application......... Debug tools
  Version.............                                                    
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. common.inc.php
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions utilitaires pour le debug
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

function print_POST() {
	foreach($_POST as $key => $value) {
		echo "\$_POST['$key'] = $value <br />\n";
	}
}

function print_SESSION() {
	if (isset($_SESSION)) {
		foreach($_SESSION as $key => $value) {
			if (is_array($_SESSION[$key]))
				print_r($_SESSION[$key]);
			else
				echo "\$_SESSION['$key'] = $value <br />\n";
		}
	}
}
?>