<?php
/**
 * TotalChest: Lightweight PHP File Manger
 * VERSION 1.0.0-beta.6
 * LICENSE
 * 
 * https://github.com/1503Dev/TotalChest
 */

// User Configs
$rootpath='<root>';
    // '<this>': The directory of this PHP file
    // '<root>': Website rootpath
    // Do not have '/' at the end. And preferably no subdirectory
$actions=[
    'view'=>true // 访客是否允许查看
];
$timezone='Asia/Shanghai'; // 时区
$users=[
    'Admin'=>[
        'password'=>'7300cbe969a3c38a0cdf8bf5918afb50a1da4c982caf9af5ba4dbad31f490bee', //Admin114514
        'permission'=>100 // 管理员
    ],
    'Guest'=>[
        'password'=>'0ec3965f98a8ee525e54eb487e8e38aa66985b1ababd66918fa32be73bcf2f42', //Guest123456
        'permission'=>0 // 普通成员
    ]
    /**
     * '用户名(区分大小写)'=>[
     *     'password'=>'密码(sha-256)',
     *     'permission'=>权限(100为管理员，其余为普通成员)
     * ]
     */
];



ini_set('date.timezone',$timezone);
$html_style=' 
.header {
    width: 100%;
    height: 48px;
    background: white;
    box-shadow: 0px 1px 5px rgba(0,0,0,0.5);
    position: fixed;
    left: 0px;
    top: 0px
}

.header div{
    display:inline-box;
}

.header>.title{
    height:100%;
    line-height:48px;
    margin-left:8px;
    font-size:20px;
    font:bold;
}

.header>.btns{
    position:absolute;
    top:0px;
    right:8px;
    height:100%;
    display: table;
}

.header>.btns> .btn{
    display: table-cell;
    vertical-align: middle;
    font-size:90%;
}

.header>.btns> .btn img{
    vertical-align: middle;
}

a {
    text-decoration: none
}

a:not([nocolor]),
a:active {
    color: blue
}

a:hover,
a:focus {
    text-decoration: underline;
    color: red
}

body {
    background-color: #F5F5F5;
}

h2,
h3 {
    margin-bottom: 12px;
    width: 97vw;
    padding-right: 8px;
    box-sizing: border-box;
    overflow-x: auto;
    white-space: nowrap
}

table {
    margin-left: 12px;
    width: auto
}

th,
td {
    font: 90% monospace;
    text-align: left
}

th {
    font-weight: bold;
    padding-right: 14px;
    padding-bottom: 3px;
    white-space: nowrap
}

td {
    padding-right: 14px
}

tr {
    -height: 16px
}

td {
    white-space: nowrap
}

td[list-type="name"] span {
    display: block;
    white-space: nowrap;
    margin: 1px
}

td[list-type="icon"] img {
    width: 14px;
    margin: 0px
}

td[list-type="icon"] {
    padding-right: 2px
}

span a {
    margin-left: 2px
}

div.list {
    background-color: white;
    border-top: 1px solid #646464;
    border-bottom: 1px solid #646464;
    padding-top: 10px;
    padding-bottom: 14px;
    overflow-x: auto;
    max-height: 75vh
}

div.foot,
div.foot * {
    font: 90% monospace;
    color: #787878;
    padding-top: 4px;
}

a[file-type="directory"] {
    color: green;
}

.btn-action~.btn-action {
    margin-left: 4px;
}

div[list-type="action"] a~a{
    margin-left: 6px;
}

tr[file-type="directory"] {
    display: none
}

div[list-type="action"] {
    margin: 4px 0px -4px 12px;
    font-size: 12px
}

a.btn-action,
div[list-type="action"] a {
    color: darkorange
}
';
$version='1.0.0-beta.6';
function verifyAccount(){
    global $users;
    $stat=[
        'stat'=>false,
        'msg'=>'',
        'user'=>'',
        'permission'=>0
    ];
    if(!isset($_COOKIE['TotalChest_User'])||!isset($_COOKIE['TotalChest_Password'])){
        $stat['msg']='请先登录';
    } else if(!isset($users[$_COOKIE['TotalChest_User']])){
        $stat['msg']='账号不存在';
    } else if($_COOKIE['TotalChest_Password']!==$users[$_COOKIE['TotalChest_User']]['password']){
        $stat['msg']='密码错误';
    } else {
        $stat['stat']=true;
        $stat['user']=$_COOKIE['TotalChest_User'];
        $stat['permission']=$users[$_COOKIE['TotalChest_User']]['permission'];
    }
    return $stat;
}
function showUser(){
    if(isset($_COOKIE['TotalChest_User'])){
        return urldecode($_COOKIE['TotalChest_User']);
    } return '';
}
if((!verifyAccount()['stat']&&$actions['view']==false)||isset($_GET['login'])){
    echo('<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TotalChest: 登录</title>
    <style>
        '.$html_style.'
    </style>
</head>

<body>
    <h3>登录</h3>
    <b>'.verifyAccount()['msg'].'</b>
    <div class="list">
        <table summary="Directory Listing" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td list-type="icon">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAG1BMVEUAAAAAAACAgIDAwMD///8AAP8AAIAA/wAAgABQLitQAAAAAXRSTlMAQObYZgAAAAFiS0dEBI9o2VEAAAAHdElNRQfiBhoANhnKy2vHAAAAbUlEQVQI1y2NQQ6DMBADTV6QDah3QsQdrdqeS/wF7pEQ+QgfZwn1xaP1WgZMIuIBF0cDASaVBo4/My5w2bxffYPA93MZ+I8C8x1ZizSIUQZVj85KaX15JLJqnguSfra6HxXpe1ZTQWwan+d78wJPkhJ8GYBSZwAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxOC0wNi0yNlQwMDo1NDoyNS0wNDowMOjR21cAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTgtMDYtMjZUMDA6NTQ6MjUtMDQ6MDCZjGPrAAAAAElFTkSuQmCC"/>
                    </td>
                    <td list-type="name">
                        账号
                    </td>
                    <td>
                        <input id="user" type="text" value="'.showUser().'">
                    </td>
                </tr>
                <tr>
                    <td list-type="icon">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAJFBMVEUAAACAgIAAAAD////AwMAA/wAAgACAAAD/AACAgAD//wAAAP8K7E+fAAAAAXRSTlMAQObYZgAAAAFiS0dEAxEMTPIAAAAHdElNRQfiBhgXEB4kEQA+AAAAb0lEQVQI12NgYGBgVGCAAGEnCM1oqAIRElZNAgsxmlTMAgsxmmhvMgEzLJyUIVIum8ECDJNUXMACnCqWSmCByZqLZ86cOYGBYdYkrVWrVi0CMmZqLfFyWaLAMGXVKicVJaByJhcXBSYGkHIlJbCNADcgF4855DANAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA2LTI0VDIzOjE2OjMwLTA0OjAwGWva0QAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNi0yNFQyMzoxNjozMC0wNDowMGg2Ym0AAAAASUVORK5CYII="/>
                    </td>
                    <td list-type="name">
                        密码
                    </td>
                    <td>
                        <input id="pwd" type="password">
                    </td>
                </tr>
            </tbody>
        </table>
        <div list-type="action">
            <a onclick="action.login()">登录</a>
        </div>
    </div>
    <div class="foot">
        <a nocolor href="https://github.com/1503Dev/TotalChest">TotalChest</a>/'.$version.' 
    </div>
    <script>
        function sha256(n){function r(n,r){const t=(65535&n)+(65535&r);return(n>>16)+(r>>16)+(t>>16)<<16|65535&t}function t(n,r){return n>>>r|n<<32-r}function o(n,r){return n>>>r}function e(n,r,t){return n&r^~n&t}function u(n,r,t){return n&r^n&t^r&t}function f(n){return t(n,2)^t(n,13)^t(n,22)}function c(n){return t(n,6)^t(n,11)^t(n,25)}function i(n){return t(n,7)^t(n,18)^o(n,3)}return function(n){const r="0123456789abcdef";let t="";for(let o=0;o<4*n.length;o++)t+=r.charAt(n[o>>2]>>8*(3-o%4)+4&15)+r.charAt(n[o>>2]>>8*(3-o%4)&15);return t}(function(n,h){const a=[1116352408,1899447441,3049323471,3921009573,961987163,1508970993,2453635748,2870763221,3624381080,310598401,607225278,1426881987,1925078388,2162078206,2614888103,3248222580,3835390401,4022224774,264347078,604807628,770255983,1249150122,1555081692,1996064986,2554220882,2821834349,2952996808,3210313671,3336571891,3584528711,113926993,338241895,666307205,773529912,1294757372,1396182291,1695183700,1986661051,2177026350,2456956037,2730485921,2820302411,3259730800,3345764771,3516065817,3600352804,4094571909,275423344,430227734,506948616,659060556,883997877,958139571,1322822218,1537002063,1747873779,1955562222,2024104815,2227730452,2361852424,2428436474,2756734187,3204031479,3329325298],C=[1779033703,3144134277,1013904242,2773480762,1359893119,2600822924,528734635,1541459225],g=new Array(64);let l,d,m,s,S,A,b,p,v,w,y,j;for(n[h>>5]|=128<<24-h%32,n[15+(h+64>>9<<4)]=h,v=0;v<n.length;v+=16){for(l=C[0],d=C[1],m=C[2],s=C[3],S=C[4],A=C[5],b=C[6],p=C[7],w=0;w<64;w++)g[w]=w<16?n[w+v]:r(r(r(t(k=g[w-2],17)^t(k,19)^o(k,10),g[w-7]),i(g[w-15])),g[w-16]),y=r(r(r(r(p,c(S)),e(S,A,b)),a[w]),g[w]),j=r(f(l),u(l,d,m)),p=b,b=A,A=S,S=r(s,y),s=m,m=d,d=l,l=r(y,j);C[0]=r(l,C[0]),C[1]=r(d,C[1]),C[2]=r(m,C[2]),C[3]=r(s,C[3]),C[4]=r(S,C[4]),C[5]=r(A,C[5]),C[6]=r(b,C[6]),C[7]=r(p,C[7])}var k;return C}(function(n){const r=[];for(let t=0;t<8*n.length;t+=8)r[t>>5]|=(255&n.charCodeAt(t/8))<<24-t%32;return r}(n=function(n){n=n.replace(/\r\n/g,"\n");let r="";for(let t=0;t<n.length;t++){const o=n.charCodeAt(t);o<128?r+=String.fromCharCode(o):o>127&&o<2048?(r+=String.fromCharCode(o>>6|192),r+=String.fromCharCode(63&o|128)):(r+=String.fromCharCode(o>>12|224),r+=String.fromCharCode(o>>6&63|128),r+=String.fromCharCode(63&o|128))}return r}(n)),8*n.length))}
        user.value=decodeURI(user.value)
        const action={
            login:function(){
                document.cookie="TotalChest_User="+
                    encodeURIComponent(user.value)
                document.cookie="TotalChest_Password="+
                    encodeURIComponent(sha256(pwd.value))
                window.location.replace("?path=/")
            }
        }
    </script>
</body>

</html>');
    setcookie("TotalChest_Password", "", time()-3600);
    die();
}
if($actions['view']==false&&!verifyAccount()['stat']){
    die('<h2>Insufficient permission</h2>');
}
function getMaxUpload() {
    $sizeStr=ini_get('upload_max_filesize');
    $units = ['B' => 1, 'K' => 1024, 'M' => 1024*1024, 'G' => 1024*1024*1024, 'T' => 1024*1024*1024*1024];
    preg_match('/^([0-9]+)([BKMGT])$/', $sizeStr, $matches);
    if ($matches) {
        return (int)$matches[1] * $units[$matches[2]];
    }
    return false;
}
function formatBytes($bytes) {
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
        'application/x-empty'=>'空文件',
        "image/jpeg"=>"图片",
        "image/gif"=>"图片",
        "image/png"=>"图片",
        'image/vnd.microsoft.icon'=>'图标',
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
    if(strstr($rootpath,'<root>')){
        return(str_replace('<root>','',$rootpath));
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
    else return "完成";
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
function getFileDir($path){
    $a=explode('/',$path);
    $b='';
    for($i=0;$i<count($a)-1;$i++){
        $b=$b.$a[$i].'/';
    }
    return $b;
}
function handleAction($act){
    global $actions;
    global $path;
    
    if(verifyAccount()['permission']!=100){
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
            case "mkfile":
                $r="创建文件 ".getMsg(file_put_contents($path,''));
                break;
            case 'view':
                die(file_get_contents($path));
                break;
            case 'edit':
                die('编辑文件 '.getMsg(file_put_contents($path,$_POST['content'])));
                break;
            case 'rename':
                if(file_exists(getFileDir($path).$_GET['new_name'])){
                    $r='文件已存在';
                } else $r='重命名 '.getMsg(rename($path,getFileDir($path).$_GET['new_name']));
                break;
            case 'unzip':
$zipFilePath = $path;
$destinationPath = getFileDir($path);
$zip = new ZipArchive();
$res = $zip->open($zipFilePath);
if ($res === TRUE) {
    $zip->extractTo($destinationPath);
    $zip->close();
    $r= "解压成功";
} else {
    $r= "无法打开ZIP文件";
}
                break;
            case 'can_upload':
                if((int)$_GET['size']>getMaxUpload()){
                    die("文件过大\n大于php.ini中[PHP]upload_max_filesize或[PHP]post_max_size的值");
                } die('');
                break;
            case 'upload':
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['file']['tmp_name'];
                    $target_dir = $path;
                    $target_file = $target_dir . basename($_FILES["file"]["name"]);
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $r= "suc%上传成功";
                    } else {
                        $r= "err%上传出错".$_FILES['file']['error'];
                    }
                } else {
                    die("err%文件过大\n大于php.ini中[PHP]upload_max_filesize或[PHP]post_max_size的值");
                }
                die($r);
            default:
                header('Location: ?path='.urlencode($_GET['path']));
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
},750)
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
    <title>TotalChest</title>
    <style>
        <?=$html_style?>
        body {
            padding-top:32px;
        }
        <?
        if(verifyAccount()['permission']!=100){
            echo '*[list-type="action"]{
            display: none;
        }';
        }
        ?>
    </style>
</head>

<body>
    <div class="header">
        <b class="title">TotalChest</b>
        <div class="btns">
            <a class="btn" style="<?
            if(verifyAccount()['stat']==false) echo 'display:none;';
            ?>;" onclick="action.logout()">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAHlBMVEUAAAAAAACAgICAAAD/AADAwMD///8AgID//wCAgACkKDC8AAAAAXRSTlMAQObYZgAAAAFiS0dEBmFmuH0AAAAHdElNRQfiBBMBIhSc3rbyAAAAd0lEQVQI12MQYmBgYDY2ZmBgVGBgMnFxMWBgEGJSdnFxcWZgYFINdg0NdQbSocFuaWnODGqhQcpgEaUk1WCwGiG10FCwLkal1NBgY2MBBpCQomhYAdBEpUSJzrR0oAVCipHT0guBDEbBsvRCoBogEIfSDIxQGgwApXIV0NBrragAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTgtMDQtMTlUMDE6MzQ6MjAtMDQ6MDAmDt15AAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE4LTA0LTE5VDAxOjM0OjIwLTA0OjAwV1NlxQAAAABJRU5ErkJggg==">
                退出登录
            </a>
            <a class="btn" style="<?
            if(verifyAccount()['stat']==true) echo 'display:none;';
            ?>;" onclick="action.login()">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAGFBMVEUAAAAAAACAgIDAwMD///8AgID//wCAgACnIk6mAAAAAXRSTlMAQObYZgAAAAFiS0dEBI9o2VEAAAAHdElNRQfiBBMBIhXr2YZkAAAAY0lEQVQI1y3NTQqAIBQE4NETmFFr/06QQet+3Cd0hy7Q/emNKDy+YRYjLAAtB+Wgo2OyOuaFgbJqsko0Z4dwUAk2UW+gwimOBqz8uN6yGPb5vQoX/faVyk+Gp1TDbUxdqG57P90ADaNxh3mAAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA0LTE5VDAxOjM0OjIxLTA0OjAwgHnWzQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wNC0xOVQwMTozNDoyMS0wNDowMPEkbnEAAAAASUVORK5CYII=">
                登录
            </a>
        </div>
    </div>
    <h3>Index of <?=$viewpath?></h3>
    <div class="list">
        <table summary="Directory Listing" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th list-type="icon"></th>
                    <th>名称</th>
                    <th>大小</th>
                    <th list-type="filetime">修改时间</th>
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
                    
                    echo '<td list-type="filetime">';
                    if(isParentBoolean($file)){
                        echo '-';
                    } else echo date('Y-m-d H:i:s',filemtime($filepath));
                    echo '</td>';
                    
                    echo '<td list-type="type">';
                    if(isParentBoolean($file)){
                        echo '父文件夹';
                    } else echo _gft(getFileType($filepath));
                    echo '</td>';
                    
                    echo '<td list-type="action">';
                    if(!isParentBoolean($file)){
                        echo '<a class="btn-action action-del" onclick="action.del(`'.urlencode($file).'`,'.hasChildFiles($filepath).')">删除</a>';
                        if(is_dir($filepath)){
                            echo '<a class="btn-action action-visit" href="'.gCPRTR($rootpath).getRelativePathToURL(rFO(cPTRTCS($viewpath.$file),__DIR__)).'">访问</a>';
                        } else if(strstr(getFileType($filepath),'application')==false&&strstr(getFileType($filepath),'image')==false) {
                            echo '<a class="btn-action action-edit" onclick="action.edit(`'.urlencode($file).'`,'.filesize($filepath).')">编辑</a>';
                        } if(getFileType($filepath)=='application/zip'){
                            echo '<a class="btn-action action-unzip" onclick="action.unzip(`'.urlencode($file).'`)">解压</a>';
                        }
                        echo '<a class="btn-action action-rename" onclick="action.rename(`'.urlencode($file).'`)">重命名</a>';
                    } else  echo '<a class="btn-action">-</a>';
                    echo '</td>';
                    
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
        <div list-type="action">
            <a onclick="action.mkdir()">新建文件夹</a>
            <a onclick="action.mkfile()">新建文件</a>
            <a>
                <label for="fileSelector">
                    上传文件(单个)
                    <input type="file" onchange="action.upload(this,this.parentNode.parentNode)" id="fileSelector" style="display:none;">
                </label>
            </a>
        </div>
    </div>
    <div class="foot">
        <a nocolor href="https://github.com/1503Dev/TotalChest">TotalChest</a>/<?=$version?>
    </div>
    <script>
        const dir="<?=$viewpath?>";
        const fileUnValidMsg='文件名不能包含\\/:*?"<>|，不能为..和.，且不能超过255个字符';
        function isValidFileName(name) {
            if(name.length>255) return false
            if(name==".."||name=='.') return false
            var invalidCharsPattern = /[\\/:*?"<>|]/; // 匹配不允许的文件/文件夹字符
            return !invalidCharsPattern.test(name);
        }
        function httpGet(url, callback = function() {}) {
            var xhttp = new XMLHttpRequest()
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    callback(this.responseText, this.status)
                }
            }
            xhttp.open("GET", url, true)
            xhttp.send()
        }
        function httpPost(url, data, callback = function() {}) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    callback(this.responseText);
                }
            };
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(data);
        }
        function upload(file,pe){
            var xhr = new XMLHttpRequest();
            var formData = new FormData();
            formData.append('file', file);
            xhr.open('POST', '?path='+encodeURIComponent(dir)+'&action=upload', true);
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                      var percentComplete = event.loaded / event.total;
                    percentComplete = parseInt(percentComplete * 100);
                    pe.innerHTML=(percentComplete + '%');
                } else {
                 pe.innerHTML=('无法计算进度');
                }
            };
            xhr.onload = function() {
                if(this.responseText.split('%')[0]=="err"){
                    alert(this.responseText.split('%')[1])
                } else {
                    pe.innerHTML=this.responseText.split('%')[1]
                }
                setTimeout('location.reload()',500)
            };
            xhr.send(formData);
        }
        const action={
            logout:function(){
                //document.cookie = "TotalChest_User=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
                document.cookie = "TotalChest_Password=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
                window.location.replace('?login')
            },
            login:function(){
                window.location.replace('?login')
            },
            del:function(f,hasChild){
                f=decodeURI(f)
                let msg='确定删除'+f+'?'
                if(hasChild==true){
                    if(confirm(msg)!=true) return
                    msg='再次确定删除文件夹'+f+'以及里面的所有文件?'
                }
                if(confirm(msg)){
                    window.location.replace('?path='+encodeURIComponent(dir+f)+'&action=del&has_child='+hasChild)
                }
            },
            edit:function(f,size){
                f=decodeURI(f)
                if(size>16777216) return alert('超过16MB的文件不能编辑')
                httpGet('?path='+encodeURIComponent(dir+f)+'&action=view',function(r){
                    let c=prompt('编辑',r)
                    if(c!==r&&c!==void 0&&c!==null&&c!==''){
                        httpPost('?path='+encodeURIComponent(dir+f)+'&action=edit','content='+encodeURIComponent(c),function(r){
                            alert(r)
                            location.reload()
                        })
                    }
                })
            },
            rename:function(f){
                f=decodeURI(f)
                let fn=prompt('重命名'+f,f)
                if(fn==""||fn==f) return
                if(isValidFileName(fn)){
                    window.location.replace('?path='+encodeURIComponent(dir+f)+'&new_name='+encodeURIComponent(fn)+'&action=rename')
                } else alert(fileUnValidMsg)
            },
            unzip:function(f){
                f=decodeURI(f)
                if(confirm('是否解压'+f)) window.location.replace('?path='+encodeURIComponent(dir+f)+'&action=unzip')
            },
            mkdir:function(){
                let fn=prompt('新建文件夹')
                if(fn=="") return
                if(isValidFileName(fn)){
                    window.location.replace('?path='+encodeURIComponent(dir+fn)+'&action=mkdir')
                } else alert(fileUnValidMsg);
            },
            mkfile:function(){
                let fn=prompt('新建文件')
                if(fn=="") return
                if(isValidFileName(fn)){
                    window.location.replace('?path='+encodeURIComponent(dir+fn)+'&action=mkfile')
                } else alert(fileUnValidMsg);
            },
            upload:function(e,pe){
                let file=e.files[0]
                if(!file) return
                httpGet('?path=/&action=can_upload&size='+file.size,function(r){
                    if(r!='') return alert(r)
                    upload(file,pe)
                })
            }
        }
    </script>
</body>

</html>