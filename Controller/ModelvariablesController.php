<?php

/**
 * Application: webdelib / Adullact.
 * Date: 26/02/2013
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
class ModelvariablesController extends ModelOdtValidatorAppController {

    public function edit($id = null) {
        if (!empty($id)) {
            if (empty($this->request->data)) {
                $this->request->data = $this->Modelvariable->find('first', array('conditions' => array('Modelvariable.id' => $id)));
            } else {
                $conditions = array(
                    'NOT' => array('Modelvariable.id' => $id),
                    'Modelvariable.name' => $this->request->data['Modelvariable']['name']
                );
                if (!$this->Modelvariable->hasAny($conditions)) {
                    $this->Modelvariable->id = $id;
                    if ($this->Modelvariable->save($this->request->data)) {
                        $this->Session->setFlash("Variable enregistrée avec succès.", 'growl', array('type' => 'success'));
                        return $this->redirect($this->previous);
                    } else {
                        $this->Session->setFlash("Erreur lors de l'enregistrement de la variable.", 'growl', array('type' => 'error'));
                    }
                } else {
                    $this->Session->setFlash("Une variable de ce nom existe déjà.", 'growl', array('type' => 'erreur'));
                }
            }
        } else {
            $this->Session->setFlash("Veuillez spécifier la variable à modifier", 'growl');
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function add() {
        if (!empty($this->request->data)) {
            if (!$this->Modelvariable->hasAny(array('Modelvariable.name' => $this->request->data['Modelvariable']['name']))) {
                $maxid = $this->Modelvariable->find('first', array(
                    'recursive' => -1,
                    'fields' => array('id'),
                    'order' => array('id DESC')
                ));
                $this->request->data['Modelvariable']['id'] = $maxid['Modelvariable']['id'] + 1;
                $this->Modelvariable->create();
                if ($this->Modelvariable->save($this->request->data)) {
                    $this->Session->setFlash("Variable créée avec succès.", 'growl', array('type' => 'success'));
                    return $this->redirect($this->previous);
                } else {
                    $this->Session->setFlash("Erreur lors de l'enregistrement de la variable.", 'growl', array('type' => 'error'));
                }
            } else {
                $this->Session->setFlash("Une variable de ce nom existe déjà.", 'growl');
            }
        }
        $this->render('edit');
    }

    public function index() {
        $conditions = array();
        if (!empty($this->request->data['Modelvariable']['nom']))
            $conditions['Modelvariable.id'] = $this->request->data['Modelvariable']['nom'];
        $data = $this->Modelvariable->find('all', array(
            'recursive' => -1,
            'conditions' => $conditions,
            'order' => 'name ASC'
        ));
        $this->set('data', $data);
        $this->set('names', $this->Modelvariable->find('list'));
    }

    public function delete($id) {
        if ($this->Modelvariable->delete($id, false)) {
            $this->Session->setFlash("La variable $id à été supprimé", 'growl');
        } else {
            $this->Session->setFlash("Impossible de supprimer la variable", 'growl', array('type' => 'error'));
        }
        return $this->redirect($this->referer());
    }

}