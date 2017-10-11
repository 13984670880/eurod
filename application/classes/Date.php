<?php

class Genius_Class_Date {

    public static function age($naiss) {
        list($jour, $mois, $annee) = split('-', $naiss);
        $today['mois'] = date('n');
        $today['jour'] = date('j');
        $today['annee'] = date('Y');
        $annees = $today['annee'] - $annee;

        if ($today['mois'] <= $mois) {
            if ($mois == $today['mois']) {
                if ($jour > $today['jour'])
                    $annees--;
            }
            else
                $annees--;
        }
        return $annees;
    }

    public static function ConvertToMysqlDate($date) {
        if (isset($date)) {
            $tDate = explode("/", $date);
            return $dateUk = $tDate[2] . "-" . $tDate[1] . "-" . $tDate[0];
        }
        else
            return "";
    }

}