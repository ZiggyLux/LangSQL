<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_cod.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions de codage et de décodage des chaînes                                                     
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Fonction de rencodage d'une chaîne pour son affichage dans un formulaire
  en tolérant les caractères étrangers
----------------------------------------------------------------------------*/
function hed_he($str) {
	// Conversion en entité HTML, même pour les ", en laissant tel quel les '
	$str_he = htmlentities($str, ENT_COMPAT, "UTF-8");
	
	// Décodage des entités HTML, en laissant tel quels les " et les '
	$str_hed_he = html_entity_decode($str_he, ENT_NOQUOTES, "UTF-8");
	
	return $str_hed_he;
}
?>
