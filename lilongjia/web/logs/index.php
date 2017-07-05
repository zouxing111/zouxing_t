<?php
$delete = array_key_exists('delete',$_GET)?$_GET['delete']:'';
if ($delete and ($delete!='./index.php')){
    if (unlink($delete)){
        echo "success";
    }
    else{
        echo "fail";
    }
    exit;
}

$dir="./";
$filenames = getDirFiles($dir);
$resultarray = array();
$content = '';
foreach ($filenames as $nothing=>$filename) {
    if (($filename=='..') or ($filename=='.') or ($filename=='index.php')){
        continue;
    }
    $filename = $dir.$filename;
    $modifytime = filemtime($filename);//获取文档最后修改的时间
    $filesize = filesize($filename);//获取文件的大小
    $resultarray[$modifytime] = array($filename,$modifytime,$filesize) ;
}
ksort($resultarray);
foreach ($resultarray as $modifytime=>$fileinfoarray) {

    $modifytime = date("Y-m-d H:i:s",$modifytime);
    $filelink = "<a target='_blank' href='./".$fileinfoarray[0]."'>"."$fileinfoarray[0]"."</a>";
    $fileinfoarray[2] = number_format(($fileinfoarray[2]/1024 ), 2, '.', '');
    $deletelink = "<a target='_blank' href='./index.php?delete=".$fileinfoarray[0]."'>Delete</a>";
    $content .=
	"<tr>
		<td width='270'>{$filelink}</td>
		<td width='180'>{$modifytime}</td>
		<td width='180'>{$fileinfoarray[2]} k</td>
		<td width='180'>{$deletelink}</td>
	</tr>";
}
echo "<table border='0' cellpadding='0' cellspacing='0'>".$content."</table>";


function getDirFiles($dir) {
    if ($handle = opendir($dir)) {
        /* Because the return type could be false or other equivalent type(like 0),
         this is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle))) {
            $files[]=$file;
        }
    }
    closedir($handle);
    if($files)
        return $files;
    else
        return false;
}
?>