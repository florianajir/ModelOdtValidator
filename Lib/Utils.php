<?php

/**
 * Application: webdelib / Adullact.
 * Date: 25/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
class Utils {
    public static function human_filesize($bytes, $decimals = 2) {
        $sz = array('B', 'Ko', 'Mo', 'Go');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}