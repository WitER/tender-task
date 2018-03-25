<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('validInn')) {
    function validInn($inn)
    {
        $inn = trim($inn);
        if (preg_match('#^([\d]{10})$#', $inn, $m)) {
            $inn = $m[0];
            $code10 = (($inn[0] * 2 + $inn[1] * 4 + $inn[2] *10 + $inn[3] * 3 +
                        $inn[4] * 5 + $inn[5] * 9 + $inn[6] * 4 + $inn[7] * 6 +
                        $inn[8] * 8) % 11 ) % 10;
            if ($code10 == $inn[9]) return $inn;
        }

        if (preg_match('#^([\d]{12})$#', $inn, $m)) {
            $inn = $m[0];
            $code11 = (($inn[0] * 7 + $inn[1] * 2 + $inn[2] * 4 + $inn[3] *10 +
                        $inn[4] * 3 + $inn[5] * 5 + $inn[6] * 9 + $inn[7] * 4 +
                        $inn[8] * 6 + $inn[9] * 8) % 11 ) % 10;
            $code12 = (($inn[0] * 3 + $inn[1] * 7 + $inn[2] * 2 + $inn[3] * 4 +
                        $inn[4] *10 + $inn[5] * 3 + $inn[6] * 5 + $inn[7] * 9 +
                        $inn[8] * 4 + $inn[9] * 6 + $inn[10]* 8) % 11 ) % 10;

            if ($code11 == $inn[10] && $code12 == $inn[11]) return $inn;
        }

        return false;
    }
}

if (!function_exists('validOgrn')) {
    function validOgrn($ogrn)
    {
        $ogrn = trim($ogrn);
        if (preg_match('#^([\d]{13})$#', $ogrn, $m)) {
            $code1 = substr($m[1], 0, 12);
            $code2 = floor($code1 / 11) * 11;
            $code = ($code1 - $code2) % 10;
            if ($code == $m[1][12]) return $m[1];
        }

        // ОГРНИП
        if (preg_match('#^([\d]{15})$#', $ogrn, $m)) {
        $code1 = substr($m[1], 0, 14);
        $code2 = floor($code1 / 13) * 13;
        $code = ($code1 - $code2) % 10;
        if ($code == $m[1][14]) return $m[1];
    }

        return false;
    }
}