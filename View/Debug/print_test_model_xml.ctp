<?php
/**
 * Created by PhpStorm.
 * User: fajir
 * Date: 19/11/13
 * Time: 15:54
 */

echo $this->Html->tag('h2', 'Contenu XML de document ODT');
echo $this->Form->create('Odt', array('type' => 'file'));
echo $this->Form->input('file', array('type' => 'file'));
echo $this->Form->input('dl', array('type' => 'checkbox'));
echo $this->Form->end('Afficher XML');
if (!empty($xml)){
    echo $this->Form->input('xml', array('type' => 'textarea', 'value' => $xml));
}

?>
<style>
    #xml{
        width: 100%;
        height: 600px;
    }
</style>