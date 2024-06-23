<?php
/**
 * TotalChest: Lightweight PHP File Manger
 * VERSION 1.0.0-alpha.4
 * LICENSE
 * 
 * https://github.com/1503Dev/TotalChest
 */

// User Configs
$rootpath='<this>';
    // '<this>': The directory of this PHP file
    // '<root>': Website rootpath
    // Do not have '/' at the end. And preferably no subdirectory
$actions=[
    'view'=>true, // 访客是否允许查看
    'action'=>true // 普通用户是否允许操作
];

if($actions['view']==false){
    die('<h2>Insufficient permission</h2>');
}
function formatBytes($bytes) {
    // 判断大小并转换为最合适的单位
    if ($bytes >= 1073741824) {
        $size = round($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $size = round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $size = round($bytes / 1024, 2) . ' KB';
    } else {
        $size = $bytes . ' B';
    }
    return $size;
}
function ensureTrailingSlash($str) {
    if (substr($str, -1) !== '/') {
        header('Location: ?path='.$str.'/');
    }
}
function _gft($ft){
    $types=[
        "directory"=>"文件夹",
        "text/html"=>"HTML",
        "text/xml"=>"XML",
        "text/x-php"=>"PHP",
        "text/x-c++"=>"代码",
        "text/plain"=>"文本文档",
        "application/octet-stream"=>"Java字节码",
        "application/json"=>"JSON",
        "application/x-sharedlib"=>"共享库",
        "application/zip"=>"zip压缩包",
        "application/x-bzip"=>"bz压缩包",
        "application/x-bzip2"=>"bz2压缩包",
        "application/gzip"=>"gz压缩包",
        "application/x-tar"=>"tar压缩包",
        "application/x-7z-compressed"=>"7z压缩包",
        "image/jpeg"=>"图片",
        "image/gif"=>"图片",
        "image/png"=>"图片",
        "video/mp4"=>"视频",
        'audio/midi'=>'MIDI',
        'audio/mpeg'=>'MP3音乐',
        'script/javascript'=>'JavaScript',
        'script/php'=>'PHP'
    ];
    if(isset($types[$ft])){
        return $types[$ft];
    } return $ft;
}
function getFileType($f){
    $finfo = finfo_open(FILEINFO_MIME);
    $ft=explode(';',finfo_file($finfo, $f))[0];
    finfo_close($finfo);
    $f=strtolower($f);
    if($ft=='text/plain'){
        switch(explode('.',$f)[count(explode('.',$f))-1]){
            case 'js':
                return 'script/javascript';
            case 'php':
                return 'script/php';
        }
    }
    return $ft;
}
function getFileIcon($ft){
    if($ft=="directory") return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAG1BMVEUAAACZmQAAAADx8fH//8z//5nMzGb/zJn///++6lkdAAAAAXRSTlMAQObYZgAAAAFiS0dECIbelXoAAAAHdElNRQfiBhgXARMJeV+TAAAAT0lEQVQI12NggANBQSEwzWjiGqgAYoilpaUJCgoyMIi4gECYAoNIKAiUQRjh4TCRUohIOFykFCICFAgHMUqBoBzIABkIBAoMTEpgoMCAAQDGRBkarw77pAAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOC0wNi0yNFQyMzowMToxOS0wNDowMO2EXMYAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTgtMDYtMjRUMjM6MDE6MTktMDQ6MDCc2eR6AAAAAElFTkSuQmCC');
    $s=explode("/",$ft);
    if($s[0]=="image") return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgBAMAAACBVGfHAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAHlBMVEUAAAAAAID///+AgIAAAADAwMCAAAAA//8AgAAAgIA7Mqk3AAAAAXRSTlMAQObYZgAAAAFiS0dEAmYLfGQAAAAHdElNRQfiBhgXECN8eUwvAAAAkElEQVQoz2NgIAIwCkIBXEAJApQF0AUU0QWUBNAFFNEEjI0dUARUQ0OdUQSAACYAcwhcQCwNDBIRAhANSALq5eXl7kVoAi4oAublJS3IAsqTiz1QBDRnmnZ0OCELTA7pQFVhGuqBokI5NDSiJQhVIDQVRcDY2DjZCJ/T0T2H4X2MACIkwOICBxABBmMEwBL1AOtUQBEQRL3xAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA2LTI0VDIzOjE2OjM1LTA0OjAwS1P1dgAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNi0yNFQyMzoxNjozNS0wNDowMDoOTcoAAAAASUVORK5CYII=');
    if($s[0]=='audio') return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAElBMVEUAAACAgID////AwMCAAIAAAAC9RW8gAAAAAXRSTlMAQObYZgAAAAFiS0dEAmYLfGQAAAAHdElNRQfiBhgXExTv6brjAAAARUlEQVQI12NgEAQBBiAQUlJSUhaAMRTBDBcl1dAAMMNFSRnMcIIzVNAYKjCGC5yhBFOsAmc4QRkgO4AMYWMQADJCwSAAAJgaEw++7qzUAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA2LTI0VDIzOjE5OjIwLTA0OjAwJMqBwgAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNi0yNFQyMzoxOToyMC0wNDowMFWXOX4AAAAASUVORK5CYII=');
    if($s[0]=='video') return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAElBMVEUAAACAgID////AwMAAAAD/AADwNPkuAAAAAXRSTlMAQObYZgAAAAFiS0dEAmYLfGQAAAAHdElNRQfiBhoANxc0aHeBAAAAVklEQVQI103M0Q2AMAiE4Ruh1boAGyAMYNvbfyZPo8b/6QsBAFRVoBYz8xd7eUA+sER5UV1HVhNtxvTeEgcZPjKBNcKpCbaYfVCgGrr6/vyg7YgLvDsBNoERSAZ7Iq4AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTgtMDYtMjZUMDA6NTU6MjMtMDQ6MDBkw4VTAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE4LTA2LTI2VDAwOjU1OjIzLTA0OjAwFZ497wAAAABJRU5ErkJggg==');
    if($s[0]=='script'||$s[1]=='x-php') return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAElBMVEWAgIAAAAD////AwMD//wCAgACfPtibAAAAAWJLR0QCZgt8ZAAAAAd0SU1FB+IGGgAyEaB8JvEAAABbSURBVAjXVYztDcAgCETxYwBxAqPtAMQJtCMY9l+lePZPL4G85B4QnTjytZItsSlzCsDrStmg1CkBcCvzrqDAibry/4qcNsgt5WeDH/FCVbrrR2Z7YxA6IsRfXh2UD0vkCUrWAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA2LTI2VDAwOjUwOjE3LTA0OjAw+Cpt5wAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNi0yNlQwMDo1MDoxNy0wNDowMIl31VsAAAAASUVORK5CYII=');
    if($s[1]=='html'||$s[1]=='xml') return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAHlBMVEUAAACAgID////AwMAAAAAAgIAA//8AAIAAgAAAAP9fcaSUAAAAAXRSTlMAQObYZgAAAAFiS0dEAmYLfGQAAAAHdElNRQfiBhoAOCV7JzrOAAAAT0lEQVQI12MQBAMGBgYhJSBQFoAxFKEMFRcHBiFlpdAgZSUgI11VLVw9yYFBuEg1tV2pGCwSMR0kAlRTXgRWowQGQIZqKBAQEIExXCDAAQAVSxevXM5m8QAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOC0wNi0yNlQwMDo1NjozNy0wNDowMLcRGt0AAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTgtMDYtMjZUMDA6NTY6MzctMDQ6MDDGTKJhAAAAAElFTkSuQmCC');
    if($s[1]=="x-sharedlib") return('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAElBMVEUAAACAgID///8AAP8AAADAwMAT19+5AAAAAXRSTlMAQObYZgAAAAFiS0dEAmYLfGQAAAAHdElNRQfiBhoAMQWRi6FPAAAAVElEQVQI12MQBAMBBgYhJRAQhDGEBBiElI2VjJVFXBiEjJWNgCgEiQGWAjHAipUgUkDBEGyKYVKiQJWhoUCGiGtooIuLUAgDA6OLCwODI5ABBiwuACkJE4RkZcj6AAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA2LTI2VDAwOjQ5OjA1LTA0OjAwnoSIWgAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNi0yNlQwMDo0OTowNS0wNDowMO/ZMOYAAAAASUVORK5CYII=');
    return ('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfiBhgXBwzSK/XgAAAAVUlEQVQoz82RyQ3AIAwExxGFpTNIZ6SyzYMghWP/mQ/CXsaSCYAiFkp8L5qp6o8ODPn1pn37bIcIYxCiMhiuaUAnraWR3xryLuAMdtWT4baB9qny/QcuKiSXnu3JMgAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOC0wNi0yNFQyMzowNzoxMi0wNDowMOKdeHsAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTgtMDYtMjRUMjM6MDc6MTItMDQ6MDCTwMDHAAAAAElFTkSuQmCC');
}
function cPTRTCS($path) {
    $currentScriptDir = __DIR__;
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    if (substr($docRoot, -1) !== '/') {
        $docRoot .= '/';
    }
    if (substr($path, 0, 1) !== '/') {
        $path = '/' . $path;
    }
    $pathWithoutDocRoot = ltrim(str_replace($docRoot, '', $path), '/');
    $relativePath = $currentScriptDir . '/' . $pathWithoutDocRoot;
    $relativePath = rtrim($relativePath, '/');

    return $relativePath;
}
function rFO($haystack, $needle) {
    $result = str_replace($needle, '', $haystack, $count);
    if ($count > 0) {
        return $result;
    } else {
        return $haystack;
    }
}
function normalizePathManually($path) {
    $path = trim($path, '/');
    $parts = explode('/', $path);
    $normalizedParts = [];
    foreach ($parts as $part) {
        if ($part === '..') {
            // 如果遇到'..'并且 normalizedParts 中有元素（非根目录情况），则移除最后一个元素
            if (count($normalizedParts) > 0) {
                array_pop($normalizedParts);
            }
        } elseif ($part !== '.' && $part !== '') { // 忽略'.'，并确保不加入空字符串
            $normalizedParts[] = $part;
        }
    }
    return implode('/', $normalizedParts);
}
function eSWS($str) {
    // 检查字符串开头是否为 "/"
    if (strpos($str, '/') !== 0) {
        // 如果不是，则在开头添加 "/"
        return '/' . $str;
    }
    // 如果已经是，则直接返回原字符串
    return $str;
}
function getRelativePathToURL($p) {
    return $p;
}
function gCPRTR($rootpath) {//getCurrentPathRelativeToRoot
    if(strstr($rootpath,'&root')){
        return(str_replace('&root','',$rootpath));
    }
    // 获取脚本名称对应的目录（不包含脚本文件名）
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);

    // 获取PATH_INFO，这部分是脚本名后面的所有路径信息
    // 如果PATH_INFO不存在或为空，则默认为空字符串
    $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

    // 拼接以获取完整的路径，同时处理斜杠以保持路径格式的整洁
    $fullPath = rtrim($scriptDir, '/') . ($pathInfo ? '/' . ltrim($pathInfo, '/') : '');

    return $fullPath;
}
function getMsg($s){
    if($s===true) return "成功";
    if($s===false) return "失败";
    else return "已尝试";
}
function removeDirectoryRecursively($dir) {
    if (!file_exists($dir) || !is_dir($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $filePath = "$dir/$file";
        if (is_dir($filePath)) {
            removeDirectoryRecursively($filePath);
        } else {
            unlink($filePath);
        }
    }
    rmdir($dir);
    return true;
}
function hasChildFiles($dir) {
    if (!is_dir($dir)) return 'false';
    $l=scandir($dir);
    $l=array_diff($l, array('.','..'));
    if(count($l)) return 'true';
    return 'false';
}
function isAllSameChar($str, $char) {
    $result = str_replace($char, '', $str);
    return strlen($result) === 0;
}
function handleAction($act){
    global $actions;
    global $path;
    
    if($actions["action"]!=true){
        $r="Insufficient permission";
    } else {
        switch($act){
            case "del":
                if(strpos($path,'/../')||substr($path,-3)=='/..'||strpos($path,'/./')||substr($path,-2)=='/.'||$_GET['path']=='/'||isAllSameChar($_GET['path'],'/')) $r='删除文件夹 失败';else if(is_dir($path)){
                    if($_GET['has_child']=='true'){
                        $r="删除文件夹 ".getMsg(removeDirectoryRecursively($path));
                    } else {
                        $r="删除文件夹 ".getMsg(rmdir($path));
                    }
                }
                else $r="删除文件 ".getMsg(unlink($path));
                break;
            case "mkdir":
                $r="创建文件夹 ".getMsg(mkdir($path));
                break;
        }
    }
    return ("\n".'<h2>'.$r.'</h2>
<script>
var path="'.$_GET["path"].'"
function start() {
    var a=path.split("/")
    var b=""
    for(var i=0;i<a.length-1;i++){
        b=b+"/"+a[i]
    }
    window.location.replace("?path="+encodeURIComponent(b))
}
setTimeout(function(){
    start("action")
},1000)
</script>');
}

$viewpath=$_GET['path'];
if(!isset($viewpath)||$viewpath===''){
    $viewpath='/';
}
if(!isset($_GET["action"])){
    $viewpath=eSWS(normalizePathManually($viewpath).'/');
}
if($viewpath=='../'||$viewpath=='/../') header('Location: ?path=/');
$path=str_replace('<this>',__DIR__,str_replace("<root>",$_SERVER['DOCUMENT_ROOT'],$rootpath)).$viewpath;
if(isset($_GET["action"])){
    die(handleAction($_GET["action"]));
}
ensureTrailingSlash($viewpath);
if($viewpath!=$_GET['path']) header('Location: ?path='.$viewpath);

if(!file_exists($path)) die('<h2>No such file or directory.</h2><a href="?path='.$viewpath.'/../">Return to previous directory');
try {
    $files = scandir($path);
    natcasesort($files);
    $files = array_diff($files, array('.','..'));
    array_unshift($files,'..');
    
} catch (Exception $e) {
    die("无法读取目录: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Index of <?=$viewpath?></title>
    <style>
        a{
            text-decoration: none;
        }
        a:not([nocolor]),
        a:active {
            color: blue;
        }

        a:hover,
        a:focus {
            text-decoration: underline;
            color: red;
        }

        body {
            background-color: #F5F5F5;
        }

        h2,h3 {
            margin-bottom: 12px;
            width: 97vw;
            padding-right: 8px;
            box-sizing: border-box;
            overflow-x: auto;
            white-space: nowrap;
        }

        table {
            margin-left: 12px;
            width: auto;
        }

        th,
        td {
            font: 90% monospace;
            text-align: left;
        }

        th {
            font-weight: bold;
            padding-right: 14px;
            padding-bottom: 3px;
            white-space: nowrap;
        }

        td{
            padding-right: 14px;
        }
        
        tr{
            -height: 16px;
        }

        td[list-type="size"],
        td[list-type="type"],
        td[list-type="action"]{
            white-space: nowrap;
        }
        
        /*td[list-type="action"],
        th[list-type="action"]{
            position: fixed;
            right:0px;
        }*/
        
        td[list-type="name"] span{
            display: block;
            white-space: nowrap;
            margin: 1px;
        }
        
        td[list-type="icon"] img{
            width:14px;
            margin:0px;
        }
        
        td[list-type="icon"]{
            padding-right:2px;
        }
        
        span a{
            margin-left: 2px;
        }

        div.list {
            background-color: white;
            border-top: 1px solid #646464;
            border-bottom: 1px solid #646464;
            padding-top: 10px;
            padding-bottom: 14px;
            overflow-x: auto;
            max-height: 75vh/*calc(100vh - 256px);*/
        }

        div.foot,
        div.foot *{
            font: 90% monospace;
            color: #787878;
            padding-top: 4px;
        }
        
        a[file-type="directory"]{
            color: green;
        }
        
        .btn-action ~ .btn-action{
            margin-left:4px;
        }
        
        tr[file-type="directory"]{
            display:none;
        }
        
        div[list-type="action"]{
            margin:4px 0px -4px 12px;
            font-size: 12px;
        }
        
        a.btn-action,
        div[list-type="action"] a{
            color: darkorange;
        }
        
        <?
        if($actions['action']==false){
            echo '*[list-type="action"]{
            display: none;
        }';
        }
        ?>
    </style>
</head>

<body>
    <h3>Index of <?=$viewpath?></h3>
    <div class="list">
        <table summary="Directory Listing" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th list-type="icon"></th>
                    <th>名称</th>
                    <th>大小</th>
                    <th>类型</th>
                    <th list-type="action">操作</th>
                </tr>
            </thead>
            <tbody>
                <?
                function isParent($fn){
                    if($fn=='..') return 'Parent Dir';
                    return $fn;
                }
                function isParentBoolean($fn){
                    if($fn=='..') return true;
                    return false;
                }
                function fileNameFormat($fn){
                    $fn=str_replace('&','&amp;',$fn);
                    $fn=str_replace(' ','&nbsp;',$fn);
                    return $fn;
                }
                foreach ($files as $file) {
                    $filepath=$path.$file;
                    if(is_dir($filepath)){
                        echo '<tr file-type="directory">';
                    } else echo '<tr>';
                    echo '<tr>';
                    
                    echo '<td list-type="icon">';
                    echo '<img src="'.getFileIcon(getFileType($filepath)).'"/>';
                    echo '</td>';
                    
                    echo '<td list-type="name">';
                    if(is_dir($filepath)){
                        echo '<span><a file-type="directory" href="?path='.urlencode($viewpath.$file).'/">'.fileNameFormat(isParent($file)).'</a>/</span>';
                    } else echo '<span><a href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">'.fileNameFormat($file).'</a></span>';
                    echo '</td>';
                    
                    echo '<td list-type="size">';
                    if(is_dir($filepath)){
                        echo '-';
                    } else echo formatBytes(filesize($filepath));
                    echo '</td>';
                    
                    echo '<td list-type="type">';
                    echo _gft(getFileType($filepath));
                    echo '</td>';
                    
                    echo '<td list-type="action">';
                    if(!isParentBoolean($file)){
                        echo '<a class="btn-action action-del" onclick="action.del(`'.$file.'`,'.hasChildFiles($filepath).')">删除</a>';
                        if(is_dir($filepath)){
                            echo '<a class="btn-action action-visit" href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">访问</a>';
                        }
                    } else  echo '<a class="btn-action">-</a>';
                    echo '</td>';
                    
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
        <div list-type="action">
            <a onclick="action.mkdir()">新建文件夹</a>
        </div>
    </div>
    <div class="foot">
        <a nocolor href="https://github.com/1503Dev/TotalChest">TotalChest</a>/1.0.0-alpha.4
    </div>
    <script>
        const dir="<?=$viewpath?>";
        const action={
            del:function(f,hasChild){
                let msg='确定删除'+f+'?'
                if(hasChild==true){
                    if(confirm(msg)!=true) return
                    msg='再次确定删除文件夹'+f+'以及里面的所有文件?'
                }
                if(confirm(msg)){
                    window.location.replace('?path='+encodeURIComponent(dir+f)+'&action=del&has_child='+hasChild)
                }
            },
            mkdir:function(){
                function isValidFileName(name) {
                    if(name.length>255) return false
                    if(name==".."||name=='.') return false
                    var invalidCharsPattern = /[\\/:*?"<>|]/; // 匹配不允许的文件/文件夹字符
                    return !invalidCharsPattern.test(name);
                }
                let fn=prompt('新建文件夹')
                if(fn=="") return
                if(isValidFileName(fn)){
                    window.location.replace('?path='+encodeURIComponent(dir)+encodeURIComponent(fn)+'&action=mkdir')
                } else alert('文件名不能包含\\/:*?"<>|，不能为..和.，且不能超过255个字符');
            }
        }
    </script>
</body>

</html>