<?php
function r($s, $d) { 
    $i = imagecreatefromwebp($s); 
    $w = 543; 
    $h = 724; 
    $n = imagecreatetruecolor($w, $h); 
    imagealphablending($n, false); 
    imagesavealpha($n, true); 
    imagefilledrectangle($n, 0, 0, $w, $h, imagecolorallocatealpha($n, 255, 255, 255, 127)); 
    imagecopyresampled($n, $i, 0, 0, 0, 0, $w, $h, imagesx($i), imagesy($i)); 
    imagewebp($n, $d, 85); 
} 
r('mascot.webp', 'mascot_small.webp'); 
echo "done";
