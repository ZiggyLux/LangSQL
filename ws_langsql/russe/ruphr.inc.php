<?PHP
/****************************************************************************
  Application......... langSql                                              
  Version............. 1.A                                                   
  Plateforme.......... Portabilité PHP 4                                     
  Source.............. ruphr.inc.php                                       
  Dernière MAJ........                                                       
  Auteur.............. Marc CESARINI                                         
  Remarque............ PHP 4                                        
  Brève description... Fonctions PHP communes pour phrases                                                       
                   
  Emplacement.........                                                       
*****************************************************************************/
function genObjAudio ($str) {
	if (strlen($str)==0) {
		return;
	}

  // return; /* Tant que problème Shockwave */

    print "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"\n";
    print "\tcodebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\"\n";
    print "\tid=\"ruphr-00000\" width=\"93\" height=\"48\">\n";
    print "\t<param name=\"movie\" value=\"audio/" . $str . "\">\n";

	print "\t<param name=\"allowScriptAccess\" value=\"sameDomain\" />";
    print "\t<param name=\"bgcolor\" value=\"#DFDFFF\">\n";
    print "\t<embed name=\"mp3_00000\" src=\"audio/" . $str . "\"\n"; 
    print "\tquality=\"high\" bgcolor=\"#DFDFFF\" swLiveConnect=\"true\"\n";
    print "\twidth=\"93\" height=\"48\"\n";
    print "\ttype=\"application/x-shockwave-flash\" allowScriptAccess=\"sameDomain\" \n";
    print "\tpluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed></object>";
	return;
}

?>
