<?php
/**
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
function _gft(){
    $fts=json_decode('{
        "js":"text/javascript"
    }');
}
function getFileType($f){
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
        "image/gif"=>"图片"
    ];
    $finfo = finfo_open(FILEINFO_MIME);
    $ft=explode(';',finfo_file($finfo, $f))[0];
    finfo_close($finfo);
    if(isset($types[$ft])){
        return $types[$ft];
    } return $ft;
}
function cPTRTCS($path) {
    // 当前脚本的绝对路径
    $currentScriptDir = __DIR__;
    
    // 服务器文档根目录
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    
    // 确保文档根目录和路径都以斜杠结尾，以便正确比较和替换
    if (substr($docRoot, -1) !== '/') {
        $docRoot .= '/';
    }
    if (substr($path, 0, 1) !== '/') {
        $path = '/' . $path;
    }
    
    // 从路径中移除文档根目录部分
    $pathWithoutDocRoot = ltrim(str_replace($docRoot, '', $path), '/');
    
    // 如果原始路径不是相对于文档根目录的，此逻辑可能需要调整
    // 这里假定输入的$path总是相对于文档根的，直接计算相对路径
    $relativePath = $currentScriptDir . '/' . $pathWithoutDocRoot;
    
    // 确保结果路径规范化，避免多余的斜杠
    $relativePath = rtrim($relativePath, '/');

    return $relativePath;
}
function rFO($haystack, $needle) {
    // 使用str_replace找到并替换掉第一个匹配的子字符串，限制定符1表示只替换第一个匹配项
    $result = str_replace($needle, '', $haystack, $count);
    
    // 确保确实有替换发生，避免不必要的操作
    if ($count > 0) {
        return $result;
    } else {
        // 如果没有找到匹配项，则返回原字符串
        return $haystack;
    }
}
function normalizePathManually($path) {
    // 处理路径首尾的斜杠
    $path = trim($path, '/');

    // 分割路径为数组
    $parts = explode('/', $path);

    // 用于存储处理后的路径组件
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

    // 重新组合路径
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
    if($s==true) return "成功";
    if($s==false) return "失败";
    else return "完成";
}
function handleAction($act){
    global $actions;
    global $path;
    
    if($actions["action"]!=true){
        $r="Insufficient permission";
    } else {
        switch($act){
            case "del":
                $r="删除文件 ".getMsg(unlink($path));
                break;
        }
    }
    return ("\n".'<h2>'.$r.'</h2>
<script>
var path=`'.$_GET["path"].'`
function start() {
    var a=path.split("/")
    var b=""
    for(var i=0;i<a.length-1;i++){
        b=b+"/"+a[i]
    }
    window.location.replace("?path="+b)
}
setTimeout(function(){
    start("action")
},2000)
</script>');
}

$viewpath=$_GET['path'];
if(!isset($viewpath)||$viewpath===''){
    $viewpath='/';
}
if(!isset($_GET["action"])){
    $viewpath=eSWS(normalizePathManually($viewpath).'/');
}
if($viewpath=='../') header('Location: ?path=/');
$path=str_replace('&this',__DIR__,str_replace("&root",$_SERVER['DOCUMENT_ROOT'],$rootpath)).$viewpath;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        h2 {
            margin-bottom: 12px;
            width: 97vw;
            padding-right: 8px;
            box-sizing: border-box;
            overflow-x: auto;
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
        
        td[list-type="name"] span{
            display: block;
            border-left: 2px #ccc solid;
            margin: 1px;
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
    <h2>Index of <?=$viewpath?></h2>
    <div class="list">
        <table summary="Directory Listing" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>大小</th>
                    <th>类型</th>
                    <th list-type="action">操作</th>
                </tr>
            </thead>
            <tbody>
                <?
                foreach ($files as $file) {
                    $filepath=$path.$file;
                    if(is_dir($filepath)){
                        echo '<tr file-type="directory">';
                    } else echo '<tr>';
                    echo '<tr>';
                    echo '<td list-type="name">';
                    if(is_dir($filepath)){
                        echo '<span><a file-type="directory" href="?path='.$viewpath.$file.'/">'.$file.'</a>/</span>';
                    } else echo '<span><a href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">'.$file.'</a></span>';
                    echo '</td>';
                    echo '<td list-type="size">';
                    if(is_dir($filepath)){
                        echo '-';
                    } else echo formatBytes(filesize($filepath));
                    echo '</td>';
                    echo '<td list-type="type">';
                    echo getFileType($filepath);
                    echo '</td>';
                    echo '<td list-type="action">';
                    if(!is_dir($filepath)){
                        echo '<a class="btn-action action-del" onclick="action.del(`'.$file.'`)">删除</a>';
                    }
                    if(is_dir($filepath)){
                        echo '<a class="btn-action action-visit" href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">访问</a>';
                    }
                    echo '</td>';
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="foot">
        <a nocolor href="https://github.com/1503Dev/TotalChest">TotalChest</a>/1.0.0-alpha.3
    </div>
    <script>
        const dir=`<?=$viewpath?>`;
        const action={
            del:function(f){
                if(confirm('确定删除'+f+'?')){
                    window.location.replace('?path='+dir+f+'&action=del')
                }
            }
        }
    </script>
</body>

</html>