<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 5                                     
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
    
    /* Connexion à la base de données */
    $dbh = connect_db();
    
    /* Requête pour trouver le nombre d'entrées de la table des phrases */
    $query = "SELECT max(id) FROM ruphr";
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
		   	    $query_x = "SELECT id FROM ruphr WHERE id={$x}";
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
    /* Libère le résultat */
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
	    " FROM item, ruphr"
	    . " WHERE item.id_type = " . D_LISTE_RUPHR
	    . "   AND item.id_liste = {$IdList}"
		. "   AND (id = id_item)"
	    . "   {$where_cond}";
    $query_cnt = "SELECT COUNT(*) {$query_sub}";
    if (($result_cnt = $dbh->query($query_cnt)) === FALSE) {
        echo 'Erreur dans la requête SQL : ';
        echo $query;
        exit();
    }
	$n = $result_cnt->fetchColumn();
	
	$query = "SELECT id_item {$query_sub}";
    if (($result = $dbh->query($query)) === FALSE) {
        echo 'Erreur dans la requête SQL : ';
        echo $query;
        exit();
    }
    
    /* Construction du tableau de questions */
	$cQuestion = ($cIds < $n)? $cIds: $n;
	
    /* Tirage au sort des questions */
    $arrT = array();
    for ($i=0; $i<$cQuestion; $i++) {
    	while (TRUE) {
    		$x = mt_rand(1, $n);
			if (! in_array($x, $arrT))
    			break; /* Non encore Déjà tiré,  */
    	}
    	$arrT[$i] = $x;
    }
    $tirstr = implode(",", $arrT);

    $cResteALire = $cQuestion ;
    $i = 0;
    $arrItem = array();
	while ($cResteALire > 0) {
		if (! ($line = $result->fetch(PDO::FETCH_ASSOC))) break;
		
		reset($arrT);
		for ($fnd = FALSE; !$fnd && list(, $arrTval) = each($arrT);)
			$fnd = ($arrTval == $i);
		if ($fnd) {
			$arrItem[$i] = $line['id_item'];
			$cResteALire--;
		}
		$i++;
	}
    $selstr = implode(",", $arrItem);
    
    /* Libre le résultat */
	$result = NULL;

    /* Closing connection */
    disconnect_db($dbh);
    
    return $selstr;
}

?>

