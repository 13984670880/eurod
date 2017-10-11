<?php

class Genius_Class_Cookie {

    public static function isCorrect($tab, $id_marque, $id_product) {
        foreach ($tab as $key => $value) {
            if ($key == $id_marque) {
                foreach ($value as $v) {
                    if ($v == $id_product) {
                        return true;
                    }
                }
            }
        }
    }

    public static function inGroup($tab, $id_marque, $id_product) {
        foreach ($tab as $key => $value) {
            if ($key == $id_marque) {
                return true;
            }
        }
    }

    public static function countCookie($tab, $id_marque, $id_product) {
        foreach ($tab as $key => $value) {
            if ($key == $id_marque) {
                return count($value);
            }
        }
    }

}
