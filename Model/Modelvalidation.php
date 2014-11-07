<?php
/**
 * Application: ModelOdtValidator / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */

App::uses('ModelOdtValidatorAppModel', 'ModelOdtValidator.Model');

class Modelvalidation extends ModelOdtValidatorAppModel {
    public $order = array('Modelvalidation.modeltype_id' => 'asc', 'Modelvalidation.modelsection_id' => 'asc', 'Modelvalidation.modelvariable_id' => 'asc');
    public $belongsTo = array(
        'Modeltype' => array(
            'className' => 'ModelOdtValidator.Modeltype',
            'foreignKey' => 'modeltype_id'
        ),
        'Modelvariable' => array(
            'className' => 'ModelOdtValidator.Modelvariable',
            'foreignKey' => 'modelvariable_id'
        ),
        'Modelsection' => array(
            'className' => 'ModelOdtValidator.Modelsection',
            'foreignKey' => 'modelsection_id'
        ),
    );

    /**
     * @var array Règles de validation du modèle
     */
    public $validate = array(
        'id' => array(
            'rule' => 'blank',
            'on' => 'create'
        ),
        'min' => array(
            'rule' => 'numeric',
            'allowEmpty' => true,
            'message' => 'Merci de soumettre le nombre minimum d\'occurences'
        ),
        'max' => array(
            'rule' => 'numeric',
            'allowEmpty' => true,
            'message' => 'Merci de soumettre le nombre maximum d\'occurences'
        ),
        'actif' => array(
            'rule' => array('boolean'),
            'message' => 'Valeur incorrecte pour actif'
        )
    );

    /**
     * Validation du modèle lors de l'ajout/modification d'un modèle
     */
    public function validate($file, $idType) {
        /*
         * Initialisations
         */
        //Variable de retour
        $validation = array(
            'warnings' => array(),
            'errors' => array()
        );
        //Initialisation de la librairie
        $this->PhpOdtApi = new PhpOdtApi;
        try {
            $this->PhpOdtApi->loadFromFile($file);
        } catch (Exception $e) {
            $validation['errors'][] = $e->getMessage();
            return $validation;
        }

        //Validation des sections
        $sections = $this->PhpOdtApi->getSections();
        foreach ($sections as $section) {
            //La section existe ?
            if ($this->Modelsection->findIdByName($section)) {
                if ($this->Modelsection->autoriseePourType($section, $idType)) {

                    $nombre = $this->PhpOdtApi->countSection($section);
                    if (!$this->Modelsection->checkMultiplicite($section, $idType, $nombre))
                        $validation['errors'][] = "La section '$section' n'est pas autorisée à apparaitre $nombre fois pour ce type de modèle";
//TODO valider la section parent
//                    $parent_section = $this->PhpOdtApi->getParentSection($section);
//                    if (!$this->Modelsection->autoriseePourSection($section, $parent_section))
//                        $validation['errors'][] = "La section '$section' n'est pas autorisée à être dans la section '$parent_section'";
                } else {
                    $validation['errors'][] = "La section '$section' n'est pas autorisée pour ce type de modèle";
                }
            } else {
                $validation['warnings'][] = "La section '$section' est inconnue";
            }
        }
        //Validation des variables
        $variables = $this->PhpOdtApi->getUserFields();
        foreach ($variables as $variable) {
            //La variable existe ?
            if ($this->Modelvariable->findIdByName($variable)) {

                if ($this->Modelvariable->autoriseePourType($variable, $idType)) {
                    $nombreTotal = $this->PhpOdtApi->countUserFields($variable);

                    if (!$this->Modelvariable->checkMultiplicite($variable, $idType, $nombreTotal))
                        $validation['errors'][] = "La variable '$variable' n'est pas autorisée à apparaitre $nombreTotal fois pour ce type de modèle";

                    $containers = $this->PhpOdtApi->getUserFieldSectionContainer($variable);
                    foreach ($containers as $section) {
                        $nombreEnSection = $this->PhpOdtApi->countUserFieldsInSection($variable, $section);
                        if ($this->Modelvariable->autoriseePourSection($variable, $idType, $section)) {
                            if (!$this->Modelvariable->checkMultipliciteDansSection($variable, $idType, $section, $nombreEnSection))
                                $validation['errors'][] = "La variable '$variable' n'est pas autorisée à apparaitre $nombreEnSection fois dans la section '$section' pour ce type de modèle";
                        } else
                            $validation['errors'][] = "La variable '$variable' n'est pas autorisée à apparaitre dans la section '$section' pour ce type de modèle";
                    }
                } else
                    $validation['errors'][] = "La variable '$variable' n'est pas autorisée pour ce type de modèle";
            } // fin : la variable existe
            elseif (Configure::read('APP_CONTAINER') == 'WEBDELIB') { // variable introuvable
                /*
                 * Model Infosupdef issue de WEBDELIB
                 */
                App::uses('Infosupdef', 'Model');
                $this->Infosupdef = new Infosupdef();
                //TODO Affiner le filtre pour la recherche d'infosup (bon type, ...)
                //Est-ce une info sup ?
                $count = $this->Infosupdef->find('count', array(
                    'conditions' => array('code' => $variable)
                ));
                if (empty($count)) {
                    $validation['warnings'][] = "La variable '$variable' est inconnue";
                }
            } // fin : variable introuvable
        }

        foreach ($this->PhpOdtApi->getVariablesDeclared() as $variable) {
            $validation['warnings'][] = "La variable '$variable' est déclarée comme 'variable' et non comme 'champ utilisateur'";
        }

//        foreach ($this->PhpOdtApi->getUserFieldsNotUsed() as $notUsed) {
//            $validation['warnings'][] = "La variable '$notUsed' est déclarée mais n'est pas utilisée";
//        }

        return $validation;
    }

    public function getXmlFromFile($path) {
        $this->PhpOdtApi = new PhpOdtApi();
        $file = new File($path, false);
        if ($file->exists()) {
            $this->PhpOdtApi->loadFromFile($file->path);
            return $this->PhpOdtApi->content;
        } else {
            throw new Exception('Le fichier modèle n\'existe pas');
        }
    }

}
