<?php
/**
 * Application: webdelib / Adullact.
 * Date: 24/07/14
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
?>
<h2>Génération de nouvelles règles de validation</h2>
<?php
echo $this->Form->create('Modelvalidation');
echo $this->Form->input('modelvariable_id', array('label' => 'Variable(s) concernée(s)', 'empty' => true, 'options' => $variables, 'multiple' => true));
echo $this->Form->input('modelsection_id', array('label' => 'Section(s) concernée(s)', 'options' => $sections, 'empty' => true, 'multiple' => true));
echo $this->Form->input('modeltype_id', array('label' => 'Type(s) concerné(s)', 'options' => $types, 'empty' => true, 'multiple' => true));
echo $this->Form->input('activeMin', array('label' => 'Fixer un nombre minimum', 'type' => 'checkbox', 'value' => false));
echo $this->Form->input('min', array('label' => 'Minimum', 'type' => 'number', 'value' => '0', 'div' => array('style' => 'display:none')));
echo $this->Form->input('activeMax', array('label' => 'Fixer un nombre maximum', 'type' => 'checkbox', 'value' => false));
echo $this->Form->input('max', array('label' => 'Maximum', 'type' => 'number', 'value' => '0', 'div' => array('style' => 'display:none')));
?>
<hr/>
<div id="sql_bloc">
    <?php
    echo $this->Form->input('sql_commands', array('label' => false, 'type' => 'textarea', 'id' => 'sql_commands', 'readonly' => true));
    ?>
<!--    <pre id="sql_commands">Console SQL</pre>-->
</div>
<hr/>
<div class="btn-group">
    <?php
    echo $this->Html->link('<i class="fa fa-arrow-left"></i> Annuler', array('action' => 'index'), array('class' => 'btn', 'escape' => false));
    echo $this->Form->button('<i class="fa fa-save"></i> Ajouter à la base de données', array('class' => 'btn btn-primary', 'escape' => false, 'type' => 'submit'));
    ?>
</div>
<?php
echo $this->Form->end();
?>
<script type="application/javascript">
    $(document).ready(function () {

        $('#ModelvalidationModeltypeId').select2({
            width: 'element',
            placeholder: "Toutes Editions",
            allowClear: true
        });
        $('#ModelvalidationModelsectionId').select2({
            width: 'element',
            placeholder: "Selectionner une section",
            allowClear: true
        });
        $('#ModelvalidationModelvariableId').select2({
            width: 'element',
            placeholder: "Selectionner une variable",
            allowClear: true
        });

        $('#ModelvalidationActiveMin').change(function () {
            if ($('#ModelvalidationActiveMin').prop('checked')) {
                /* Min activé */
                $('#ModelvalidationMin').closest('div').show();
                $('#ModelvalidationMin').val(1);
            } else {
                /* Min désactivé */
                $('#ModelvalidationMin').closest('div').hide();
                $('#ModelvalidationMin').val(0);
            }
        });

        $('#ModelvalidationMin').change(function () {
            if ($('#ModelvalidationMin').val() < 0)
                $('#ModelvalidationMin').val(0);
            if ($('#ModelvalidationActiveMax').prop('checked') && $('#ModelvalidationMax').val() < $('#ModelvalidationMin').val()) {
                $('#ModelvalidationMax').val($('#ModelvalidationMin').val());
            }
        });

        $('#ModelvalidationActiveMax').change(function () {
            if ($('#ModelvalidationActiveMax').prop('checked')) {
                /* Max activé */
                $('#ModelvalidationMax').closest('div').show();
                if ($('#ModelvalidationMin').val() != 0)
                    $('#ModelvalidationMax').val($('#ModelvalidationMin').val());
                else
                    $('#ModelvalidationMax').val(1);
            } else {
                /* Max désactivé */
                $('#ModelvalidationMax').closest('div').hide();
                $('#ModelvalidationMax').val(0);
            }
        });

        $('#ModelvalidationMax').change(function () {
            if ($('#ModelvalidationMax').val() < 0)
                $('#ModelvalidationMax').val(0);
            if ($('#ModelvalidationActiveMin').prop('checked') && $('#ModelvalidationMax').val() < $('#ModelvalidationMin').val()) {
                $('#ModelvalidationMin').val($('#ModelvalidationMax').val());
            }
        });

        /* Génération bloc sql */


        $('#ModelvalidationModeltypeId').change(generateSql);
        $('#ModelvalidationModelsectionId').change(generateSql);
        $('#ModelvalidationModelvariableId').change(generateSql);
        $("#ModelvalidationMin").change(generateSql);
        $("#ModelvalidationMax").change(generateSql);
        $("#ModelvalidationActiveMin").change(generateSql);
        $("#ModelvalidationActiveMax").change(generateSql);
        $('#sql_commands').focus(function() {
            var $this = $(this);
            $this.select();
            // Work around Chrome's little problem
            $this.mouseup(function() {
                // Prevent further mouseup intervention
                $this.unbind("mouseup");
                return false;
            });
        });

    });

    function generateSql() {
        var types = $('#ModelvalidationModeltypeId').val(),
            sections = $('#ModelvalidationModelsectionId').val(),
            variables = $('#ModelvalidationModelvariableId').val(),
            min = $("#ModelvalidationMin").val(),
            max = $("#ModelvalidationMax").val(),
            text = '',
            $sql = $('#sql_commands');

        if (variables != null)
            $.each(variables, function (v, variable) {
                if (sections != null)
                    $.each(sections, function (s, section) {
                        if (types != null)
                            $.each(types, function (s, type) {
                                text += getInsert(variable, section, type, min, max);
                            });
                        else
                            text += getInsert(variable, section, 1, min, max);
                    });
            });

        $($sql).val(text);

        if (text != '')
            $($sql).show();
        else
            $($sql).hide();

    }

    function getInsert(variable, section, type, min, max) {
        return "INSERT INTO public.modelvalidations (modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (" + variable + ", " + section + ", " + type + ", " + min + ", " + max + ", true);\n";
    }
</script>

<style>
    #ModelvalidationModeltypeId, #ModelvalidationModelsectionId, #ModelvalidationModelvariableId {
        width: 400px;
    }

    div.input.select {
        margin: 15px;
    }

    #sql_commands {
        display: none;
        font-family: "courier new", courier, typewriter, monospace;
        font-size: 10px;
        width: 100%;
        line-height: 14px;
    }
</style>