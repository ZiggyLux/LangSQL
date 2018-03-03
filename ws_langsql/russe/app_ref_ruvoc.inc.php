<?PHP
/****************************************************************************
  Application......... L                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 5                                     
  Source.............. app_ref_ruvoc.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Gestion des références de vocables                                                      
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

include_once("../util/app_ref.inc.php");
include_once("../util/app_sql.inc.php");

function poke_ref_lstdef ($dbh, $key, $id) {
    
    /* An id has to be given */
	if ($id == 0)
	   die("Unexpected condition.");
	
	/* Check if reference already exists */
	$query = 
		"SELECT * FROM usref WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;";
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}	
	
	if (! ($result->fetch(PDO::FETCH_ASSOC))) {
		// Create the reference value
	    $query = "INSERT INTO usref SET" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\"," .
			" str_urkey=\"{$key}\"," .
			" i_urval=\"{$id}\" ;";
	    if (($result = $dbh->exec($query)) === FALSE) {
	        echo "Erreur DB à l'insertion : ";
	        echo $query;
	        exit();
	    }
	} else {
		// Alter the the reference value
	    $query = "UPDATE usref SET" .
			" i_urval=\"{$id}\" WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;";
	    if (($result = $dbh->exec($query)) === FALSE) {
	        echo "Erreur DB à l'insertion : ";
	        echo $query;
	        exit();
	    }
	}
}

function peek_ref_lstdef ($dbh, $key) {
	/* Check if reference already exists */
	$query = 
		"SELECT * FROM usref WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;";
	if (($result = $dbh->query($query)) === FALSE) {
	    echo 'Erreur dans la requête SQL : ';
	    echo $query;
	    exit();
	}
	echo $query;
	if ($usref = $result->fetch(PDO::FETCH_ASSOC)) {
		return $usref["i_urval"];
	} else {
		return 0;
	}
}

?>
