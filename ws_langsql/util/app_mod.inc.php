<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_mod.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Définitions générales liées au mode d'exécution                                                      
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Environnement d'exécution
----------------------------------------------------------------------------*/
define ("D_APPW_MOD_ENV", "DEV");
//define ("D_APPW_MOD_ENV", "VAL");
//define ("D_APPW_MOD_ENV", "RUN");

// Option activant l'affichage du bandeau de mode
define ("D_APPW_MOD_PANEL", TRUE);

/*----------------------------------------------------------------------------
  Niveau de message
----------------------------------------------------------------------------*/
// Option activant un niveau de compte rendu élevé (premier niveau de debug)
define ("D_APPW_MOD_VERBOSE", TRUE);
?>