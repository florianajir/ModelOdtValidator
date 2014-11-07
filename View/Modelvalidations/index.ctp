<?php
/**
 * Application: webdelib / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>

<h2>Règles de validation des modèles</h2>
<div class="spacer"></div>
<div class="alert avertissement">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention!</strong> Les modifications apportées aux règles de validation peuvent créer des problèmes lors de
    l'ajout des modèles d'édition, cette page de configuration est destinée uniquement aux utilisateurs avertis
</div>
<div class="spacer"></div>
<?php
echo $this->Form->create('Modelvalidation', array('action' => 'index'));

echo $this->Html->tag('button', '<i class="fa fa-eye"></i> Afficher/Masquer les filtres', array('type' => 'button', 'id' => 'filtrer', 'class' => 'btn', 'data-toggle' => 'button', 'escape' => false));

echo $this->Html->tag('hr');
echo $this->Html->tag('div', null, array('style' => 'display:none', 'id' => 'divFiltres'));
echo $this->Form->input('Filtre.modeltype_id', array('label' => 'Type', 'placeholder' => 'Type de modèle', 'options' => $types, 'empty' => true));
echo '<div class="spacer"></div>';
echo $this->Form->input('Filtre.modelsection_id', array('label' => 'Section', 'placeholder' => 'Nom de la section', 'options' => $sections, 'empty' => true));
echo '<div class="spacer"></div>';
echo $this->Form->input('Filtre.modelvariable_id', array('label' => 'Variable', 'empty' => 'Ne concerne pas une variable', 'options' => $variables, 'empty' => true));
echo '<div class="spacer"></div>';
echo $this->Form->button('<i class="fa fa-filter"></i> Filtrer', array('class' => 'btn btn-info', 'escape' => false, 'type' => 'submit'));

echo $this->Html->tag('hr');

echo $this->Html->tag('/div', null);

echo $this->Form->end();

$barreActions = $this->Html->tag('div', null, array('class' => 'btn-group'));
$barreActions .= $this->Html->link('<i class="fa fa-flag"></i> Ajouter des règles', array('action' => 'generate'), array('escape' => false, 'class' => 'btn btn-primary'));
$barreActions .= $this->Html->link('<i class="fa fa-code"></i> Variables', array('controller' => 'Modelvariables'), array('escape' => false, 'class' => 'btn btn-info'));
$barreActions .= $this->Html->link('<i class="fa fa-flag-checkered"></i> Sections', array('controller' => 'Modelsections'), array('escape' => false, 'class' => 'btn btn-info'));
$barreActions .= $this->Html->tag('/div', null);
echo $barreActions;
?>
<hr/>
<table class="table table-striped">
    <caption>Liste des règles de validation par type de modèle (variables et sections autorisées)</caption>
    <thead>
    <tr>
        <th>#</th>
        <th>Type</th>
        <th>Section</th>
        <th>Variable</th>
        <th>Nombre</th>
        <th style="width: 140px">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($datas as $rel) {
        if (empty($rel['Modelvalidation']['actif']))
            echo $this->Html->tag('tr', null, array('class' => 'error'));
        else
            echo $this->Html->tag('tr', null);
        echo $this->Html->tag('td', $rel['Modelvalidation']['id']);
        echo $this->Html->tag('td', $rel['Modeltype']['name']);
        echo $this->Html->tag('td', $rel['Modelsection']['name']);
        echo $this->Html->tag('td', $rel['Modelvariable']['name']);

        // Nombre
        if (empty($rel['Modelvalidation']['min']) && empty($rel['Modelvalidation']['max'])) {
            echo $this->Html->tag('td', 'Non défini');
        } elseif ($rel['Modelvalidation']['min'] === $rel['Modelvalidation']['max']) {
            echo $this->Html->tag('td', 'Exactement : ' . $rel['Modelvalidation']['min']);
        } elseif (!empty($rel['Modelvalidation']['min']) && !empty($rel['Modelvalidation']['max'])) {
            echo $this->Html->tag('td', 'Entre ' . $rel['Modelvalidation']['min'] . ' et ' . $rel['Modelvalidation']['max']);
        } elseif (!empty($rel['Modelvalidation']['max'])) {
            echo $this->Html->tag('td', 'Maximum : ' . $rel['Modelvalidation']['max']);
        } elseif (!empty($rel['Modelvalidation']['min'])) {
            echo $this->Html->tag('td', 'Minimum : ' . $rel['Modelvalidation']['min']);
        }

        echo $this->Html->tag('td',
            $this->Html->tag('div', null, array('class' => 'btn-group btn-group-action'))
            .
            $this->Html->link('<i class="fa fa-edit"></i> Modifier', array('action' => 'edit', $rel['Modelvalidation']['id']), array('class' => 'btn btn-mini', 'escape' => false, 'title' => 'Modifier la règle'))
            .
            $this->Html->link('<i class="fa fa-trash-o"></i> Supprimer', array('action' => 'delete', $rel['Modelvalidation']['id']), array('class' => 'btn btn-mini btn-danger', 'escape' => false, 'title' => 'Supprimer la règle'), "Confirmez-vous la suppression de cette règle de validation ? Attention cette action est irréversible !")
            .
            $this->Html->tag('/div', null),
            array('style' => 'text-align:center;')
        );
        echo $this->Html->tag('/tr', null);
    }
    ?>
    </tbody>
</table>
<hr/>
<?php echo $barreActions; ?>
<script type="application/javascript">
    $(document).ready(function () {
        $('#filtrer').click(function () {
            if ($('#divFiltres').is(':visible'))
                $('#divFiltres').hide();
            else
                $('#divFiltres').show();
        });

        $('#FiltreModeltypeId').select2({
            width: 'element',
            placeholder: "Filtre de type",
            allowClear: true
        });
        $('#FiltreModelsectionId').select2({
            width: 'element',
            placeholder: "Filtre de section",
            allowClear: true
        });
        $('#FiltreModelvariableId').select2({
            width: 'element',
            placeholder: "Filtre de variable",
            allowClear: true
        });

        <?php if (!empty($this->request->data['Filtre'])) : ?>
        $('#divFiltres').show();
        $('#filtrer').addClass('active');
        <?php endif; ?>

    });
</script>