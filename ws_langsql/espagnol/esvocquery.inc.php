<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 5                                     
  Source.............. esvocquery.inc.php                                       
  Dernire MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Fonctions PHP communes pour interrogations                                                       
                                                                             
  Emplacement.........                                                       
*****************************************************************************/
function random_ids ($cIds) {
	
    /* Connexion à la base de données */
    $dbh = connect_db();
    
    /* Requête pour trouver le nombre d'entrées du dictionnaire */
    $query = "SELECT max(id) FROM esvoc";
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
	    		$query_x = "SELECT id FROM esvoc WHERE id={$x}";
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

?>

