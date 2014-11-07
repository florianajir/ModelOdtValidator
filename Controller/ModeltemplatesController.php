<?php
App::uses('File', 'Utility');
App::uses('Utils', 'ModelOdtValidator.Lib');

/**
 * @property Typeseance Typeseance
 * @property Modeltemplate Modeltemplate
 * @property DroitsComponent Droits
 */
class ModeltemplatesController extends ModelOdtValidatorAppController {
    public $helpers = array('Html', 'Form');
    public $aucunDroit;
    public $components = array('ModelOdtValidator.Fido');

    public function index() {
        $templates = $this->Modeltemplate->find('all', array(
            'fields' => array(
                'Modeltemplate.id',
                'Modeltemplate.name',
                'Modeltemplate.filename',
                'Modeltemplate.filesize',
                'Modeltemplate.created',
                'Modeltemplate.modified'
            ),
            'order' => array('Modeltemplate.name' => 'ASC'),
            'contain' => array('Modeltype.name'),
        ));

        //Cas particulier webdelib : test possibilité de supprimer les modèles
        if (Configure::read('APP_CONTAINER') == 'WEBDELIB') {
            $deletable = array();
            App::uses('Typeseance', 'Model');
            $this->Typeseance = new Typeseance();
            foreach ($templates as $template) {
                $id = $template['Modeltemplate']['id'];
                if ($this->Typeseance->find('first', array('conditions' => array(
                    'OR' => array(
                        'Typeseance.modelprojet_id' => $id,
                        'Typeseance.modeldeliberation_id' => $id,
                        'Typeseance.modelconvocation_id' => $id,
                        'Typeseance.modelordredujour_id' => $id,
                        'Typeseance.modelpvsommaire_id' => $id,
                        'Typeseance.modelpvdetaille_id' => $id)),
                    'recursive' => -1))
                )
                    $deletable[$id] = false;
                else
                    $deletable[$id] = true;
            }
            $this->set('deletable', $deletable);
        }
        foreach ($templates as &$template)
            $template['Modeltemplate']['filesize'] = Utils::human_filesize($template['Modeltemplate']['filesize']);

        $this->set('templates', $templates);
        $this->set('canEditRules', $this->Droits->check($this->user_id, 'Modelvalidations:index'));
    }

    public function view($id = null) {
        if (!$id) {
            $this->Session->setFlash('id invalide', 'growl', array('type' => 'erreur'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->request->data = $this->Modeltemplate->find('first', array(
                'recursive' => 0,
                'conditions' => array('Modeltemplate.id' => $id),
                'fields' => array(
                    'Modeltemplate.id',
                    'Modeltemplate.modeltype_id',
                    'Modeltemplate.name',
                    'Modeltemplate.content',
                    'Modeltemplate.filename',
                    'Modeltemplate.filesize',
                    'Modeltemplate.created',
                    'Modeltemplate.modified'
                ),
                'contain' => array(
                    'Modeltype.name',
                    'Modeltype.description'
                )
            ));

            if (!empty($this->request->data)) {
                //Conversion taille document pour lecture
                $this->request->data['Modeltemplate']['filesize'] = Utils::human_filesize($this->request->data['Modeltemplate']['filesize']);
                //Envoi des résultats de la validation à la vue
                $this->set('validation', $this->Modeltemplate->validerFlux(
                    $this->request->data['Modeltemplate']['content'],
                    $this->request->data['Modeltemplate']['modeltype_id']
                ));
                //Vidage mémoire
                unset($this->request->data['Modeltemplate']['content']);
                return $this->render();
            } else {
                $this->Session->setFlash('Aucun fichier lié à ce modèle', 'growl', array('type' => 'erreur'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function add() {
        if (!empty($this->request->data)) {
            $this->_add_edit();
        }
        $this->set('modeltypes', $this->Modeltemplate->Modeltype->find('list', array('recursive' => 0)));
        return $this->render('edit');
    }

    public function edit($id = null) {
        if (!$id) {
            $this->Session->setFlash('id invalide', 'growl', array('type' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }

        $template = $this->Modeltemplate->find('first', array(
            'conditions' => array('Modeltemplate.id' => $id),
            'fields' => array(
                'id',
                'name',
                'modeltype_id',
                'filename',
                'filesize',
                'created',
                'modified'
            )
        ));

        if (!$template) {
            $this->Session->setFlash('Modèle introuvable', 'growl');
            return $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data))
            $this->_add_edit($id);

        $this->request->data = $template;

        $this->set('modeltypes', $this->Modeltemplate->Modeltype->find('list', array('recursive' => 0)));
        return $this->render();
    }

    private function _add_edit($id = null) {
        $erreur = '';
        $this->Modeltemplate->set($this->request->data);
        if ($this->Modeltemplate->validates()) {
            if ($this->request->data['Modeltemplate']['modeltype_id'] !== MODEL_TYPE_TOUTES) {
                if (!empty($this->request->data['Modeltemplate']['fileupload'])) {
                    $validation = $this->Modeltemplate->Modeltype->Modelvalidation->validate($this->request->data['Modeltemplate']['fileupload']['tmp_name'], $this->request->data['Modeltemplate']['modeltype_id']);
                    if (empty($validation['errors'])) {
                        $this->request->data['Modeltemplate']['filename'] = $this->request->data['Modeltemplate']['fileupload']['name'];
                        $this->request->data['Modeltemplate']['filesize'] = $this->request->data['Modeltemplate']['fileupload']['size'];
                        $this->request->data['Modeltemplate']['content'] = file_get_contents($this->request->data['Modeltemplate']['fileupload']['tmp_name']);
                    }
                } else {
                    $template = $this->Modeltemplate->find('first', array(
                        'recursive' => -1,
                        'conditions' => array('Modeltemplate.id' => $id),
                        'fields' => array('content')
                    ));

                    $validation = $this->Modeltemplate->validerFlux(
                        $template['Modeltemplate']['content'],
                        $this->request->data['Modeltemplate']['modeltype_id']
                    );
                }

                if (!empty($validation['errors'])) {
                    $this->set('validation', $validation);
                    $erreur = "Des erreurs ont été détectées dans le modèle.";
                }
            }
        } else {
            // La logique n'est pas validée
            foreach ($this->Modeltemplate->validationErrors as $field) {
                foreach ($field as $error) {
                    $erreur .= $error . '<br>';
                }
            }
        }

        if (empty($erreur)) {

            if (!empty($id))
                $this->Modeltemplate->id = $id;
            else
                $this->Modeltemplate->create();

            if ($this->Modeltemplate->save($this->request->data)) {
                $this->Session->setFlash(__('Modèle validé et enregistré.'), 'growl');
                return $this->redirect(array('action' => 'index'));
            } else
                $erreur = 'Erreur lors de la sauvegarde.<br/>Veuillez contacter votre administrateur.';
        }
        $this->Session->setFlash($erreur, 'growl', array('type' => 'erreur'));
        return false;
    }

    public function delete($id = null) {
        if (empty($id)) {
            $this->Session->setFlash('id invalide', 'growl', array('type' => 'error'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $deletable = true;
            //Cas particulier webdelib : test possibilité de supprimer les modèles
            if (Configure::read('APP_CONTAINER') == 'WEBDELIB') {
                App::uses('Typeseance', 'Model');
                $this->Typeseance = new Typeseance();
                $record = $this->Typeseance->find('count', array(
                    'conditions' => array(
                        'OR' => array(
                            'Typeseance.modelprojet_id' => $id,
                            'Typeseance.modeldeliberation_id' => $id,
                            'Typeseance.modelconvocation_id' => $id,
                            'Typeseance.modelordredujour_id' => $id,
                            'Typeseance.modelpvsommaire_id' => $id,
                            'Typeseance.modelpvdetaille_id' => $id))));
                if ($record)
                    $deletable = false;
            }
            if ($deletable) {
                if ($this->Modeltemplate->delete($id)) {
                    $this->Session->setFlash('Le modèle a été supprimé.', 'growl');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Erreur lors de la suppression du modèle', 'growl', array('type' => 'erreur'));
                    return $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Ce modèle ne peut pas être supprimé.', 'growl', array('type' => 'erreur'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    /**
     * Télécharge le fichier odt du modèle
     * @param int|string $id identifiant du template
     * @return mixed
     */
    public function download($id) {
        $template = $this->Modeltemplate->find('first', array(
            'recursive' => -1,
            'conditions' => array('Modeltemplate.id' => $id),
            'fields' => array(
                'Modeltemplate.id',
                'Modeltemplate.modeltype_id',
                'Modeltemplate.name',
                'Modeltemplate.content',
                'Modeltemplate.filename',
                'Modeltemplate.filesize',
                'Modeltemplate.created',
                'Modeltemplate.modified'
            )
        ));

        if (!empty($template)) {
            // envoi au client
            $this->response->disableCache();
            $this->response->type('application/vnd.oasis.opendocument.text');
            $this->response->body($template['Modeltemplate']['content']);
            $this->response->download($template['Modeltemplate']['filename']);
            return $this->response;
        } else {
            $this->Session->setFlash('Modèle introuvable', 'growl', array('type' => 'erreur'));
            return $this->redirect($this->referer());
        }
    }

}