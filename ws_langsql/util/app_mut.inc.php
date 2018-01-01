<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_mut.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Fonctions de pagination                                                     
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Classes O_Mutable
  Permet de gérer de manière générique des variables mutable avec valeur initiale
----------------------------------------------------------------------------*/
class O_Mutable {
	var $valInit;	// Valeur initiale
	var $val;		// Valeur actuelle
	function O_Mutable ($aValInit, $aVal) {
		$this->valInit = $aValInit;
		$this->val = $aVal;
	}
	function set($aVal) { $this->val = $aVal; }
	function commit() { $this->valInit = $this->val; }
	function get() { return $this->val;	}
	function isEltDirty() { return (($this->val) != ($this->valInit));	}
	function isEltClean() { ~$isEltDirty(); }
}

class O_MutableAssocArray {
	var $tab;		// tableau
	function O_MutableAssocArray () {
		$this->tab = array();
	}
	function push($aCle, $aMutObj) {
		$this->tab[$aCle] = $aMutObj;
	}
	function set($aCle, $aVal) {
		($this->tab[$aCle]).set($aVal);
	}
	function commit($aCle) {
		$mut = ($this->tab[$aCle]);
		$mut->commit();
	}
	function get($aCle) {
		$mut = ($this->tab[$aCle]);
		return $mut->get();
	}
	function isEltDirty($aCle) {
		$mut = ($this->tab[$aCle]);
		return $mut->isEltDirty();
	}
	function isDirty() {
		$fDirty=false;
		reset($this->tab);
		while( list($cle, $valeur) = each($this->tab)) {
			$mut = $valeur;
			$fDirty = $mut->isEltDirty();
			if ($fDirty) break;
		}
		return $fDirty;
	}
	function isClean() { ~$isDirty(); }
}
?>