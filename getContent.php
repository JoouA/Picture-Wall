<?php
include "config.php";

$filetimes = [];
/*
 *获取目录结构
 *
 */
function getListResource($path)
{
    global $filetimes;

    $res = opendir($path);
    while ($file = readdir($res)) {
        if ($file !== '.' && $file !== '..') {
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                $resources[$file] = getListResource($path . DIRECTORY_SEPARATOR . $file);
            } else {
                $ext = ['jpg', 'png', 'jpeg', 'bmp','gif'];
                if (in_array(substr(strrchr($file, '.'), 1), $ext)) {
                    array_push($filetimes,date('Y-m-d H:i:s', filectime($path . DIRECTORY_SEPARATOR . $file)));
//                    $filetimes[] = date('Y-m-d H:i:s', filectime($path . DIRECTORY_SEPARATOR . $file));
                    $resources[] = $path . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
    }
    @closedir($res);


    $newResource = [];

    foreach ($resources as $resource){
        if (is_array($resource)){
            foreach ($resource as $value )
            $newResource[] = $value;
        }else{
            $newResource[] = $resource;
        }
    }

    $resources = count($newResource) ? $newResource : 'Not found!';

    return $resources;
}

function getList($path)
{
    global $filetimes;
    $newResource = getListResource($path);
    // 按照文件创建的顺序来进行排列
    array_multisort($filetimes, SORT_ASC, SORT_STRING, $newResource);
    echo json_encode($newResource);
}



function getNumber($config)
{
    $number = count(getListResource($config['path']));

    return $number;
}


$action = $_GET['action'];

if ($action == 'getNumber') {
    echo getNumber($config);
} else if ($action == 'getList') {
    echo getList($config['path']);
}else{
    return;
}
//print_r(getListResource('tmp'));

