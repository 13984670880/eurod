<?php

class SearchingController extends Genius_AbstractController
{

    public function autocompleteAction()
    {
        $term = $_GET[ "term" ];

        $search = $this->_getParam('term');


        $interdit=array(">", "<",  ":", "*", "/", "|", "?", '"', '<', '>',"#","$","%","£","@","À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
        $search = str_replace($interdit, "", $search);

        if (strtolower($search) == "reparation" )
        {
            $this->_redirect("/reparation");
        }
        elseif(strtolower($search) == "vente" )
        {
            $this->_redirect("/vente");
        }
        elseif(strtolower($search) == "maintenance"  || strtolower($search) == "garantie" )
        {
            $this->_redirect("/maintenance");
        }
        elseif(strtolower($search) == "location" )
        {
            $this->_redirect("/location");
        }
        elseif(strtolower($search) == "audit" )
        {
            $this->_redirect("/audit");
        }
        elseif(strtolower($search) == "reprise" )
        {
            $this->_redirect("/reprise");

        }
        elseif(strtolower($search) == "smartprint" )
        {
            $this->_redirect("/smartprint");
        }

        $results = Genius_Model_Search::get($search);


        $data=[];
            $i=0;
            foreach ($results[1] as $index => $result) {
                if($i < 6){
                    $data[]=[
                        "value" => $result['title'],
                        "label" => [
                            "label" => '' ,
                            'h' => PROJECTS.'/xxx.html'
                        ],
                        "desc" => $result['photocrh_cover_p'],
                    ];
                }

                $i++;
            }






        //$data = [
        //       [
        //           "value" => $term,
        //           "label" => [
        //               "label" => 'Imprimante thermique' ,
        //               'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //           ],
        //                           "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //       ],
        //    [
        //        "value" => "Zebra GX420",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //                        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    [
        //        "value" => "Zebra LP2844",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //                        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    [
        //        "value" => "Zebra ZT220",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    [
        //        "value" => "Zebra ZT220",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    [
        //        "value" => "Zebra ZT220",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    [
        //        "value" => "Zebra ZT220",
        //        "label" => [
        //            "label" => 'Imprimante thermique' ,
        //            'h' => PROJECTS.'/imprimantes-etiquettes/zebra/petits-modeles/lp-tlp2844-15.html'
        //        ],
        //        "desc" => PROJECTS.'/upload/images/geo/zt600_.jpg',
        //    ],
        //    ];

        echo Zend_Json::encode($data);

        exit();
    }
}