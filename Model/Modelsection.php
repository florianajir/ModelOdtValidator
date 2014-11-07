<?php
/**
 * Application ModelOdtValidator / Adullact
 * Date: 20/11/13
 * @author Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

App::uses('ModelOdtValidatorAppModel','ModelOdtValidator.Model');
class Modelsection extends ModelOdtValidatorAppModel
{

    /*
     * Relations entre modèles
     */
    public $hasMany = array(
        'Modelvalidation' => array(
            'className' => 'ModelOdtValidator.Modelvalidation'
        )
    );
    public $belongsTo = array(
        'Parent' => array(
            'className' => 'ModelOdtValidator.Modelsection',
            'foreignKey' => 'parent_id'
        )
    );

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
     * Trouve l'id d'une section par son nom
     * @param string $name nom de la section
     * @return integer id de la section
     */
    public function findIdByName($name)
    {
        $modelsection = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('id'),
            'conditions' => array('Modelsection.name' => $name)
        ));
        if (empty($modelsection)) return false;
        return $modelsection['Modelsection']['id'];
    }

    /**
     * la section est elle autorisée à exister dans ce type de modèle
     * @param string $section nom de la section
     * @param integer $idType identifiant du type de modèle
     * @return bool
     */
    public function autoriseePourType($section, $idType)
    {
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $sectionId = $this->findIdByName($section);
        if (!$sectionId) return true;

        $exist = $this->Modelvalidation->find('count', array(
            'conditions' => array(
                'modelvariable_id' => null,
                'modelsection_id' => $sectionId,
                'modeltype_id' => $idType
            )
        ));

        return (!empty($exist));
    }

    /**
     * la section apparait un nombre autorisé de fois dans le document
     * @param string $section nom de la section
     * @param integer $idType identifiant du type de modèle
     * @param integer $nombre nombre de fois que la section apparait
     * @return bool
     */
    public function checkMultiplicite($section, $idType, $nombre)
    {
        if ($idType == MODEL_TYPE_TOUTES) return true;
        $sectionId = $this->findIdByName($section);
        if (!$sectionId) return true;

        $record = $this->Modelvalidation->find('first', array(
            'fields' => array('Modelvalidation.min', 'Modelvalidation.max'),
            'conditions' => array(
                'Modelvalidation.modelvariable_id' => null,
                'Modelvalidation.modelsection_id' => $sectionId,
                'Modelvalidation.modeltype_id' => $idType
            )
        ));
        if (empty($record)) return true;

        return ($record['Modelvalidation']['min'] <= $nombre || empty($record['Modelvalidation']['min']))
        && ($nombre <= $record['Modelvalidation']['max'] || empty($record['Modelvalidation']['max']));
    }


    /**
     * la section est elle autorisée à exister dans cette section parent
     * @param string $section nom de la section
     * @param integer $parent nom de la section parent
     * @return bool
     */
    public function autoriseePourSection($section, $parent)
    {
        $parentId = $this->findIdByName($parent);
        if (!$parentId) return true;

        $exist = $this->find('count', array(
            'conditions' => array(
                'name' => $section,
                'parent_id' => $parentId
            )
        ));

        return (!empty($exist));
    }

}
