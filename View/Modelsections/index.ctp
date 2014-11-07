<?php
/**
 * Application: webdelib / Adullact.
 * Date: 26/02/2013
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>

<h2>Sections de modèles</h2>
<br/>
<?php
echo $this->Form->create('Modelsection', array('action' => 'index'));
echo $this->Form->input('nom', array('label' => array('text' => 'Rechercher une section', 'style' => 'width: auto; padding-top: 5px;'), 'placeholder' => 'Nom de section', 'options' => $names, 'empty' => true, 'div' => false));
echo $this->Form->button('<i class="fa fa-filter"></i> Filtrer', array('class' => 'btn btn-info', 'escape' => false, 'type' => 'submit', 'style' => 'margin-left:5px'));
echo $this->Html->tag('hr');
echo $this->Form->end();

$barreActions = $this->Html->tag('div', null, array('class' => 'btn-group'));
$barreActions .= $this->Html->link('<i class="fa fa-flag-checkered"></i> Ajouter une section', array('action' => 'add'), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Déclarer une nouvelle section de modèle'));
$barreActions .= $this->Html->link('<i class="fa fa-flag"></i> Règles de validation', array('controller' => 'Modelvalidations'), array('escape' => false, 'class' => 'btn btn-info', 'title' => 'Règles de validation des modèles d\'édition'));
$barreActions .= $this->Html->link('<i class="fa fa-code"></i> Variables', array('controller' => 'Modelvariables'), array('escape' => false, 'class' => 'btn btn-info', 'title' => 'Liste des variables de modèle'));
$barreActions .= $this->Html->tag('/div', null);

echo $barreActions;
?>
<hr/>
<table class="table table-striped">
    <caption>Liste des sections autorisées dans les modèles</caption>
    <thead>
    <tr>
        <th>id</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Parent</th>
        <th>Création</th>
        <th>Modification</th>
        <th style="width: 140px">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($data as $section) {
        echo $this->Html->tag('tr', null);
        echo $this->Html->tag('td', $section['Modelsection']['id']);
        echo $this->Html->tag('td', $section['Modelsection']['name']);
        echo $this->Html->tag('td', $section['Modelsection']['description']);
        echo $this->Html->tag('td', $section['Parent']['id'] > 1 ? $this->Html->link($section['Parent']['name'], array('action' => 'edit', $section['Parent']['id'])) : '');
        echo $this->Html->tag('td', date('d/m/Y \à h:i:s', strtotime($section['Modelsection']['created'])));
        echo $this->Html->tag('td', date('d/m/Y \à h:i:s', strtotime($section['Modelsection']['modified'])));
        echo $this->Html->tag('td',
            $this->Html->tag('div', null, array('class' => 'btn-group btn-group-action'))
            . $this->Html->link('<i class="fa fa-edit"></i> Modifier', array('action' => 'edit', $section['Modelsection']['id']), array('class' => 'btn btn-mini', 'escape' => false, 'title' => 'Modifier la règle'))
            . $this->Html->link('<i class="fa fa-trash-o"></i> Supprimer', array('action' => 'delete', $section['Modelsection']['id']), array('class' => 'btn btn-mini btn-danger', 'escape' => false, 'title' => 'Supprimer la règle'), "Confirmez-vous la suppression de cette règle de validation ? Attention cette action est irréversible !")
            . $this->Html->tag('/div', null)
        );
        echo $this->Html->tag('/tr', null);
    }
    ?>
    </tbody>
</table>
<hr/>
<?php
echo $barreActions;
?>
<script type="application/javascript">
    $(document).ready(function () {
        $('#filtrer').change(function () {
            if ($('#filtrer').prop('checked'))
                $('#divFiltres').show();
            else
                $('#divFiltres').hide();
        });

        $('#ModelsectionNom').select2({
            width: 'element',
            placeholder: "Nom de section",
            allowClear: true
        });

        <?php if (!empty($this->request->data['Modelsection']['filtrer'])) : ?>
        $('#divFiltres').show();
        <?php endif; ?>

    });
</script>