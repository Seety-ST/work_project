<?php 
//·ÖÖÓ
$res = array();
for($i=0;$i<=59;$i++)
{
    if(strlen($i) == 1)
    {
        $i = '0'.$i;
    }
    $res[] = array('id'=>$i,'text'=>$i."·Ö");
}
return $res;





