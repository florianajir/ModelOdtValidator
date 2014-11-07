<?php
/**
 * Application: webdelib / Adullact.
 * Date: 26/02/2013
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>
<?php if ($this->action == 'add'): ?>
    <h2>Création d'une nouvelle variable de modèle</h2>
<?php else: ?>
    <h2>Modification d'une variable de modèle</h2>
<?php endif; ?>

<?php
echo $this->Form->create('Modelvariable');
if ($this->action == 'edit')
    echo $this->Form->hidden('id');
echo $this->Form->input('name', array('label' => 'Nom'));
echo $this->Form->input('description', array('label' => 'Description', 'type' => 'textarea'));
?>
    <hr/>
    <div class="btn-group">
        <?php
        echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', array('action' => 'index'), array('class' => 'btn', 'escape' => false));
        echo $this->Form->button('<i class="fa fa-save"></i> Enregistrer', array('class' => 'btn btn-primary', 'escape' => false, 'type' => 'submit'));
        ?>
    </div>
<?php
echo $this->Form->end();
?>