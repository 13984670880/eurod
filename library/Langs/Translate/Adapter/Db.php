<?php
/*
 * @see Zend_Translate_Adapter
 */
require_once 'Zend/Translate/Adapter.php';

/*
 * Langs_Translate_Adapter_Db
 * Translation adapter for using a database back-end
 *
 */
class Langs_Translate_Adapter_Db extends Zend_Translate_Adapter
{ 
	/*
	 * Loads the translation data into the Adapter
	 * @param string | array $data  - DB related settings
	 *								- dbAdapter: adapter to connect with DB
	 *								- tableName: storage of the translations
	 *								- options: filter on the translations
	 * @param string $language_abbreviation - The language_abbreviation this translation is for
	 * @param array $options - general adapter options
	 */
	protected function _loadTranslationData($data, $language_abbreviation, array $options = array())
	{ 
		// we create a select statement first
		$select = $data['dbAdapter']->select();
		$select->from($data['tableName'], array($data['keyField'], $data['valueField'])) 
			   ->where('language_abbreviation = ?', $language_abbreviation);
		$stmt = $select->query();

		// we store our whole translation into $trData
		$trData = $stmt->fetchAll();

		// we create a schema to generate a Zend_Translation_Array type
		$trScheme = array();
		if(!empty($trData)){ 
			foreach ($trData as $trEl) {
			 	$trScheme[$trEl[$data['keyField']]] = $trEl[$data['valueField']];
			} 
		}	

		// we put everything back into data so it can be handled properly by the translation adapter
		$data = $trScheme;

		// we need to check if we don't call a clean up routine
		$options = array_merge($this->_options, $options);
		if(($options['clear']==true) || !isset($this->_translate[$language_abbreviation])){ 
			$this->_translate[$language_abbreviation] = array(); 
		}

		// now we merge our data back to the translate adapter
		$this->_translate[$language_abbreviation] = array_merge($this->_translate[$language_abbreviation], $data);

		return $this->_translate;

	}

	/*
	 * returns the adapters name
	 *
	 * @return string
	 */
	public function toString()
	{ 
		return "Db"; 
	}

}