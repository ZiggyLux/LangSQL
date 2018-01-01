<?PHP
/****************************************************************************
  Application......... L                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_ref_ruvoc.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Gestion des références de vocables                                                      
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

include_once("../util/app_ref.inc.php");
include_once("../util/app_sql.inc.php");

function poke_ref_lstdef ($key, $id) {
    
	/* An id has to be given */
	if ($id == 0)
	   die("Unexpected condition.");
	
	/* Check if reference already exists */
	$result = exec_query(
		"SELECT * FROM usref WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;");
	if (mysql_num_rows($result) == 0) {
		// Create the reference value
		exec_query("INSERT INTO usref SET" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\"," .
			" str_urkey=\"{$key}\"," .
			" i_urval=\"{$id}\" ;");
	} else {
		// Alter the the reference value
		exec_query("UPDATE usref SET" .
			" i_urval=\"{$id}\" WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;");
	}
}

function peek_ref_lstdef ($key) {
	/* Check if reference already exists */
	$result = exec_query(
		"SELECT * FROM usref WHERE" .
			" id_urusr=\"" . D_LSQW_REF_USR_DEF . "\" AND" .
			" str_urkey=\"{$key}\" ;");
			
	if (mysql_num_rows($result) > 0) {
		if ($usref = mysql_fetch_array($result, MYSQL_ASSOC))
			return $usref["i_urval"];
		else
			die("Unexpected condition.");
	} else {
		return 0;
	}
}

?>
