<?php
	/**
	 * language pack
	 * @author Vincent Chabredier  (vincent [dot] chabredier [at] lepton [dot] fr)
	 * @link tim.lepton.fr
	 * @since 17/September/2010
	 *
	 */
	define('DATE_TIME_FORMAT', 'd/M/Y H:i:s');
	//Common
	//Menu

	define('MENU_SELECT', 'Sélectionner');
	define('MENU_DOWNLOAD', 'Télécharger');
	define('MENU_PREVIEW', 'Aperçu');
	define('MENU_RENAME', 'Renommer');
	define('MENU_EDIT', 'Editer');
	define('MENU_CUT', 'Couper');
	define('MENU_COPY', 'Copier');
	define('MENU_DELETE', 'Supprimer');
	define('MENU_PLAY', 'Lecture');
	define('MENU_PASTE', 'Coller');

	//Label
	//Top Action
	define('LBL_ACTION_REFRESH', 'Rafraichir');
	define('LBL_ACTION_DELETE', 'Effacer');
	define('LBL_ACTION_CUT', 'Couper');
	define('LBL_ACTION_COPY', 'Copier');
	define('LBL_ACTION_PASTE', 'Coller');
	define('LBL_ACTION_CLOSE', 'Fermer');
	define('LBL_ACTION_SELECT_ALL', 'Tout sélectionner');
		//File Listing
	define('LBL_NAME', 'Nom');
	define('LBL_SIZE', 'Taille');
	define('LBL_MODIFIED', 'Date de modification:');
		//File Information
	define('LBL_FILE_INFO', 'Informations du fichier');
	define('LBL_FILE_NAME', 'Nom:');
	define('LBL_FILE_CREATED', 'Création:');
	define('LBL_FILE_MODIFIED', 'Modification:');
	define('LBL_FILE_SIZE', 'Taille:');
	define('LBL_FILE_TYPE', 'Type:');
	define('LBL_FILE_WRITABLE', 'Écriture?');
 	define('LBL_FILE_READABLE', 'Lecture?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Informations du dossier');
	define('LBL_FOLDER_PATH', 'Dossier:');
	define('LBL_CURRENT_FOLDER_PATH', 'Emplacement actuel:');
	define('LBL_FOLDER_CREATED', 'Création:');
	define('LBL_FOLDER_MODIFIED', 'Modification:');
	define('LBL_FOLDER_SUDDIR', 'Sous-dossiers:');
	define('LBL_FOLDER_FIELS', 'Fichiers:');
	define('LBL_FOLDER_WRITABLE', 'Écriture?');
	define('LBL_FOLDER_READABLE', 'Lecture?');
	define('LBL_FOLDER_ROOT', 'Dossier racine');
		//Preview
	define('LBL_PREVIEW', 'Aperçu');
	define('LBL_CLICK_PREVIEW', 'Clickez ici pour un aperçu');
	//Buttons
	define('LBL_BTN_SELECT', 'Selectionner');
	define('LBL_BTN_CANCEL', 'Annuler');
	define('LBL_BTN_UPLOAD', 'Envoyer');
	define('LBL_BTN_CREATE', 'Créer');
	define('LBL_BTN_CLOSE', 'Fermer');
	define('LBL_BTN_NEW_FOLDER', 'Nouveau dossier');
	define('LBL_BTN_NEW_FILE', 'Nouveau fichier');
	define('LBL_BTN_EDIT_IMAGE', 'Éditer');
	define('LBL_BTN_VIEW', 'Sélectionner la vue');
	define('LBL_BTN_VIEW_TEXT', 'Texte');
	define('LBL_BTN_VIEW_DETAILS', 'Détails');
	define('LBL_BTN_VIEW_THUMBNAIL', 'Miniatures');
	define('LBL_BTN_VIEW_OPTIONS', 'Vue en:');
	//pagination
	define('PAGINATION_NEXT', 'Suivant');
	define('PAGINATION_PREVIOUS', 'Précédent');
	define('PAGINATION_LAST', 'Dernier');
	define('PAGINATION_FIRST', 'Premier');
	define('PAGINATION_ITEMS_PER_PAGE', 'Afficher %s éléments par page');
	define('PAGINATION_GO_PARENT', 'Aller au dossier parent');
	//System
	define ('SYS_DISABLED', 'Interdit : Le système est désactivé.');
	//Cut
	define ('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Aucun document(s) à couper sélectionné(s).');
	//Copy
	define ('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Aucun document(s) à copier sélectionné(s).');
	//Paste
	define ('ERR_NOT_DOC_SELECTED_FOR_PASTE', 'Aucun document(s) à coller sélectionné(s).');
	define ('WARNING_CUT_PASTE', 'Etes-vous sûr de déplacer les documents sélectionnés dans le dossier courant?');
	define ('WARNING_COPY_PASTE', 'Etes-vous sûr de copier les documents sélectionnés dans le dossier courant?');
	define ('ERR_NOT_DEST_FOLDER_SPECIFIED', 'Pas de dossier de destination spécifié.');
	define ('ERR_DEST_FOLDER_NOT_FOUND', 'Dossier de destination introuvable.');
	define ('ERR_DEST_FOLDER_NOT_ALLOWED', 'Vous n\\\'êtes pas autorisé à déplacer des fichiers dans ce dossier');
	define ('ERR_UNABLE_TO_MOVE_TO_SAME_DEST', 'Impossible de déplacer ce fichier (% s): le chemin d\\\'origine est la même que le chemin de destination.');
	define ('ERR_UNABLE_TO_MOVE_NOT_FOUND', 'Impossible de déplacer ce fichier (% s): le fichier d\\\'origine n\\\'existe pas.');
	define ('ERR_UNABLE_TO_MOVE_NOT_ALLOWED', 'Impossible de déplacer ce fichier (% s): l\\\'accès au fichier d\\\'origine est refusé.');

	define ('ERR_NOT_FILES_PASTED', 'Aucun fichier n\\\'a été collé.'); 
	
	//Search
	define ('LBL_SEARCH', 'Rechercher');
	define ('LBL_SEARCH_NAME', 'Nom du fichier (Complet/Partiel):');
	define ('LBL_SEARCH_FOLDER', 'Rechercher dans:');
	define ('LBL_SEARCH_QUICK', 'Recherche rapide');
	define ('LBL_SEARCH_MTIME', 'Date de modification du fichier (Interval):');
	define ('LBL_SEARCH_SIZE', 'Taille du fichier:');
	define ('LBL_SEARCH_ADV_OPTIONS', 'Options avancées');
	define ('LBL_SEARCH_FILE_TYPES', 'Types de fichiers:');
	define ('SEARCH_TYPE_EXE', 'Application');

	define ('SEARCH_TYPE_IMG', 'Image');
	define ('SEARCH_TYPE_ARCHIVE', 'Archive');
	define ('SEARCH_TYPE_HTML', 'html');
	define ('SEARCH_TYPE_VIDEO', 'Vidéo');
	define ('SEARCH_TYPE_MOVIE', 'Film');
	define ('SEARCH_TYPE_MUSIC', 'Music');
	define ('SEARCH_TYPE_FLASH', 'Flash');
	define ('SEARCH_TYPE_PPT', 'PowerPoint');
	define ('SEARCH_TYPE_DOC', 'Document');
	define ('SEARCH_TYPE_WORD' , 'Word');
	define ('SEARCH_TYPE_PDF', 'PDF');
	define ('SEARCH_TYPE_EXCEL', 'Excel');
	define ('SEARCH_TYPE_TEXT', 'Texte');
	define ('SEARCH_TYPE_UNKNOWN', 'inconnu');
	define ('SEARCH_TYPE_XML', 'XML');
	define ('SEARCH_ALL_FILE_TYPES', 'tous types de fichiers');
	define ('LBL_SEARCH_RECURSIVELY', 'Recherche récursive');
	define ('LBL_RECURSIVELY_YES', 'Oui');
	define ('LBL_RECURSIVELY_NO', 'Non');
	define ('BTN_SEARCH', 'Rechercher maintenant'); 

	//thickbox
	define ('THICKBOX_NEXT', 'Suivant>');
	define ('THICKBOX_PREVIOUS', '<Précédent');
	define ('THICKBOX_CLOSE', 'Fermer');

	//Calendar
	define ('CALENDAR_CLOSE', 'Fermer');
	define ('CALENDAR_CLEAR', 'Effacer');
	define ('CALENDAR_PREVIOUS', '<Précédent');
	define ('CALENDAR_NEXT', 'Suivant>');
	define ('CALENDAR_CURRENT', 'Aujourd\\\'hui');
	define ('CALENDAR_MON', 'Lun');
	define ('CALENDAR_TUE', 'Mar');
	define ('CALENDAR_WED', 'Mer');
	define ('CALENDAR_THU', 'Jeu');
	define ('CALENDAR_FRI', 'Ven');
	define ('CALENDAR_SAT', 'Sam');
	define ('CALENDAR_SUN', 'Dim');
	define ('CALENDAR_JAN', 'Jan');
	define ('CALENDAR_FEB', 'Fév');
	define ('CALENDAR_MAR', 'Mar');
	define ('CALENDAR_APR', 'Avr');
	define ('CALENDAR_MAY', 'Mai');
	define ('CALENDAR_JUN', 'Juin');
	define ('CALENDAR_JUL', 'Juil');
	define ('CALENDAR_AUG', 'Août');
	define ('CALENDAR_SEP' ,'Sep');
	define ('CALENDAR_OCT', 'Oct');
	define ('CALENDAR_NOV', 'Nov');
	define ('CALENDAR_DEC', 'Déc'); 

	//ERROR MESSAGES
		//deletion
	define ('ERR_NOT_FILE_SELECTED', 'Aucun fichier sélectionné pour être supprimé.');
	define ('ERR_NOT_DOC_SELECTED', 'Aucun document sélectionné pour être supprimé.');
	define ('ERR_DELTED_FAILED', 'Impossible de supprimer le(s) document(s) sélectionné(s).');
	define ('ERR_FOLDER_PATH_NOT_ALLOWED', 'Le chemin du dossier n\\\'est pas autorisé.');
		//class manager
	define ('ERR_FOLDER_NOT_FOUND', 'Impossible de trouver le dossier spécifique:');
		//rename
	define ('ERR_RENAME_FORMAT', 'S\\\'il vous plaît donnez un nom qui ne contiennent que des lettres, des chiffres, espace, trait d\\\'union et tiret bas.');
	define ('ERR_RENAME_EXISTS', 'S\\\'il vous plaît donnez un nom qui est unique dans le dossier.');
	define ('ERR_RENAME_FILE_NOT_EXISTS', 'Le fichier / dossier n\\\'existe pas.');
	define ('ERR_RENAME_FAILED', 'Impossible de renommer, s\\\'il vous plaît essayez à nouveau.');
	define ('ERR_RENAME_EMPTY', 'S\\\'il vous plaît donnez un nom.');
	define ('ERR_NO_CHANGES_MADE', 'Aucun changement n\\\'a été fait.');
	define ('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Vous n\\\'êtes pas autorisé à modifier le fichier pour une telle extension.');
		//folder creation
	define ('ERR_FOLDER_FORMAT', 'S\\\'il vous plaît donnez un nom qui ne contiennent que des lettres, des chiffres, espace, trait d\\\'union et tiret bas.');
	define ('ERR_FOLDER_EXISTS', 'S\\\'il vous plaît donnez un nom qui est unique dans le dossier.');
	define ('ERR_FOLDER_CREATION_FAILED', 'Impossible de créer le dossier, s\\\'il vous plaît essayez à nouveau.');
	define ('ERR_FOLDER_NAME_EMPTY' ,'S\\\'il vous plaît donnez un nom.');
	define ('FOLDER_FORM_TITLE', 'Nouveau dossier');
	define ('FOLDER_LBL_TITLE', 'Nom du dossier:');
	define ('FOLDER_LBL_CREATE', 'Creer');
	      //New File
	define('NEW_FILE_FORM_TITLE', 'Nouveau fichier');
	define('NEW_FILE_LBL_TITLE', 'Nom de fichier:');
	define('NEW_FILE_CREATE', 'Créer');
		//file upload
	define('ERR_FILE_NAME_FORMAT', 'S\\\'il vous plaît donnez un nom qui ne contiennent que des lettres, des chiffres, espace, trait d\\\'union et tiret bas.');
	define('ERR_FILE_NOT_UPLOADED', 'Aucun fichier n\\\'a été sélectionné pour l\\\'envoi.');
	define ('ERR_FILE_TYPE_NOT_ALLOWED', 'Vous n\\\'êtes pas autorisé à envoyer ce type de fichier.');
	define ('ERR_FILE_MOVE_FAILED', 'Impossible de déplacer le fichier.');
	define ('ERR_FILE_NOT_AVAILABLE', 'Le fichier n\\\'est pas disponible.');
	define ('ERROR_FILE_TOO_BID', 'Fichier trop volumineux. (max:% s)');
	define('FILE_FORM_TITLE', 'Envoi de fichier');
	define('FILE_LABEL_SELECT', 'Sélectionner un fichier');
	define('FILE_LBL_MORE', 'Ajouter un fichier à envoyer');
	define('FILE_CANCEL_UPLOAD', 'Annuler');
	define('FILE_LBL_UPLOAD', 'Envoyer');
	//file download
	define('ERR_DOWNLOAD_FILE_NOT_FOUND', 'Fichier introuvable.');
	//Rename
	define ('RENAME_FORM_TITLE', 'Renommer');
	define ('RENAME_NEW_NAME', 'Nouveau nom');
	define ('RENAME_LBL_RENAME', 'Renommer');

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Clickez pour accéder à ce dossier ...');
	define('TIP_DOC_RENAME', 'Double Cliquez pour modifier ...');
	define('TIP_FOLDER_GO_UP', 'Clickez pour se rendre au dossier parent ...');
	define('TIP_SELECT_ALL', 'Tout sélectionner');
	define('TIP_UNSELECT_ALL', 'Tout désélectionner');
	//WARNING
	define ('WARNING_DELETE', 'Etes Vous sur de vouloir effacer ce(s) document(s)?');
	define ('WARNING_IMAGE_EDIT', 'Veuillez s\\\'il vous plaît choisir une image à modifier.');
	define ('WARNING_NOT_FILE_EDIT', 'Veuillez s\\\'il vous plaît sélectionner un fichier à modifier.');
	define ('WARING_WINDOW_CLOSE', 'Etes-vous sûr de fermer la fenêtre?');
	//Preview
	define ('PREVIEW_NOT_PREVIEW', 'Pas de prévisualisation disponible.');
	define ('PREVIEW_OPEN_FAILED', 'Impossible d\\\'ouvrir le fichier.');
	define ('PREVIEW_IMAGE_LOAD_FAILED', 'Impossible de charger l\\\'image');
	//Login
	define ('LOGIN_PAGE_TITLE', 'Fenètre de connexion de l\\\'Ajax File Manager');
	define ('LOGIN_FORM_TITLE', 'Connexion');
	define ('LOGIN_USERNAME', 'Nom d\\\'utilisateur:');
	define ('LOGIN_PASSWORD', 'Mot de passe:');
	define('LOGIN_FAILED', 'Nom d\\\'utilisateur et/ou mot de passe invalide..');


	//88888888888   Below for Image Editor   888888888888888888888
		//Warning
		define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', 'Vous n\\\'avez pas apporté de modifications aux images.');

		//General
		define('IMG_GEN_IMG_NOT_EXISTS', 'L\\\'image n\\\'existe pas.');
		define('IMG_WARNING_LOST_CHANAGES', 'Toutes les modifications non enregistrées apportées à l\\\'image seront perdues, Etes-vous sûr de vouloir continuer?');
		define('IMG_WARNING_REST', 'Toutes les modifications non enregistrées apportées à l\\\'image seront perdues, vous êtes sûr de réinitialiser?');
		define('IMG_WARNING_EMPTY_RESET', 'Aucun changement n\\\'a été apporté à l\\\'image.');
		define('IMG_WARING_WIN_CLOSE', 'Êtes-vous sûr de vouloir fermer la fenêtre?');
		define('IMG_WARNING_UNDO', 'Êtes-vous sûr de vouloir restaurer l\\\'image à l\\\'état précédent?');
		define('IMG_WARING_FLIP_H', 'Êtes-vous sûr de faire vouloir basculer l\\\'image horizontalement?');
		define('IMG_WARING_FLIP_V', 'Êtes-vous sûr de faire vouloir basculer l\\\'image verticalement?');
		define('IMG_INFO', 'Informations');

		//Mode
			define('IMG_MODE_RESIZE', 'Redimensionner:');
			define('IMG_MODE_CROP', 'Rogner:');
			define('IMG_MODE_ROTATE', 'Rotation:');
			define('IMG_MODE_FLIP', 'Basculer:');
		//Button

			define('IMG_BTN_ROTATE_LEFT', '90&deg;Gauche');
			define('IMG_BTN_ROTATE_RIGHT', '90&deg;Droite');
			define('IMG_BTN_FLIP_H', 'Miroir Horizontal');
			define('IMG_BTN_FLIP_V', 'Miroir Vertical');
			define('IMG_BTN_RESET', 'Restaurer');
			define('IMG_BTN_UNDO', 'Annuler');
			define('IMG_BTN_SAVE', 'Enregistrer');
			define('IMG_BTN_CLOSE', 'Fermer');
			define('IMG_BTN_SAVE_AS', 'Enregistrer sous');
			define('IMG_BTN_CANCEL', 'Annuler');
		//Checkbox
			define('IMG_CHECKBOX_CONSTRAINT', 'Contrainte?');
		//Label
			define('IMG_LBL_WIDTH', 'Largeur:');
			define('IMG_LBL_HEIGHT', 'Hauteur:');
			define('IMG_LBL_X', 'X:');
			define('IMG_LBL_Y', 'Y:');
			define('IMG_LBL_RATIO', 'Ratio:');
			define('IMG_LBL_ANGLE', 'Angle:');
			define('IMG_LBL_NEW_NAME', 'Nouveau nom:');
			define('IMG_LBL_SAVE_AS', 'Enregister sous:');
			define('IMG_LBL_SAVE_TO', 'Enregister:');
			define('IMG_LBL_ROOT_FOLDER', 'Dossier racine');
		//Editor
		//Save as
		define('IMG_NEW_NAME_COMMENTS', 'Veuillez ne pas indiquer l\\\extension du fichier.');
		define('IMG_SAVE_AS_ERR_NAME_INVALID', 'S\\\'il vous plaît donnez un nom qui ne contiennent que des lettres, des chiffres, espace, trait d\\\'union et tiret bas.');
		define('IMG_SAVE_AS_NOT_FOLDER_SELECTED', 'Auncun de dossier de destination sélectionné.');
		define('IMG_SAVE_AS_FOLDER_NOT_FOUND', 'Le dossier de destination n\\\'existe pas.');
		define('IMG_SAVE_AS_NEW_IMAGE_EXISTS', 'Il existe une image avec le même nom.');

		//Save
		define('IMG_SAVE_EMPTY_PATH', 'Chemin de l\\\'image vide.');
		define('IMG_SAVE_NOT_EXISTS', 'L\\\'image n\\\'existe pas.');
		define('IMG_SAVE_PATH_DISALLOWED', 'Vous n\\\'êtes pas autorisé à accéder à ce fichier.');
		define('IMG_SAVE_UNKNOWN_MODE', 'Mode d\\\opération inattendu');
		define('IMG_SAVE_RESIZE_FAILED', 'Impossible de redimensionner l\\\'image.');
		define('IMG_SAVE_CROP_FAILED', 'Impossible de rogner l\\\'image.');
		define ('IMG_SAVE_FAILED', 'Impossible d\\\'enregistrer l\\\'image.');
		define ('IMG_SAVE_BACKUP_FAILED', 'Impossible de sauvegarder l\\\'image d\\\'origine.');
		define ('IMG_SAVE_ROTATE_FAILED', 'Impossible de faire pivoter l\\\'image.');
		define ('IMG_SAVE_FLIP_FAILED', 'Impossible de retourner l\\\'image.');
		define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Impossible d\\\'ouvrir l\\\'image depuis la session.');
		define('IMG_SAVE_IMG_OPEN_FAILED', 'Impossible d\\\'ouvrir l\\\'image');

		//UNDO
		define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'Aucun historique pour annuler.');
		define('IMG_UNDO_COPY_FAILED', 'Impossible de restaurer l\\\'image.');
		define('IMG_UNDO_DEL_FAILED', 'Impossible de supprimer l\\\'image de la session');

	//88888888888   Above for Image Editor   888888888888888888888

	//88888888888   Session   888888888888888888888
		define('SESSION_PERSONAL_DIR_NOT_FOUND', 'Impossible de trouver le dossier dédié aux sessions');
		define('SESSION_COUNTER_FILE_CREATE_FAILED', 'Impossible d\\\'ouvrir le fichier \\\'compteur\\\' de la session.');
		define('SESSION_COUNTER_FILE_WRITE_FAILED', 'Impossible d\\\'écrire le fichier \\\'compteur\\\' de la session.');
	//88888888888   Session   888888888888888888888

	//88888888888   Below for Text Editor   888888888888888888888
		define('TXT_FILE_NOT_FOUND', 'Le fichier n\\\'a été pas trouvé.');
		define('TXT_EXT_NOT_SELECTED', 'S\\\'il vous plaît sélectionnez l\\\'extension du fichier');
		define('TXT_DEST_FOLDER_NOT_SELECTED', 'S\\\'il vous plaît sélectionnez le dossier de destination');
		define('TXT_UNKNOWN_REQUEST', 'Requête inconnue.');
		define('TXT_DISALLOWED_EXT', 'Vous êtes autorisé à modifier / ajouter ce type de fichiers.');
		define('TXT_FILE_EXIST', 'Le fichier existe.');
		define('TXT_FILE_NOT_EXIST', 'Le fichier n\\\'existe pas.');
		define('TXT_CREATE_FAILED', 'Impossible de créer un nouveau fichier.');
		define('TXT_CONTENT_WRITE_FAILED', 'Impossible d\\\'écrire le contenu du fichier.');
		define('TXT_FILE_OPEN_FAILED', 'Impossible d\\\'ouvrir le fichier.');
		define('TXT_CONTENT_UPDATE_FAILED', 'Impossible de mettre à jour le contenu du fichier.');
		define('TXT_SAVE_AS_ERR_NAME_INVALID', 'S\\\'il vous plaît donnez un nom qui ne contiennent que des lettres, des chiffres, espace, trait d\\\'union et tiret bas.');
	//88888888888   Above for Text Editor   888888888888888888888


?>