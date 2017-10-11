<?php
class Genius_Class_Folder {

    public function listDirectory($path)
    {
        global $dirs_to_translate;
        $handle = @opendir($path);
     
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') continue;
     
            if ( is_dir("$path/$file")) {
                // echo "$path/$file<br />";
                $dirs_to_translate[] = $path.'/'.$file;
                Genius_Class_Folder::listDirectory("$path/$file");
            } else {
                // echo "$path/$file<br />";
            }
        }
     
        closedir($handle);
    }

}