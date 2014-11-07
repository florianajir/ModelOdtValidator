<?php
/**
 * Application: webdelib / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */

App::uses('PhpOdtApi', 'ModelOdtValidator.Lib');
App::uses('File', 'Utility');

class ModelvalidationsController extends ModelOdtValidatorAppController {
    public $demandeDroit = array('index');

    public function edit($id = null) {
        if (!empty($id)) {
            if (empty($this->request->data)) {
                $this->request->data = $this->Modelvalidation->find('first', array(
                    'recursive' => 1,
                    'conditions' => array(
                        'Modelvalidation.id' => $id,
                    )
                ));
            } else {
                if (empty($this->request->data['Modelvalidation']['modeltype_id']))
                    $this->request->data['Modelvalidation']['modeltype_id'] = 1;
                if (empty($this->request->data['Modelvalidation']['modelsection_id']))
                    $this->request->data['Modelvalidation']['modelsection_id'] = 1;
                if (empty($this->request->data['Modelvalidation']['modelvariable_id']))
                    $this->request->data['Modelvalidation']['modelvariable_id'] = null;
                $exist = $this->Modelvalidation->find('count', array(
                    'conditions' => array(
                        'Modelvalidation.modeltype_id' => $this->request->data['Modelvalidation']['modeltype_id'],
                        'Modelvalidation.modelsection_id' => $this->request->data['Modelvalidation']['modelsection_id'],
                        'Modelvalidation.modelvariable_id' => $this->request->data['Modelvalidation']['modelvariable_id'],
                    )
                ));

                if (empty($exist)) {
                    $this->Modelvalidation->id = $id;
                    if ($this->Modelvalidation->save($this->request->data)) {
                        $this->Session->setFlash("Règle enregistrée avec succès", 'growl', array('type' => 'success'));
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash("Erreur lors de l'enregistrement de la règle", 'growl', array('type' => 'error'));
                    }
                } else {
                    $this->Session->setFlash("Une règle existe déjà pour cette combinaison type/section/variable", 'growl', array('type' => 'erreur'));
                }

            }
            $this->set('variables', $this->Modelvalidation->Modelvariable->find('list'));
            $this->set('sections', $this->Modelvalidation->Modelsection->find('list', array(
                'conditions' => array('Modelsection.id !=' => 1)
            )));
            $this->set('types', $this->Modelvalidation->Modeltype->find('list', array(
                'conditions' => array('Modeltype.id !=' => 1)
            )));
        } else {
            $this->Session->setFlash("Veuillez spécifier la règle à modifier", 'growl', array('type' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function add() {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['Modelvalidation']['modeltype_id']))
                $this->request->data['Modelvalidation']['modeltype_id'] = 1;
            if (empty($this->request->data['Modelvalidation']['modelsection_id']))
                $this->request->data['Modelvalidation']['modelsection_id'] = 1;
            if (empty($this->request->data['Modelvalidation']['modelvariable_id']))
                $this->request->data['Modelvalidation']['modelvariable_id'] = null;

            $exist = $this->Modelvalidation->find('count', array(
                'conditions' => array(
                    'Modelvalidation.modeltype_id' => $this->request->data['Modelvalidation']['modeltype_id'],
                    'Modelvalidation.modelsection_id' => $this->request->data['Modelvalidation']['modelsection_id'],
                    'Modelvalidation.modelvariable_id' => $this->request->data['Modelvalidation']['modelvariable_id'],
                )
            ));
            if (empty($exist)) {
                $this->Modelvalidation->create();
                if ($this->Modelvalidation->save($this->request->data)) {
                    $this->Session->setFlash("Règle créée avec succès", 'growl', array('type' => 'success'));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash("Erreur lors de l'enregistrement de la règle.", 'growl', array('type' => 'error'));
                }
            } else {
                $this->Session->setFlash("Une règle existe déjà pour cette combinaison type/section/variable.", 'growl', array('type' => 'erreur'));
            }
        }
        $this->set('variables', $this->Modelvalidation->Modelvariable->find('list'));
        $this->set('sections', $this->Modelvalidation->Modelsection->find('list', array(
            'conditions' => array('Modelsection.id !=' => 1)
        )));
        $this->set('types', $this->Modelvalidation->Modeltype->find('list', array(
            'conditions' => array('Modeltype.id !=' => 1)
        )));
        $this->render('edit');
    }

    public function index() {
        $conditions = array();
        if (!empty($this->request->data['Filtre'])) {
            if (!empty($this->request->data['Filtre']['modeltype_id']))
                $conditions['Modelvalidation.modeltype_id'][] = $this->request->data['Filtre']['modeltype_id'];
            if (!empty($this->request->data['Filtre']['modelsection_id']))
                $conditions['Modelvalidation.modelsection_id'][] = $this->request->data['Filtre']['modelsection_id'];
            if (!empty($this->request->data['Filtre']['modelvariable_id']))
                $conditions['Modelvalidation.modelvariable_id'][] = $this->request->data['Filtre']['modelvariable_id'];
        }
        $datas = $this->Modelvalidation->find('all', array(
            'recursive' => 1,
            'conditions' => $conditions,
            'order' => 'modeltype_id ASC'
        ));

        $this->set('datas', $datas);

        $this->set('variables', $this->Modelvalidation->Modelvariable->find('list'));
        $this->set('sections', $this->Modelvalidation->Modelsection->find('list'));
        $this->set('types', $this->Modelvalidation->Modeltype->find('list'));
    }

    public function delete($id) {
        if ($this->Modelvalidation->delete($id, false)) {
            $this->Session->setFlash("La règle $id à été supprimé", 'growl');
        } else {
            $this->Session->setFlash("Impossible de supprimer la règle de validation", 'growl', array('type' => 'error'));
        }
        return $this->redirect($this->referer());
    }

    /**
     * Permet la création de plusieurs règles de validation
     * en fonction de variables/sections/types/min/max
     * et affiche le code sql correspondant
     */
    public function generate() {
        if (!empty($this->request->data['Modelvalidation']['sql_commands'])) {
            $commands = trim($this->request->data['Modelvalidation']['sql_commands']);
            $querys = explode("\n", $commands);
            try {
                $this->Modelvalidation->begin();
                foreach ($querys as $query){
                    $this->Modelvalidation->query(trim($query));
                }
                $this->Modelvalidation->commit();
                $this->Session->setFlash("Les règles de validation ont été ajoutées dans la base de données", 'growl');
            } catch (Exception $e) {
                $this->Modelvalidation->rollback();
                $this->Session->setFlash($e->getMessage(), 'growl', array('type' => 'error'));
            }
        }
        $this->set('variables', $this->Modelvalidation->Modelvariable->find('list'));
        $this->set('sections', $this->Modelvalidation->Modelsection->find('list', array()));
        $this->set('types', $this->Modelvalidation->Modeltype->find('list', array(
            'conditions' => array('Modeltype.id !=' => 1)
        )));
    }

}