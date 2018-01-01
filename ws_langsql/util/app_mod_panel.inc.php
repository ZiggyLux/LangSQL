<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_mod_panel.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Affichage du mode d'exécution                                                       
                                                                             
  Emplacement.........                                                       
*****************************************************************************/
include_once("../debug_tools/common.inc.php");

if (D_APPW_MOD_VERBOSE) {
	switch(D_APPW_MOD_ENV) {
		case "DEV":
		case "REF":
		case "RUN":
			echo D_APPW_MOD_ENV;
			break;
		default:
		  die("D_APPW_MOD_ENV non d&eacute;fini");
	}
	switch(D_APPW_MOD_ENV) {
		case "DEV":
			echo "<br/><hr>";
			print_POST();
			echo "<br/>";
			print_SESSION();
			break;
		case "REF":
		case "RUN":
			break;
		default:
		  die("D_APPW_MOD_ENV non d&eacute;fini");
	}
}
?>
