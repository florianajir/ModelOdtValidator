<?php
/**
 * Application: webdelib / Adullact.
 * Date: 03/12/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>
<?php if ($this->action == 'add'): ?>
    <h2>Création d'une règle de validation de modèle d'édition</h2>
<?php else: ?>
    <h2>Modification de la règle de validation</h2>
<?php endif; ?>

<?php
echo $this->Form->create('Modelvalidation');
echo $this->Form->input('modeltype_id', array('label' => 'Type', 'options' => $types, 'empty' => true));
echo $this->Form->input('modelsection_id', array('label' => 'Section', 'options' => $sections, 'empty' => true));
if ($this->action == 'add' || !empty($this->request->data['Modelvalidation']['modelvariable_id']))
echo $this->Form->input('modelvariable_id', array('label' => 'Variable', 'empty' => true, 'options' => $variables));
echo $this->Form->input('activeMin', array('label' => 'Fixer un nombre minimum', 'type' => 'checkbox', 'value'=>false));
echo $this->Form->input('min', array('label' => 'Minimum', 'type' => 'number', 'value'=>'0', 'div'=>array('style'=>'display:none')));
echo $this->Form->input('activeMax', array('label' => 'Fixer un nombre maximum', 'type' => 'checkbox', 'value'=>false));
echo $this->Form->input('max', array('label' => 'Maximum', 'type' => 'number', 'value'=>'0', 'div'=>array('style'=>'display:none')));
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
<script type="application/javascript">
    $(document).ready(function(){

        $('#ModelvalidationModeltypeId').select2({
            width: 'element',
            placeholder: "Toutes Editions",
            allowClear: true
        });
        $('#ModelvalidationModelsectionId').select2({
            width: 'element',
            placeholder: "Document",
            allowClear: true
        });
        $('#ModelvalidationModelvariableId').select2({
            width: 'element',
            placeholder: "Ne concerne pas une variable",
            allowClear: true
        });

        $('#ModelvalidationActiveMin').change(function(){
            if ($('#ModelvalidationActiveMin').prop('checked')){
                /* Min activé */
                $('#ModelvalidationMin').closest('div').show();
                $('#ModelvalidationMin').val(1);
            }else{
                /* Min désactivé */
                $('#ModelvalidationMin').closest('div').hide();
                $('#ModelvalidationMin').val(0);
            }
        });

        $('#ModelvalidationMin').change(function(){
            if ($('#ModelvalidationMin').val() < 0)
                $('#ModelvalidationMin').val(0);
            if ($('#ModelvalidationActiveMax').prop('checked') && $('#ModelvalidationMax').val() < $('#ModelvalidationMin').val()){
                $('#ModelvalidationMax').val($('#ModelvalidationMin').val());
            }
        });

        $('#ModelvalidationActiveMax').change(function(){
            if ($('#ModelvalidationActiveMax').prop('checked')){
                /* Max activé */
                $('#ModelvalidationMax').closest('div').show();
                if ($('#ModelvalidationMin').val() != 0)
                    $('#ModelvalidationMax').val($('#ModelvalidationMin').val());
                else
                    $('#ModelvalidationMax').val(1);
            }else{
                /* Max désactivé */
                $('#ModelvalidationMax').closest('div').hide();
                $('#ModelvalidationMax').val(0);
            }
        });

        $('#ModelvalidationMax').change(function(){
            if ($('#ModelvalidationMax').val() < 0)
                $('#ModelvalidationMax').val(0);
            if ($('#ModelvalidationActiveMin').prop('checked') && $('#ModelvalidationMax').val() < $('#ModelvalidationMin').val()){
                $('#ModelvalidationMin').val($('#ModelvalidationMax').val());
            }
        });

        <?php if (!empty($this->request->data['Modelvalidation']['min'])) : ?>
        $('#ModelvalidationActiveMin').click();
        $('#ModelvalidationMin').val(<?php echo $this->request->data['Modelvalidation']['min']; ?>);
        <?php endif; ?>

        <?php if (!empty($this->request->data['Modelvalidation']['max'])) : ?>
        $('#ModelvalidationActiveMax').click();
        $('#ModelvalidationMax').val(<?php echo $this->request->data['Modelvalidation']['max']; ?>);
        <?php endif; ?>
    });
</script>
<style>
    #ModelvalidationModeltypeId,#ModelvalidationModelsectionId,#ModelvalidationModelvariableId{
        width: 400px;
    }
    div.input.select{
        margin: 15px;
    }
</style>