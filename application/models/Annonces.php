<?php

class Genius_Model_Annonces {

    public static function getRecentProperties() {
        global $db;
        $sql = "
		SELECT 
			a.*,
			ap.*,
			app.*,
			app.photo as photo_principale,
			ca.nom as nom_categorie,
			ta.nom as type_annonce,
			d.nom as nom_departement,
			a.id as id_annonce
		FROM " . TABLE_PREFIX . "annonces a
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces ap ON ap.id_annonce=a.id
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces_photos app ON app.id_annonce_piece=ap.id
		LEFT JOIN " . TABLE_PREFIX . "categories_annonces ca ON ca.id=a.id_category_annonce
		LEFT JOIN " . TABLE_PREFIX . "types_annonces ta ON ta.id=a.id_type_annonce
		LEFT JOIN " . TABLE_PREFIX . "departements d ON d.id=a.id_departement
		WHERE
		a.id is not null
		GROUP BY a.id
		LIMIT 0,4 
		";
        $data = $db->fetchAll($sql);
        return $data;
    }
	
	public static function getRecentPropertiesNotId($id_annonce) {
        global $db;
        $sql = "
		SELECT 
			a.*,
			ap.*,
			app.*,
			app.photo as photo_principale,
			ca.nom as nom_categorie,
			ta.nom as type_annonce,
			d.nom as nom_departement,
			a.id as id_annonce
		FROM " . TABLE_PREFIX . "annonces a
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces ap ON ap.id_annonce=a.id
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces_photos app ON app.id_annonce_piece=ap.id
		LEFT JOIN " . TABLE_PREFIX . "categories_annonces ca ON ca.id=a.id_category_annonce
		LEFT JOIN " . TABLE_PREFIX . "types_annonces ta ON ta.id=a.id_type_annonce
		LEFT JOIN " . TABLE_PREFIX . "departements d ON d.id=a.id_departement
		WHERE
		a.id is not null
                AND 
                a.id <> $id_annonce
		GROUP BY a.id
		LIMIT 0,4 
		";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getRecentPropertiesById($id_annonce) {
        global $db;
        $sql = "
		SELECT 
			a.*,
			ap.*,
			app.*,
			app.photo as photo_principale,
			ca.nom as nom_categorie,
			ta.nom as type_annonce,
			d.nom as nom_departement,
			a.id as id_annonce,
			v.*
		FROM " . TABLE_PREFIX . "annonces a
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces ap ON ap.id_annonce=a.id
		LEFT JOIN " . TABLE_PREFIX . "annonces_pieces_photos app ON app.id_annonce_piece=ap.id
		LEFT JOIN " . TABLE_PREFIX . "categories_annonces ca ON ca.id=a.id_category_annonce
		LEFT JOIN " . TABLE_PREFIX . "types_annonces ta ON ta.id=a.id_type_annonce
		LEFT JOIN " . TABLE_PREFIX . "departements d ON d.id=a.id_departement
		LEFT JOIN " . TABLE_PREFIX . "vendeurs v ON v.id=a.id_vendeur
		WHERE
		a.id is not null
                AND
                a.id = $id_annonce
		GROUP BY a.id
		";
        $data = $db->fetchAll($sql);
        return array_shift($data);
    }

    public static function getAllProperties($searchString) {
        global $db;
        $sql = "
            SELECT 
             a.*,
             ap.*,
             app.*,
             app.photo as photo_principale,
             ca.nom as nom_categorie,
             ta.nom as type_annonce,
             d.nom as nom_departement,
             a.id as id_annonce
            FROM " . TABLE_PREFIX . "annonces a
            LEFT JOIN " . TABLE_PREFIX . "annonces_pieces ap ON ap.id_annonce=a.id
            LEFT JOIN " . TABLE_PREFIX . "annonces_pieces_photos app ON app.id_annonce_piece=ap.id
            LEFT JOIN " . TABLE_PREFIX . "categories_annonces ca ON ca.id=a.id_category_annonce
            LEFT JOIN " . TABLE_PREFIX . "types_annonces ta ON ta.id=a.id_type_annonce
            LEFT JOIN " . TABLE_PREFIX . "departements d ON d.id=a.id_departement
            WHERE
            a.id is not null
            $searchString
            GROUP BY a.id
            ";
        $data = $db->fetchAll($sql);
        return $data;
    }
	
	public static function getPhotosPiecesByAnnonce($id_annonce) {
        global $db;
        $sql = "
            SELECT 
                APP.photo as photo
            FROM
                " . TABLE_PREFIX . "annonces_pieces_photos APP
                    LEFT JOIN " . TABLE_PREFIX . "annonces_pieces AP ON AP.id = APP.id_annonce_piece
                        LEFT JOIN " . TABLE_PREFIX . "annonces A ON A.id = AP.id_annonce
                            WHERE
                            A.id = $id_annonce
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

}

?>