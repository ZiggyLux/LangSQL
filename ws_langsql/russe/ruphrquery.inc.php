<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. ruphrquery.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions PHP communes pour interrogations                                                       
                       Table des phrases                                     
  Emplacement.........                                                       
*****************************************************************************/
include_once("../liste/liste.inc.php");

function random_ids ($cIds) {
	
    /* Requête pour trouver le nombre d'entrées de la table des phrases */
    $result = exec_query("SELECT max(id) FROM ruphr");
    
    /* Construction du tableau de questions */
    if ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	$n = (int) $line['max(id)'];
		$selstr="";
	    for ($i=0; $i<$cIds; $i++) {
	    	while (TRUE) {
	    		$x = mt_rand(1, $n);
				if ($i>1) {
					reset($arr);					
	    			for ($fnd = FALSE; !$fnd && list(, $arrv) = each($arr);)
	    				$fnd = ($arrv == $x);
	    			if ($fnd)
	    				continue; /* Déjà tiré, on recommence ce tirage */
	    		}	
	    		
				/* Vérification	existence dans BD */
		   	    $result_x = exec_query("SELECT id FROM ruphr WHERE id={$x}");
				if (!mysql_fetch_array($result_x, MYSQL_ASSOC)) {
				    mysql_free_result($result_x);
					continue; /* Inexistent dans BD, on recommence ce tirage */
				}
				break;
	    	}
 		    $arr[$i] = $x;
		}
	    $selstr = implode(",", $arr);
	}
    /* Libère le résultat */
    mysql_free_result($result);

	return $selstr;
}

function random_ids_fromlist ($cIds, $IdList) {
    /* Requête pour trouver le nombre d'entrées de la liste */
	$where_cond = "";
    $result = exec_query(
		"SELECT id_item"
		. " FROM item"
		. " WHERE item.id_type = " . D_LISTE_RUPHR
		. "   AND item.id_liste = {$IdList}"
		. "   {$where_cond}");
	$n = mysql_num_rows($result);
	
    /* Construction du tableau de questions */
	$cQuestion = ($cIds < $n)? $cIds: $n;
    if ($cQuestion > 0) {
		$selstr="";
	    for ($i=0; $i<$cQuestion; $i++) {
	    	while (TRUE) {
	    		$x = mt_rand(1, $n);
				if ($i>1) {
					reset($arr);					
	    			for ($fnd = FALSE; !$fnd && list(, $arrv) = each($arr);)
	    				$fnd = ($arrv == $x);
	    			if ($fnd)
	    				continue; /* Déjà tiré, on recommence ce tirage */
	    		}	
	    		
				/* Récupération de l'id de l'item dans le résultat */
				if (!mysql_data_seek($result, ($x - 1)))
					continue; // Si échec (improbable), on recommence ce tirage
				
				if (!($line = mysql_fetch_array($result, MYSQL_ASSOC)))
					continue; // Echec (improbable), on recommence ce tirage
				
				$idItem = $line["id_item"];
				
				/* Vérification	existence dans BD */
		   	    $result_x = exec_query("SELECT id FROM ruphr WHERE id={$idItem}");
				if (!mysql_fetch_array($result_x, MYSQL_ASSOC)) {
				    mysql_free_result($result_x);
					continue; /* Inexistent dans BD, on recommence ce tirage */
				}
				break;
	    	}
 		    $arr[$i] = $idItem;
		}
	    $selstr = implode(",", $arr);
	}
    /* Libre le résultat */
    mysql_free_result($result);

	return $selstr;
}

?>

