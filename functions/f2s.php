<?php
function f2s(float $f) {
    $s = (string)$f;
    if (!strpos($s,"E")) return $s;
    list($be,$ae)= explode("E",$s);
    $fs = "%.".(string)(strlen(explode(".",$be)[1])+(abs($ae)-1))."f";
    return sprintf($fs,$f);
}
?>