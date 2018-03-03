<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. ruvrbquery.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Fonctions PHP communes pour interrogations                                                       
                                                                             
  Emplacement.........                                                       
*****************************************************************************/
include_once("../liste/liste.inc.php");

function random_ids ($cIds) {
    
    /* Connexion à la base de données */
    $dbh = connect_db();
    
    /* Requête pour trouver le nombre d'entrées du dictionnaire des verbes */
    $query = "SELECT max(id) FROM ruvrb";
    if (($result = $dbh->query($query)) === FALSE) {
        echo 'Erreur dans la requête SQL : ';
        echo $query;
        exit();
    }
    
    /* Construction du tableau de questions */
    if ($line = $result->fetch(PDO::FETCH_ASSOC)) {
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
		   	    $query_x = "SELECT id FROM ruvrb WHERE id={$x}";
		   	    if (($result_x = $dbh->query($query_x)) === FALSE) {
		   	        echo 'Erreur dans la requête SQL : ';
		   	        echo $query_x;
		   	        exit();
		   	    }
		   	    
		   	    if (!$result_x->fetch(PDO::FETCH_ASSOC)) {
		   	        $result_x = NULL;
		   	        continue; /* Inexistent dans BD, on recommence ce tirage */
				}
				break;
	    	}
 		    $arr[$i] = $x;
		}
	    $selstr = implode(",", $arr);
	}
    /* Libre le résultat */
	$result = NULL;
	
	/* Closing connection */
	disconnect_db($dbh);
	
	return $selstr;
}

function random_ids_fromlist ($cIds, $IdList) {
    /* Connexion à la base de données */
    $dbh = connect_db();
    
    /* Requête pour trouver le nombre d'entrées de la liste */
	$where_cond = "";
    $query_sub =
		" FROM item"
		. " WHERE item.id_type = " . D_LISTE_RUVRB
		. "   AND item.id_liste = {$IdList}"
		. "   {$where_cond}";
	$query = "SELECT id_item {$query_sub}";
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
		
	$query_cnt = "SELECT COUNT(*) {$query_sub}";
	if (($result_cnt = $dbh->query($query_cnt)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
	$n = $result_cnt->fetchColumn();

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
	    		if (!($line
	    		    = $result->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, ($x - 1))))
	    		    continue; // Si échec (improbable), on recommence ce tirage
				
				$idItem = $line["id_item"];
				
				/* Vérification	existence dans BD */
		   	    $query_x = "SELECT id FROM ruvrb WHERE id={$idItem}";
		   	    if (($result_x = $dbh->query($query_x)) === FALSE) {
		   	        echo 'Erreur dans la requête SQL : ';
		   	        echo $query_x;
		   	        exit();
		   	    }
		   	    if (!$result_x->fetch(PDO::FETCH_ASSOC)) {
		   	        $result_x = NULL;
		   	        continue; /* Inexistent dans BD, on recommence ce tirage */
				}
				break;
	    	}
 		    $arr[$i] = $idItem;
		}
	    $selstr = implode(",", $arr);
	}
    /* Libre le résultat */
	$result = NULL;
	
	/* Closing connection */
	disconnect_db($dbh);
	
	return $selstr;
}

?>


