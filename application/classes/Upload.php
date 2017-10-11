<?php

class Genius_Class_Upload {

    function photos($name, $filename, $path, $size, $id_membre) {
        $valid_formats = array("JPG", "JPEG", "jpg", "png", "gif", "bmp");
        list($txt, $ext) = explode(".", $name);
        if (in_array($ext, $valid_formats)) {
            if ($size < (1024 * 1024 * 10)) {
                $actual_image_name = $filename . "." . $ext;
                $tmp = $_FILES['photo']['tmp_name'];
                if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                    $datas = array('photo' => $actual_image_name);
                    Genius_Model_Global::update(TABLE_PREFIX . 'membres_documents', $datas, "id_membre='$id_membre'");
                } else
                    echo "failed";
            } else
                echo "Image file size max 1 MB";
        } else
            echo "Invalid file format..";
    }

    function immobiliers($column, $name, $filename, $path, $size, $id_immobilier) {
        $valid_formats = array("pdf", "doc", "docx", "PDF", "DOC", "DOCX");
        list($txt, $ext) = explode(".", $name);
        if (in_array($ext, $valid_formats)) {
            if ($size < (1024 * 1024 * 10)) {
                $actual_file_name = $filename . "." . $ext;
                $tmp = $_FILES[$column]['tmp_name'];
                if (move_uploaded_file($tmp, $path . $actual_file_name)) {
                    $datas = array($column => $actual_file_name);
                    Genius_Model_Global::update(TABLE_PREFIX . 'immobiliers', $datas, "id='$id_immobilier'");
                } else
                    echo "failed";
            } else
                echo "Image file size max 1 MB";
        } else
            echo "Invalid file format..";
    }

    function docs($name, $filename, $path, $size, $form_name, $id_membre) {
        list($txt, $ext) = explode(".", $name);
        if ($size < (1024 * 1024 * 100)) {
            $actual_file_name = $filename . "." . $ext;
            $tmp = $_FILES[$form_name]['tmp_name'];
            if (move_uploaded_file($tmp, $path . $actual_file_name)) {
                $datas = array($form_name => $actual_file_name);
                Genius_Model_Global::update(TABLE_PREFIX . 'membres_documents', $datas, "id_membre='$id_membre'");
            } else
                echo "failed";
        } else
            echo "Image file size max 1 MB";
    }

    public static function logo($tab) {
        $valid_formats = array("JPG", "JPEG", "PNG", "jpg", "png", "gif", "bmp");
        $id = $tab['id'];
        list($txt, $ext) = explode(".", $tab['name']);
        if (in_array($ext, $valid_formats)) {
            if ($tab['size'] < (1024 * 1024 * 10)) {
                if (move_uploaded_file($tab['tmp'], $tab['path'] . $tab['name'])) {
                    $datas = array('logo_marque' => $tab['name']);
                    Genius_Model_Global::update(TABLE_PREFIX . $tab['champ'], $datas, "id=$id");
                } else
                    echo "failed";
            } else
                echo "Image file size max 1 MB";
        } else
            echo "Invalid file format..";
    }

}

?>