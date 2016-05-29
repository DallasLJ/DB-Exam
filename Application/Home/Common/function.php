<?php
	
function NoRank($begin=0, $end=20, $limit=5) {
    $rand_array=range($begin,$end);
    shuffle($rand_array);//调用现成的数组随机排列函数
    return array_slice($rand_array,0,$limit);//截取前$limit个
}

function FindTypeId($begin, $end, $rsArr, $typeid) {
    $idposition = 0;
    for ($i=$begin; $i <= $end; $i++) { 
        if ($rsArr[$i]['typeid'] == $typeid) {
            $idposition = $i;
            break;
        }
    }
    return $idposition;
}