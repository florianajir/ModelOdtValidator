<?php
/**
 * Created by PhpStorm.
 * User: fajir
 * Date: 18/11/13
 * Time: 09:59
 */
//include_once('../Lib/PhpOdtApi.php');

//function recurseTree($var){
//    $out = '';
//    foreach($var as $v){
//        if(is_array($v)){
//            $out .= '<ul>'.recurseTree($v).'</ul>';
//        }else{
//            $out .= '<li>'.$v.'</li>';
//        }
//    }
//    return $out;
//}
//
//echo '<ul>Fichiers scannés : ';
////Table des matières
//foreach (scandir ( './files/' ) as $file){
//    if ($file[0] != '.')
//        echo '<li><a href="#'.$file.'">'.$file.'</a></li>';
//}
//echo '</ul>';


echo "<h4>Sections</h4>";
echo "<ul>";
foreach ($sections as $section){
    echo "<li>$section</li>";
}
echo "</ul>";

echo "<h4>Variables</h4>";
echo "<ul>";
foreach ($variables as $var){
    echo "<li>$var</li>";
}
echo "</ul>";
