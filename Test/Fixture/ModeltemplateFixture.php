<?php
/**
* Code source de la classe ActionFixture.
*
* PHP 5.3
*
* @package app.Test.Fixture
* @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
*/

/**
* Classe ActionFixture.
*
* @package app.Test.Fixture
*/

class ModeltemplateFixture extends CakeTestFixture 
{
    public $import = array(/* 'model' => 'Modeltemplate',*/'table' => 'modeltemplates', 'records' => false);
    
    /**
    * Définition des enregistrements.
    *
    * @var array
    */
    public function init() {
        $this->records = array(
		array(
			'id' => 1,
                        'name' => 'Projet',
                        'content' => '',
                        'filename' => 'Modeltemplate1.odt',
                        'filesize' => 17520,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                        'modeltype_id' => 1,
		)
	);
        parent::init();
    }
}

?>