<?PHP
/****************************************************************************
  Application......... LangSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. app_sql.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonction MySQL communes                                                      
                                                                             
  Emplacement.........                                                       
*****************************************************************************/

/*----------------------------------------------------------------------------
  Connection et Déconnection de la base de données
----------------------------------------------------------------------------*/

/*  STANDARD FUNCTIONS FOR THIS APP                                           */
function connect_db () {
    /* Connecting, selecting database */
	if (D_APPW_MOD_ENV == "RUN") {
		$databaseName = "langsql_db";
		$userName = "langsql_usr";
		$userPass = "langsql";
	} else if (D_APPW_MOD_ENV == "VAL"){
 		$databaseName = "val_langsql";
		$userName = "val_langsql_usr";
		$userPass = "langsql_val";
	} else {
 		$databaseName = "langsql_dbdev";
		$userName = "langsql_usrdev";
		$userPass = "langsql_dev";
	}
    $link = mysql_connect("", $userName, $userPass)
        or die("Could not connect");
    mysql_select_db($databaseName) or die("Could not select database");
	return $link;
}

function disconnect_db ($link) {
    /* Closing connection */
    mysql_close($link);
}

/*----------------------------------------------------------------------------
  Exécution des queries
----------------------------------------------------------------------------*/

function exec_query ($qry) {
	$result = mysql_query($qry) or die("Query failed");

	return $result;
}

function exec_verbose_query ($qry) {
	
	print("<br>\nRequ&ecirc;te SQL: " . $qry . "\n\n");
	
	return exec_query ($qry);
}

?>
