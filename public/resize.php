<?php
function r($s, $d) { 
    $i = imagecreatefromwebp($s); 
    $w = 270; 
    $h = 65; 
    $n = imagecreatetruecolor($w, $h); 
    imagealphablending($n, false); 
    imagesavealpha($n, true); 
    imagefilledrectangle($n, 0, 0, $w, $h, imagecolorallocatealpha($n, 255, 255, 255, 127)); 
    imagecopyresampled($n, $i, 0, 0, 0, 0, $w, $h, imagesx($i), imagesy($i)); 
    imagewebp($n, $d, 85); 
} 
r('logo_web.webp', 'logo_web_small.webp'); 
r('logo_web2.webp', 'logo_web2_small.webp'); 
echo "done";
