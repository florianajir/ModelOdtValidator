<?php
/**
 * Application Webdelib / Adullact
 * Date: 20/11/13
 * @author Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

App::uses('ModelOdtValidatorAppModel', 'ModelOdtValidator.Model');

class Modelvariable extends ModelOdtValidatorAppModel {

    public $hasMany = array('ModelOdtValidator.Modelvalidation');
    public $order = "name";

    /**
     * @var array Règles de validation des enregistrements
     */
    public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'allowEmpty' => false,
                'message' => 'Ce nom est déjà utilisé.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'Le nom ne doit pas dépasser 255 caractères.'
            )
        ),
        'description' => array(
            'rule' => array('maxLength', 255),
            'message' => 'La description ne doit pas dépasser 255 caractères.'
        )
    );

    /**
     * Trouve l'id d'une variable par son nom
     * @param string $name nom de la variable
     * @return integer id de la variable
     */
    public function findIdByName($name) {
        $modelvariable = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array('Modelvariable.name' => $name)
        ));
        if (empty($modelvariable['Modelvariable']['id'])) return false;
        return $modelvariable['Modelvariable']['id'];
    }

    /**
     * La variable est elle autorisée pour le type
     * @param string $variable
     * @param integer $idType
     * @return bool
     */
    public function autoriseePourType($variable, $idType) {
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $variableId = $this->findIdByName($variable);
        if (!$variableId) return true;

        $exist = $this->Modelvalidation->find('count', array(
            'conditions' => array(
                'modelvariable_id' => $variableId,
                'modeltype_id' => $idType
            )
        ));

        return (!empty($exist));
    }

    /**
     * La variable est elle autorisée dans la section pour le type (ou le document)
     * @param string $variable
     * @param integer $idType
     * @param string $section
     * @return bool
     */
    public function autoriseePourSection($variable, $idType, $section) {
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $variableId = $this->findIdByName($variable);
        if (!$variableId) return true;

        $sectionId = $this->Modelvalidation->Modelsection->findIdByName($section);
        if (!$sectionId) return true;

        $exist = $this->Modelvalidation->find('count', array(
            'conditions' => array(
                'modelvariable_id' => $variableId,
                'modelsection_id' => array($sectionId, MODEL_SECTION_DOCUMENT),
                'modeltype_id' => $idType
            )
        ));

        return (!empty($exist));
    }

    /**
     * @param string $variable nom de la variable
     * @param integer $idType identifiant du type de model
     * @param string $nombre a comparer avec min et max
     * @return bool le nombre est compris entre min et max
     */
    public function checkMultiplicite($variable, $idType, $nombre) {
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $variableId = $this->findIdByName($variable);
        if (!$variableId) return true;

        $record = $this->Modelvalidation->find('first', array(
            'fields' => array('min', 'max'),
            'conditions' => array(
                'modelvariable_id' => $variableId,
                'modeltype_id' => $idType
            )
        ));

        return ($record['Modelvalidation']['min'] <= $nombre || empty($record['Modelvalidation']['min']))
        && ($nombre <= $record['Modelvalidation']['max'] || empty($record['Modelvalidation']['max']));
    }

    /**
     * @param string $variable nom de la variable
     * @param integer $idType identifiant du type de model
     * @param string $section nom de la section
     * @param integer $nombre a comparer avec min et max
     * @return bool le nombre est compris entre min et max
     */
    public function checkMultipliciteDansSection($variable, $idType, $section, $nombre) {
        //Type "Toutes Editions" : pas d'erreur
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $variableId = $this->findIdByName($variable);
        //La variable n'est pas répertoriée : pas d'erreur
        if (!$variableId) return true;
        $sectionId = $this->Modelvalidation->Modelsection->findIdByName($section);
        //La section n'est pas répertoriée : pas d'erreur
        if (!$sectionId) return true;

        $record = $this->Modelvalidation->find('first', array(
            'fields' => array('min', 'max'),
            'conditions' => array(
                'modelvariable_id' => $variableId,
                'modelsection_id' => $sectionId,
                'modeltype_id' => $idType
            )
        ));

        if (empty($record))
            $record = $this->Modelvalidation->find('first', array(
                'fields' => array('min', 'max'),
                'conditions' => array(
                    'modelvariable_id' => $variableId,
                    'modelsection_id' => MODEL_SECTION_DOCUMENT,
                    'modeltype_id' => $idType
                )
            ));

        if (empty($record))
            return false;

        return ($record['Modelvalidation']['min'] <= $nombre || empty($record['Modelvalidation']['min']))
        && ($nombre <= $record['Modelvalidation']['max'] || empty($record['Modelvalidation']['max']));
    }

}
