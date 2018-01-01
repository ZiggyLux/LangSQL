<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_pag.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions de pagination                                                     
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Classe O_Milestone:
  Permet de regrouper les données pour le parcours par page d'une table
----------------------------------------------------------------------------*/
	class O_Milestone {
		var $val;		// Valeur de l'élément
		var $idx;		// Valeur d'index de l'élément
		var $id;		// Identifiant numérique dans la table
		function O_Milestone ($aVal="", $aIdx="", $aId=0) {
			$this->val = $aVal;
			$this->idx = $aIdx;
			$this->id = $aId;
		}
	}

?>