<h2>Vue détaillée du modèle d'édition "<?php echo $this->data['Modeltemplate']['name']; ?>" (Type : <?php echo $this->data['Modeltype']['name']; ?>)</h2>

<div>
    <h3>Détails</h3>
    <p>Nom : <?php echo $this->data['Modeltemplate']['name']; ?></p>
    <p>Type de modèle : <abbr title="<?php echo $this->data['Modeltype']['description']; ?>"><?php echo $this->data['Modeltype']['name']; ?></abbr> </p>
    <p>Fichier : <?php echo $this->Html->link($this->data['Modeltemplate']['filename'].' <i class="fa fa-download"></i>', array('action'=>'download', $this->data['Modeltemplate']['id']), array('escape'=>false)); ?> (<?php echo $this->data['Modeltemplate']['filesize']; ?>)</p>
    <p>Date de création : <?php echo $this->data['Modeltemplate']['created']; ?></p>
    <p>Date de modification : <?php echo $this->data['Modeltemplate']['modified']; ?></p>
</div>
<div id="validation">

    <?php if (!empty($validation['errors'])) : ?>
        <h3>Erreurs</h3>
        <ul id="errors">
            <?php foreach($validation['errors'] as $error): ?>
                <li>
                    <?php echo $error; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($validation['warnings'])) : ?>
    <h3>Avertissements</h3>
    <ul id="warnings">
        <?php foreach($validation['warnings'] as $warning): ?>
        <li>
        <?php echo $warning; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>

<div class="submit btn-group">
    <?php echo $this->Html->link("<i class='fa fa-arrow-left'></i> Retour", array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'title' => 'Retour à la liste des modèles')); ?>
    <?php echo $this->Html->link("<i class='fa fa-edit'></i> Modifier", array('action' => 'edit', $this->data['Modeltemplate']['id']), array('class' => 'btn btn-primary', 'escape' => false, 'title' => 'Modifier le modèle')); ?>
</div>
