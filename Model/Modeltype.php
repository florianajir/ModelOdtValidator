<?php
/**
 * Application ModelOdtValidator / Adullact
 * Date: 20/11/13
 * @author Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

App::uses('ModelOdtValidatorAppModel','ModelOdtValidator.Model');
class Modeltype extends ModelOdtValidatorAppModel{

    public $hasMany = array(
        'Modeltemplate' => array(
            'className' => 'ModelOdtValidator.Modeltemplate',
            'foreignKey' => 'modeltype_id',
            'order' => 'Modeltemplate.created DESC',
            'dependent' => true
        ),
        'ModelOdtValidator.Modelvalidation'
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
     * Trouve l'id d'un type grace à son nom
     * @param string $name nom du type
     * @return integer id du type
     */
    public function findIdByName($name){
        $modeltype = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('Modeltype.id'),
            'conditions' => array('Modeltype.name' => $name)
        ));
        if (empty($modeltype['Modeltype']['id'])) return false;
        return $modeltype['Modeltype']['id'];
    }

    /**
     * Trouve le nom d'un type grace à son id
     * @param integer $id id du type
     * @return string nom du type
     */
    public function findNameById($id){
        $modeltype = $this->find('first', array(
            'recursive' => -1,
            'fields' => array('Modeltype.name'),
            'conditions' => array('Modeltype.id' => $id)
        ));
        if (empty($modeltype['Modeltype']['name'])) return false;
        return $modeltype['Modeltype']['name'];
    }
}
