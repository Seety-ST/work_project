<?php 
//Ğ¡Ê±
$res = array();
for($i=0;$i<=23;$i++)
{
    if(strlen($i) == 1)
    {
        $i = '0'.$i;
    }
    $res[] = array('id'=>$i,'text'=>$i."µã");
}
return $res;





