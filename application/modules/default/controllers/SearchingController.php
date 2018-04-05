<?php

class SearchingController extends Genius_AbstractController
{

    public function autocompleteAction()
    {
        $term = $_GET[ "term" ];

        $search = $this->_getParam('term');


        $interdit=array(">", "<",  ":", "*", "/", "|", "?", '"', '<', '>',"#","$","%","£","@","À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
        $search = str_replace($interdit, "", $search);

        $termlow = strtolower($term);

        Genius_Model_Traceur::track($_SERVER['REMOTE_ADDR'],'search_autocompletion',$search);


        if ( (strpos('reparation',$termlow) !== false) || (strpos('réparation',$termlow) !== false))
        {

            $data[]=[
                'id'=>'',
                "value" => 'Réparation',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/reparation'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }
        elseif((strpos('vente',$termlow) !== false) || (strpos('echange',$termlow) !== false))
        {
            $data[]=[
                'id'=>'',
                "value" => 'Vente',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/vente'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }
        elseif((strpos('maintenance',$termlow) !== false) )
        {
            $data[]=[
                'id'=>'',
                "value" => 'Maintenance',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/maintenance'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }
        elseif((strpos('location',$termlow) !== false) )
        {
            $data[]=[
                'id'=>'',
                "value" => 'Location',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/location'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }
        elseif((strpos('audit',$termlow) !== false) )
        {
            $data[]=[
                'id'=>'',
                "value" => 'Audit',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/audit'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }
        elseif((strpos('reprise',$termlow) !== false) )
        {
            $data[]=[
                'id'=>'',
                "value" => 'Reprise',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/reprise'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();

        }
        elseif((strpos('smartprint',$termlow) !== false) )
        {
            $data[]=[
                'id'=>'',
                "value" => 'Smartprint',
                "label" => [
                    "id"=> '',
                    "label" => '',
                    'h' => PROJECTS.'/smartprint'
                ],
                "desc" =>  PROJECTS.'/upload/images/geo/question.png'
            ];
            echo Zend_Json::encode($data);

            exit();
        }

        $interdit=array(" ","(",")",">", "<",  ":", "*", "/", "|", "?", '"', '<', '>',"#","$","%","£","@","À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");

        $results = Genius_Model_Search::get($search);

        $data=[];
            $i=0;
            foreach ($results[1] as $index => $result) {
                if($i < 6){

                    $tt = strtolower($result['title']);
                    $title = str_replace($interdit, "-", $tt);


                   $lnk = $title.'-'.$result['id_product'];
                    $data[]=[
                        'id'=>$result['id_product'],
                        "value" => $result['title'],
                        "label" => [
                            "id"=> $result['product_id'],
                            "label" => '',
                            'h' => PROJECTS.'/materiel/produit/id/'.$lnk.'.html'
                        ],
                        "desc" => $result['photocrh_cover_p'],
                    ];
                }

                $i++;
            }

        if(count($data) == 1){
            $photo = Genius_Model_Product::getProductImageCoverById($data[0]['id']);
            $xx = UPLOAD_URL.'images/'.$photo['path_folder'].'/'.$photo['filename'].'-small-'.$photo['id_image'].'.'.$photo['format'];
            $data[0]['desc']=$xx;
        }

        echo Zend_Json::encode($data);

        exit();
    }
}