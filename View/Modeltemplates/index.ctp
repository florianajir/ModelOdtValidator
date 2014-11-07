<?php
echo $this->Html->css('ModelOdtValidator.global.css');
?>
<h2>Modèles d'édition</h2>
<?php echo $this->Html->link('<i class="fa fa-plus"></i> Nouveau modèle', array('action' => 'add'), array('escape' => false, 'class' => 'btn btn-success', 'title' => 'Ajouter un nouveau modèle d\'édition')); ?>
<table class="table table-striped">
    <caption>Liste des modèles d'édition pour la génération de documents</caption>
    <thead>
    <tr>
        <th>Nom</th>
        <th>Type</th>
        <th>Nom du fichier</th>
        <th>Taille du fichier</th>
        <th>Date de création</th>
        <th>Date de modification</th>
        <th style="width: 105px;">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($templates as $template): ?>
        <tr>
            <td><?php echo $template['Modeltemplate']['name']; ?></td>
            <td><?php echo $template['Modeltype']['name']; ?></td>
            <td><?php echo $template['Modeltemplate']['filename']; ?></td>
            <td><?php echo $template['Modeltemplate']['filesize']; ?></td>
            <td><?php echo $template['Modeltemplate']['created']; ?></td>
            <td><?php echo $template['Modeltemplate']['modified']; ?></td>
            <td style="text-align: center; vertical-align: middle;">
                <div class="btn-group">
                    <?php
                    echo $this->Html->link('<i class="fa fa-info"></i>', array('action' => 'view', $template['Modeltemplate']['id']), array('class' => 'btn btn-info', 'escape' => false, 'title' => 'Plus d\'informations'));
                    echo $this->Html->link('<i class="fa fa-edit"></i>', array('action' => 'edit', $template['Modeltemplate']['id']), array('class' => 'btn', 'escape' => false, 'title' => 'Modifier le modèle'));
                    echo $this->Html->link('<i class="fa fa-trash-o"></i>', array('action' => 'delete', $template['Modeltemplate']['id']), array('class' => 'btn btn-danger', 'escape' => false, 'title' => 'Supprimer le modèle'), "Confirmez vous la suppression du modèle d'édition ?");
                    ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div style="text-align: center">
    <div class="btn-group">
        <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> Retour', $previous, array('escape' => false, 'class' => 'btn', 'title' => 'Retour à la page précédente')); ?>
        <?php if ($canEditRules) echo $this->Html->link('<i class="fa fa-check-square-o"></i> Règles de validation', array('controller' => 'modelvalidations', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-warning', 'title' => 'Règles de validation des modèles')); ?>
    </div>
</div>
