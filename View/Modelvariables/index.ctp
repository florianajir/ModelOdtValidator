<?php
/**
 * Application: webdelib / Adullact.
 * Date: 26/02/2013
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>

<h2>Variables de modèles</h2>
<br/>
<?php
echo $this->Form->create('Modelvariable', array('action' => 'index'));
echo $this->Form->input('nom', array('label' => array('text' => 'Rechercher une variable', 'style' => 'width: auto; padding-top: 5px;'), 'placeholder' => 'Nom de variable', 'options' => $names, 'empty' => true, 'div' => false));
echo $this->Form->button('<i class="fa fa-filter"></i> Filtrer', array('class' => 'btn btn-info', 'escape' => false, 'type' => 'submit', 'style' => 'margin-left:5px'));
echo $this->Html->tag('hr');
echo $this->Form->end();

$barreActions = $this->Html->tag('div', null, array('class' => 'btn-group'));
$barreActions .= $this->Html->link('<i class="fa fa-code"></i> Ajouter une variable', array('action' => 'add'), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Déclarer une nouvelle variable de modèle'));
$barreActions .= $this->Html->link('<i class="fa fa-flag"></i> Règles de validation', array('controller' => 'Modelvalidations'), array('escape' => false, 'class' => 'btn btn-info', 'title' => 'Règles de validation des modèles d\'édition'));
$barreActions .= $this->Html->link('<i class="fa fa-flag-checkered"></i> Sections', array('controller' => 'Modelsections'), array('escape' => false, 'class' => 'btn btn-info', 'title' => 'Liste des sections de modèle'));
$barreActions .= $this->Html->tag('/div', null);

echo $barreActions;
?>
<hr/>
<table class="table table-striped">
    <caption>Liste des variables autorisées dans les modèles</caption>
    <thead>
    <tr>
        <th>id</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($data as $variable) {
        echo $this->Html->tag('tr', null);

        echo $this->Html->tag('td', $variable['Modelvariable']['id']);
        echo $this->Html->tag('td', $variable['Modelvariable']['name']);
        echo $this->Html->tag('td', $variable['Modelvariable']['description']);
        echo $this->Html->tag('td', date('d/m/Y \à h:i:s', strtotime($variable['Modelvariable']['created'])));
        echo $this->Html->tag('td', date('d/m/Y \à h:i:s', strtotime($variable['Modelvariable']['modified'])));

        echo $this->Html->tag('td',
            $this->Html->tag('div', null, array('class' => 'btn-group btn-group-action'))
            .
            $this->Html->link('<i class="fa fa-edit"></i> Modifier', array('action' => 'edit', $variable['Modelvariable']['id']), array('class' => 'btn btn-mini', 'escape' => false, 'title' => 'Modifier la règle'))
            .
            $this->Html->link('<i class="fa fa-trash-o"></i> Supprimer', array('action' => 'delete', $variable['Modelvariable']['id']), array('class' => 'btn btn-mini btn-danger', 'escape' => false, 'title' => 'Supprimer la règle'), "Confirmez-vous la suppression de cette règle de validation ? Attention cette action est irréversible !")
            .
            $this->Html->tag('/div', null)
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

        $('#ModelvariableNom').select2({
            width: 'element',
            placeholder: "Nom de variable",
            allowClear: true
        });

        <?php if (!empty($this->request->data['Modelvariable']['filtrer'])) : ?>
        $('#divFiltres').show();
        <?php endif; ?>

    });
</script>