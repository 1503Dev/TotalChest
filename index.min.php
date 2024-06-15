<?php /**
 * TotalChest: Lightweight PHP File Manger
 * VERSION 1.0.0-alpha
 * LICENSE
 * 
 * https://github.com/1503Dev/TotalChest
 */
 
 // User Configs
$rootpath='&root';
    // '&this': The directory of this PHP file
    // '&root': Website rootpath
    // Do not have '/' at the end. And preferably no subdirectory

 function formatBytes($bytes){if($bytes>=1073741824){$size=round($bytes/1073741824,2).' GB';}elseif($bytes>=1048576){$size=round($bytes/1048576,2).' MB';}elseif($bytes>=1024){$size=round($bytes/1024,2).' KB';}else{$size=$bytes.' B';}return $size;}function ensureTrailingSlash($str){if(substr($str,-1)!=='/'){header('Location: ?path='.$str.'/');}}function _gft(){$fts=json_decode('{
        "js":"text/javascript"
    }');}function getFileType($f){$finfo=finfo_open(FILEINFO_MIME);$ft=explode(';',finfo_file($finfo,$f))[0];finfo_close($finfo);return $ft;if($ft=='text/plain'){}}function cPTRTCS($path){$currentScriptDir=__DIR__;$docRoot=$_SERVER['DOCUMENT_ROOT'];if(substr($docRoot,-1)!=='/'){$docRoot.='/';}if(substr($path,0,1)!=='/'){$path='/'.$path;}$pathWithoutDocRoot=ltrim(str_replace($docRoot,'',$path),'/');$relativePath=$currentScriptDir.'/'.$pathWithoutDocRoot;$relativePath=rtrim($relativePath,'/');return $relativePath;}function rFO($haystack,$needle){$result=str_replace($needle,'',$haystack,$count);if($count>0){return $result;}else{return $haystack;}}function normalizePathManually($path){$path=trim($path,'/');$parts=explode('/',$path);$normalizedParts=[];foreach($parts as $part){if($part==='..'){if(count($normalizedParts)>0){array_pop($normalizedParts);}}elseif($part!=='.'&&$part!==''){$normalizedParts[]=$part;}}return implode('/',$normalizedParts);}function eSWS($str){if(strpos($str,'/')!==0){return '/'.$str;}return $str;}function getRelativePathToURL($p){return$p;$fullRequestUri=$_SERVER['REQUEST_URI'];$currentHost=$_SERVER['HTTP_HOST'];$siteUrl=($_SERVER['HTTPS']?'https://':'http://').$currentHost;if(strpos($fullRequestUri,$siteUrl)===0){$relativePath=substr($fullRequestUri,strlen($siteUrl));}else{$relativePath=$fullRequestUri;}$relativePathWithoutQuery=parse_url($relativePath,PHP_URL_PATH);return $relativePathWithoutQuery;}function gCPRTR($rootpath){if(strstr($rootpath,'&root')){return(str_replace('&root','',$rootpath));}$scriptDir=dirname($_SERVER['SCRIPT_NAME']);$pathInfo=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';$fullPath=rtrim($scriptDir,'/').($pathInfo?'/'.ltrim($pathInfo,'/'):'');return $fullPath;}$viewpath=$_GET['path'];if(!isset($viewpath)||$viewpath===''){$viewpath='/';}$viewpath=eSWS(normalizePathManually($viewpath).'/');if($viewpath=='../')header('Location: ?path=/');$path=str_replace('&this',__DIR__,str_replace("&root",$_SERVER['DOCUMENT_ROOT'],$rootpath)).$viewpath;ensureTrailingSlash($viewpath);if($viewpath!=$_GET['path'])header('Location: ?path='.$viewpath);if(!file_exists($path))die('<h2>No such file or directory.</h2><a href="?path='.$viewpath.'/../">Return to previous directory');try{$files=scandir($path);natcasesort($files);$files=array_diff($files,array('.','..'));array_unshift($files,'..');}catch(Exception $e){die("无法读取目录: ".$e->getMessage());} ?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"><title>Index of <?=$viewpath?></title><style>a{text-decoration:none}a:not([nocolor]),a:active{color:blue}a:hover,a:focus{text-decoration:underline;color:red}body{background-color:#F5F5F5}h2{margin-bottom:12px}table{margin-left:12px}th,td{font:90% monospace;text-align:left}th{font-weight:bold;padding-right:14px;padding-bottom:3px}td{padding-right:14px}td.s,th.s{text-align:right}div.list{background-color:white;border-top:1px solid #646464;border-bottom:1px solid #646464;padding-top:10px;padding-bottom:14px}div.foot,div.foot *{font:90% monospace;color:#787878;padding-top:4px}a[file-type="directory"]{color:green}</style></head><body><h2>Index of <?=$viewpath?></h2><div class="list"><table summary="Directory Listing"cellpadding="0"cellspacing="0"><thead><tr><th>名称</th><th>大小</th><th>类型</th></tr></thead><tbody>
<?
    foreach ($files as $file) {
        $filepath=$path.$file;
        echo '<tr>';
        echo '<td>';
        if(is_dir($filepath)){
            echo '<a file-type="directory" href="?path='.$viewpath.$file.'/">'.$file.'</a>/';
        } else echo '<a href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">'.$file.'</a>';
        echo '</td>';
        echo '<td>';
        if(is_dir($filepath)){
            echo '-';
        } else echo formatBytes(filesize($filepath));
        echo '</td>';
        echo '<td>';
        echo getFileType($filepath);
        echo '</td>';
        echo "</tr>\n";
    }
?>
</tbody></table></div><div class="foot"><a nocolor href="https://github.com/1503Dev/TotalChest">TotalChest</a>/1.0.0-alpha.1</div></body></html>