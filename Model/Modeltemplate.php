<?php
/**
 * Application: ModelOdtValidator / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */

App::uses('ModelOdtValidatorAppModel','ModelOdtValidator.Model');

/**
 * @property Fido Fido
 * @property Modeltype Modeltype
 */
class Modeltemplate extends ModelOdtValidatorAppModel
{
    public $recursive = 0;
    //Relations
    public $belongsTo = array(
        'Modeltype' => array(
            'className' => 'ModelOdtValidator.Modeltype',
            'foreignKey' => 'modeltype_id'
        )
    );

    //Validations
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Veuillez attribuer un nom au modèle.'
        ),
        'modeltype_id' => array(
            array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Veuillez attribuer un type au modèle.'
            ),
            array(
                'rule' => 'numeric',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Veuillez sélectionner un type valide.'
            )
        ),
        'fileupload' => array(
            'rule' => 'checkFormat',
            'required' => false,
            'allowEmpty' => false,
            'message' => 'Erreur de fichier. Document ODT attendu.'
        )
    );

    /**
     * Validation du format du fichier par FIDO
     */
    public function checkFormat($data)
    {
        $data = array_shift($data);
        if ($data['size'] == 0 || $data['error'] != 0) {
            return false;
        }
        $this->Fido = new FidoComponent();
        $allowed = $this->Fido->checkFile($data['tmp_name']);
        $results = $this->Fido->lastResults;

        return ($allowed && $results['extension'] == 'odt');
    }

    /**
     * récupère tous les modèles d'un type donné
     * @param string $type
     * @param bool $includeToutesEditions inclure les modèles de type "Toutes éditions"
     * @return array
     */
    public function getModels($type, $includeToutesEditions = true){
        $types = array($type);
        if ($includeToutesEditions)
            $types[] = MODEL_TYPE_TOUTES;

        return $this->find('list', array(
            'conditions' => array(
                'modeltype_id' => $types
            )
        ));
    }

    /**
     * Récupère tous les modèles associés aux types voulus
     * @param array $types types de modèles à récupérer
     * @return array
     */
    public function getModelsByTypes($types){
        return $this->find('list', array(
            'conditions' => array(
                'modeltype_id' => $types
            )
        ));
    }

    /**
     * @param string $content contenu du document odt
     * @return File instance de la classe File
     */
    public function createTmpFile($content){
        //Création fichier temporaire
        App::uses('Folder', 'Utility');
        $folder = new Folder(TMP . 'files' . DS . 'modelodtvalidator', true, 0777);
        $outputDir = tempnam($folder->path, '');
        unlink($outputDir);
        $file = new File($outputDir . '.odt', true, 0755);
        $file->write($content);
        return $file;
    }

    /**
     * @param string $content
     * @param int $modeltype_id
     * @return mixed
     */
    public function validerFlux($content, $modeltype_id){
        $file = $this->createTmpFile($content);
        //Analyse du document
        $validation = $this->Modeltype->Modelvalidation->validate($file->path, $modeltype_id);
        //Suppression du fichier
        $file->delete();
        return $validation;
    }

}
