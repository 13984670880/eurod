<?php

class SearchingController extends Genius_AbstractController
{

    public function autocompleteAction()
    {
        $term = $_GET[ "term" ];

        $data = [
               [
                   "value" => "Zebra ZM600",
                   "label" => [
                       "label" => 'Imprimante thermique' ,
                       'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                   ],
                                   "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
               ],
            [
                "value" => "Zebra GX420",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            [
                "value" => "Zebra LP2844",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            [
                "value" => "Zebra ZT220",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            [
                "value" => "Zebra ZT220",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            [
                "value" => "Zebra ZT220",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            [
                "value" => "Zebra ZT220",
                "label" => [
                    "label" => 'Imprimante thermique' ,
                    'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
                ],
                "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
            ],
            ];

        echo Zend_Json::encode($data);

        exit();
    }
}