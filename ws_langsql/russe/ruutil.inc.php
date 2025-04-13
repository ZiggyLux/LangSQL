<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 5                                     
  Source.............. ruutil.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 5                                        
  Brève description... Fonctions PHP communes pour russe                                                       
                                                                             
  Emplacement.........                                                       
*****************************************************************************/
function remove_accent ($strRu) {
	$strRuNew = $strRu;
	
	$strWhat = '^';
	while(strcspn($strRuNew, $strWhat) < strlen($strRuNew))
		$strRuNew = str_replace($strWhat, '', $strRuNew);
	
	$strWhat = '_';
	while(strcspn($strRuNew, $strWhat) < strlen($strRuNew))
		$strRuNew = str_replace($strWhat, '', $strRuNew);

	return $strRuNew;
}

function change_accent_HTML ($strRu) {
	$strRuNew = "";

	$strAccent = '^';
	$strMobile = "_";
	$strAccMobile1 = "_^";
	$strAccMobile2 = "^_";
	$iLen = strlen($strRu);
	for($i = 0; $i < $iLen; $i++) {
		$c = $strRu[$i];
		if ($i < ($iLen - 1) 
			&& (	(($c . $strRu[$i + 1]) == $strAccMobile1) 
				||	(($c . $strRu[$i + 1]) == $strAccMobile2)
				)
			) {
			$strRuNew .= "<b><u><i>" . $strRu[$i + 2] . $strRu[$i + 3] ."</i></u></b>";
			$i += 3;
		} else if ($c == $strAccent && ($i + 1) < $iLen) {
			$strRuNew .= "<b><u>" . $strRu[$i + 1] . $strRu[$i + 2] ."</u></b>";
			$i += 2;
		} else if ($c == $strMobile && ($i + 1) < $iLen) { 
			$strRuNew .= "<i>" . $strRu[$i + 1] . $strRu[$i + 2] ."</i>";
			$i += 2;
		}	else
			$strRuNew .= $c;
	}
	
	return $strRuNew;
}
?>

