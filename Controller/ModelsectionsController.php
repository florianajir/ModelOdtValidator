<?php

/**
 * Application: webdelib / Adullact.
 * Date: 26/02/2013
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
class ModelsectionsController extends ModelOdtValidatorAppController {

    public function edit($id = null) {
        if (!empty($id)) {
            if (empty($this->request->data)) {
                $this->request->data = $this->Modelsection->find('first', array('conditions' => array('Modelsection.id' => $id)));
            } else {
                $conditions = array(
                    'NOT' => array('Modelsection.id' => $id),
                    'Modelsection.name' => $this->request->data['Modelsection']['name'],
                );
                if (!$this->Modelsection->hasAny($conditions)) {
                    $this->Modelsection->id = $id;
                    if (empty($this->request->data['Modelsection']['parent_id']))
                        $this->request->data['Modelsection']['parent_id'] = 1;
                    if ($this->Modelsection->save($this->request->data)) {
                        $this->Session->setFlash("Section enregistrée avec succès.", 'growl', array('type' => 'success'));
                        return $this->redirect($this->previous);
                    } else {
                        $this->Session->setFlash("Erreur lors de l'enregistrement de la section.", 'growl', array('type' => 'error'));
                    }
                } else {
                    $this->Session->setFlash("Une section de ce nom existe déjà.", 'growl', array('type' => 'erreur'));
                }
            }
        } else {
            $this->Session->setFlash("Veuillez spécifier la section à modifier", 'growl');
            return $this->redirect(array('action' => 'index'));
        }
        $parents = $this->Modelsection->find('list', array(
            'order' => array('name ASC'),
            'conditions' => array('not'=> array('id' => array($id, 1)))
        ));
        $this->set('parents', $parents);
    }

    public function add() {
        if (!empty($this->request->data)) {
            $conditions = array(
                'Modelsection.name' => $this->request->data['Modelsection']['name'],
            );
            if (!$this->Modelsection->hasAny($conditions)) {
                $maxid = $this->Modelsection->find('first', array(
                    'recursive' => -1,
                    'fields' => array('id'),
                    'order' => array('id DESC')
                ));
                $this->request->data['Modelsection']['id'] = $maxid['Modelsection']['id'] + 1;
                if (empty($this->request->data['Modelsection']['parent_id']))
                    $this->request->data['Modelsection']['parent_id'] = 1;
                $this->Modelsection->create();
                if ($this->Modelsection->save($this->request->data)) {
                    $this->Session->setFlash("Section créée avec succès.", 'growl', array('type' => 'success'));
                    return $this->redirect($this->previous);
                } else {
                    $this->Session->setFlash("Erreur lors de l'enregistrement des modifications.", 'growl', array('type' => 'error'));
                }
            } else {
                $this->Session->setFlash("Une section de ce nom existe déjà.", 'growl');
            }
        }
        $parents = $this->Modelsection->find('list', array(
            'order' => array('name ASC'),
            'conditions' => array('not'=> array('id' => 1))
        ));
        $this->set('parents', $parents);
        $this->render('edit');
    }

    public function index() {
        $conditions = array();
        if (!empty($this->request->data['Modelsection']['nom']))
            $conditions['Modelsection.id'] = $this->request->data['Modelsection']['nom'];
        else
            $conditions['Modelsection.id >'] = 1;

        $data = $this->Modelsection->find('all', array(
            'conditions' => $conditions,
            'contain' => array('Parent'),
            'order' => 'Modelsection.name ASC'
        ));
        $this->set('data', $data);
        $this->set('names', $this->Modelsection->find('list'));
    }

    public function delete($id) {
        if ($this->Modelsection->delete($id, false)) {
            $this->Session->setFlash("La section $id à été supprimée", 'growl');
        } else {
            $this->Session->setFlash("Impossible de supprimer la section $id", 'growl');
        }
        return $this->redirect($this->referer());
    }

}