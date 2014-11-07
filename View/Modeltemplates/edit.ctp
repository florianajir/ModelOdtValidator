<?php if ($this->action == 'edit') : ?>
    <h2>Modification du modèle "<?php echo $this->data['Modeltemplate']['name']; ?>"</h2>
<?php else: ?>
    <h2>Nouveau modèle</h2>
<?php endif; ?>

<?php
echo $this->Form->create('Modeltemplate', array('type' => 'file'));

echo $this->Form->input('Modeltemplate.name', array('label' => 'Libellé', 'placeholder' => 'Nom du modèle'));
echo $this->Html->tag('div', '', array('class' => 'spacer'));
echo $this->Form->input('Modeltemplate.modeltype_id', array('options' => $modeltypes, 'label' => 'Type de modèle', 'type' => 'select'));
echo $this->Html->tag('div', '', array('class' => 'spacer'));
if ($this->action == 'edit') {
    if (empty($this->validationErrors['Modeltemplate']['fileupload'])) {
        echo $this->Html->tag('div', null, array('id' => 'originalDocument'));
        echo '<i class="fa fa-file-text"></i> ';
        echo $this->Html->Link($this->data['Modeltemplate']['filename'], array('action' => 'download', $this->data['Modeltemplate']['id']), array('title' => 'Télécharger le document'));
        echo $this->Html->tag('a', '<i class="fa fa-eraser"></i> Remplacer le document', array('id' => 'replaceDocument', 'class' => 'btn btn-danger'));
        echo $this->Html->tag('/div', null);
    }
    echo $this->Form->hidden('Modeltemplate.id');
}
?>

<span id="fileUpload">
<?php echo $this->Form->input('fileupload', array('label' => 'Fichier', 'type' => 'file')); ?>
</span>
<?php
if (!empty($validation['errors'])) {
    echo $this->Html->tag('hr');
    echo $this->Html->tag('div', null, array('id' => 'validationErrors'));
    echo $this->Html->tag('h3', 'Erreurs du modèle');
    echo $this->Html->tag('ul', null);
    foreach ($validation['errors'] as $error) {
        echo $this->Html->tag('li', $error);
    }
    echo $this->Html->tag('/ul');
    echo $this->Html->tag('/div', null);
}
?>
<hr>
<div class="submit">
    <?php $this->Html2->boutonsAddCancel(); ?>
</div>
<?php echo $this->Form->end(); ?>
<?php if ($this->action == 'edit' && empty($this->validationErrors['Modeltemplate']['fileupload'])): ?>
    <script type="application/javascript">
        $(document).ready(function () {
            $('#ModeltemplateFileupload').prop('disabled', true);
            $('#replaceDocument').click(function () {
                $('#fileUpload').show();
                $('#ModeltemplateFileupload').prop('disabled', false);
                $('#originalDocument').remove();
                return false;
            });
        })
    </script>
<?php endif; ?>
<style>
    label {
        line-height: 28px;
    }

    #validationErrors {
        padding: 10px;
        background-color: whitesmoke;
        border: 2px dotted red;
    }

    <?php if ($this->action == 'edit' && empty($this->validationErrors['Modeltemplate']['fileupload'])): ?>
    #fileUpload {
        display: none;
    }

    <?php endif; ?>
    #replaceDocument {
        cursor: pointer;
        margin-left: 20px;
    }
</style>