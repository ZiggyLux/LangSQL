//----------DHTML Menu Created using AllWebMenus PRO ver 5.1-#738---------------
//C:\DevWin32\ws-langsql\langsql_web\A3-D\awm\menu_russe.awm
var awmMenuName='menu_russe';
var awmLibraryBuild=738;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/menu_russe';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmAltUrl!='' && !awmSupported) window.location.replace(awmAltUrl);
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Safari')>-1)?7:(nua.indexOf('Gecko')>-1)?2:((document.layers)?3:((nua.indexOf('Opera')>-1)?4:((nua.indexOf('Mac')>-1)?5:1)));
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
var mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
var awmMenuPath=mpi.substring(0,mpi.length-1);
while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+awmMenuPath+awmLibraryPath+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
var n=null;
awmzindex=1000;
}

var awmImageName='';
var awmPosID='';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","3"];
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["v5_bullets_05.gif",10,10];
awmCreateCSS(1,2,1,'#FFFFFF','#778375',n,'8pt Arial',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF','#778375',n,'8pt Arial',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#FFFFFF',n,n,n,'outset','1','#DFDFDF',0,0);
awmCreateCSS(1,2,1,'#000000','#E7FCE4',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',1);
awmCreateCSS(0,2,1,'#008040','#FFFFFF',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',1);
awmCreateCSS(1,2,1,'#000000','#E7FCE4',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',0);
awmCreateCSS(0,2,1,'#008040','#FFFFFF',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',0);
awmCreateCSS(0,1,0,n,'#FFFFFF',n,n,n,'outset','1','#C0C0C0',0,0);
awmCreateCSS(1,2,0,'#000000','#E7FCE4',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',1);
awmCreateCSS(0,2,0,'#008040','#FFFFFF',n,'8pt Arial',n,'none','0','#000000','3px 3px 3px 3',1);
var s0=awmCreateMenu(0,0,0,0,1,0,0,0,0,5,5,0,0,2,0,1,0,n,n,100,0,0,5,5,0,-1,1,200,200,0,0,0,"0,0");
it=s0.addItem(0,1,1,"Langue russe",n,n,"","",n,n,n,n,n,0,0,0,0,0);
it=s0.addItem(3,4,4," &nbsp;Vocables &nbsp;",n,n,"","russe/ruvocbrowse.php",n,n,n,"russe/ruvocbrowse.php",n,0,0,2,0,0);
it=s0.addItem(3,4,4," &nbsp;Verbes &nbsp;",n,n,"","russe/ruvrbbrowse.php",n,n,n,"russe/ruvrbbrowse.php",n,0,0,2,0,0);
it=s0.addItem(3,4,4," &nbsp;Phrases &nbsp;",n,n,"","russe/ruphrbrowse.php",n,n,n,"russe/ruphrbrowse.php",n,0,0,2,1,0);
it=s0.addItemWithImages(5,6,6," &nbsp;Tests &nbsp;",n,n,"",0,0,0,1,1,1,n,n,n,"",n,n,n,n,n,0,0,2,n,n,n,n,n,n,0,0,0,1,0,n,n,n);
var s1=it.addSubmenu(0,0,0,0,0,0,0,7,0,1,0,n,n,100,0,1,0,-1,1,200,200,0,0);
it=s1.addItem(8,9,9," &nbsp;Traduire du français &nbsp;",n,n,"","russe/ruvocquery_ruvoc.php",n,n,n,"russe/ruvocquery_ruvoc.php",n,0,0,2,0,0);
it=s1.addItem(8,9,9," &nbsp;Traduire du russe &nbsp;",n,n,"","russe/ruvocquery_trafr.php",n,n,n,"russe/ruvocquery_trafr.php",n,0,0,2,0,0);
it=s1.addItem(8,9,9," &nbsp;Phonétique du russe &nbsp;",n,n,"","russe/ruvocquery_prono.php",n,n,n,"russe/ruvocquery_prono.php",n,0,0,2,0,0);
it=s1.addItem(8,9,9," &nbsp;Traduire et conjuguer des verbes &nbsp;",n,n,"","russe/ruvrbquery_ruvrb.php",n,n,n,"russe/ruvrbquery_ruvrb.php",n,0,0,2,0,0);
it=s1.addItem(8,9,9," &nbsp;Traduire des phases du français &nbsp;",n,n,"","russe/ruphrquery_ruphr.php",n,n,n,"russe/ruphrquery_ruphr.php",n,0,0,2,0,0);
it=s0.addItem(3,4,4," &nbsp;Réglages &nbsp;",n,n,"","russe/ru_reglages.php",n,n,n,"russe/ru_reglages.php",n,0,0,2,1,0);
it=s0.addItemWithImages(5,6,6," &nbsp;Langues &nbsp;",n,n,"",0,0,0,1,1,1,n,n,n,"",n,n,n,n,n,0,0,2,n,n,n,n,n,n,0,0,0,0,0,n,n,n);
var s1=it.addSubmenu(0,0,0,0,0,0,0,7,0,1,0,n,n,100,0,2,0,-1,1,200,200,0,0);
it=s1.addItem(8,9,9," &nbsp;Espagnol &nbsp;",n,n,"","espagnol/esvocbrowse.php",n,n,n,"espagnol/esvocbrowse.php",n,0,0,2,0,0);
it=s1.addItem(8,9,9," &nbsp;Allemand &nbsp;",n,n,"","espagnol/esvocbrowse.php",n,n,n,"espagnol/esvocbrowse.php",n,0,0,2,0,0);
s0.pm.buildMenu();
}}
