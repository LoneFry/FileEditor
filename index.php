<?php
/*****************************************************************************
 * Project     : FileEditor
 * Created By  : LoneFry
 * License     : CC BY-NC-SA 
 *                Creative Commons Attribution-NonCommercial-ShareAlike 
 *                http://creativecommons.org/licenses/by-nc-sa/3.0/
 * Created on  : Jan 7, 2009
 *                A basic File Editor for editing files on your server via the
 *                browser with a regular expression white list to assure the
 *                wrong files are not edited.
 * Modified on : Jan 8, 2009
 *                Added basic password auth and blacklist
 *                Added styles and rounded corners (rounded.js)
 *                Added tabbing support to the textarea
 *             : Jan 9, 2009
 *                Reorganized basic password protection and tabbing
 *             : Jan 16, 2009
 *                removed static keyword from compare function
 *                added array pre-checks to foreach loops
 *             : May 31, 2011
 *                code re-org
 *                ditch rounded.js for css based corners
 ****************************************************************************/

//Label to use within UI
$_Label="LoneFry's File Editor";

//List of paths we're allowed to edit files from
$_Paths=array(dirname(__FILE__),'/tmp/');

//Whitelist of Regular Expressions files must match one or more of
$_White=array('/\.txt$/i','/^README$/');//array('/.+/i');

//Blacklist of Regular Expressions files must match none of
$_Black=array('/\.php.?|\.cgi|\.sh$/i','/^\./');

//Username and Password for basic password protection
// set $_Pass='' to disable basic password protection
$_User='Tyler';
$_Pass='cris';

//variables to tally messages and errors in
$sMsg=$sErr='';


/******************************************************************************
 * File checks boolean $_SESSION['FileEditor'] for access
 * Basic password protection
 * set $_Pass='' to use other method, like .htaccess
 *****************************************************************************/
if(''==$_Pass){
        $_SESSION['FileEditor']=true; //will be set every time if pass is blank
}else{
        session_start(); //use a session to remember auth status
        if(@$_GET['login']=='post' && isset($_POST['username']) && isset($_POST['password'])){
                $_SESSION['FileEditor']=($_POST['username']==$_User && $_POST['password']==$_Pass);
        }
        if (isset($_GET['logout'])) {
                $_SESSION['FileEditor']=false;
        }
}

/******************************************************************************
 * End Basic password protection
 *****************************************************************************/
if(!isset($_SESSION['FileEditor']) || !$_SESSION['FileEditor']){
	include 'formLogin.php';
}else{
	if(isset($_POST['filePath']) && isset($_POST['fileName']) && isset($_POST['fileContents']) && isset($_GET['file'])){
		if($_GET['file']=='-1'){
			$result=saveFile($_POST['filePath'],$_POST['fileName'],$_POST['fileContents'],true);
		} else {
			$result=saveFile($_POST['filePath'],$_POST['fileName'],$_POST['fileContents']);
		}
	}
	if(isset($_GET['path']) && isset($_GET['file'])){
		$fileContents=$_GET['file']==-1?'':getFileContents($_GET['path'],$_GET['file']);
		include 'formFile.php';
	}else{
		include 'formList.php';
	}
}


function saveFile($pathNum,$file,$content,$create=false){
	global $sErr,$sMsg,$_Paths;
	
	if(!$_SESSION['FileEditor']){
		$sErr.='Access Denied<br>';
		return false;
	}
	if(!isset($_Paths[$pathNum])){
		$sErr.='Invalid Path Requested<br>';
		return false;
	}
	if(!testMasks($file)) {
		$sErr.='Invalid File Requested: '.$file.'<br>';
		return false;
	}
	if(!$create && !file_exists($_Paths[$pathNum].'/'.$file)) {
		$sErr.='Requested File does not exist: '.$file.'<br>';
		return false;
	}
	if (!$create && !is_writable($_Paths[$pathNum].'/'.$file)) {
		$sErr.='Requested File is not writable: '.$file.'<br>';
		return false;
	}
	if (!$handle = fopen($_Paths[$pathNum].'/'.$file, 'w')) {
		$sErr.='Cannot open file '.$file.'<br>';
		return false;
	}
	if (fwrite($handle, $content) === FALSE) {
		$sErr.='Cannot write to file '.$file.'<br>';
		return false;
	}
	fclose($handle);
	$sMsg.='File Save Successful '.$file.'<br>';
	$_GET['file']=$file;
	return true;
}
function getFileContents($pathNum,$file){
	global $sErr,$sMsg,$_Paths;
	if(!isset($_Paths[$pathNum])){
		$sErr.='Invalid Path Requested<br>';
		return false;
	}
	if(!testMasks($file)) {
		$sErr.='Invalid File Requested: '.$file.'<br>';
		return false;
	}
	if(!file_exists($_Paths[$pathNum].'/'.$file)) {
		$sErr.='Requested File does not exist: '.$file.'<br>';
		return false;
	}
	if(false===$s=file_get_contents($_Paths[$pathNum].'/'.$file)) {
		$sErr.='Failed to get contents of file: '.$file.'<br>';
		return false;
	}
	return $s;
}


function testMasks($file){
	global $sErr,$sMsg,$_Paths,$_White,$_Black;
	//don't allow slashes in filenames
	if(false!==strpos($file,'/'))return false;
	
	//check file name to match any mask
	if(is_array($_Black) && count($_Black) > 0)
	foreach($_Black as $re){
		if(preg_match($re,$file))return false;
	}

	//check file name to match any mask
	if(is_array($_White) && count($_White) > 0)
	foreach($_White as $re){
		if(preg_match($re,$file))return true;
	}
	return false;
}

function getFiles($path){
	if(!$_SESSION['FileEditor']){
		$sErr.='Access Denied<br>';
		return false;
	}
	if (!is_dir($path)) {
		$sErr.='Requested Path is not a directory: '.$path.'<br>';
		return false;
	}
	if (!($rDir = opendir($path))) {
		$sErr.='Failed to open Requested Path: '.$path.'<br>';
		return false;
	}
	$list=array();
	while (($file = readdir($rDir)) !== false) {
		if ($file == '.') continue;
		if ($file == '..') continue;
		if (filetype($path.'/'.$file) == "file") {
			$list[]=$file;
		}
	}
	if(is_array($list) && count($list) > 0) usort($list,'compare');
	return $list;
}
function compare($a, $b){
    return (strtolower($a) > strtolower($b)) ? +1 : -1;
}

?>
