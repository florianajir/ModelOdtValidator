<?php
/**
 * Created by PhpStorm.
 * User: fajir
 * Date: 19/11/13
 * Time: 15:54
 */

if (empty($sql)){
    echo $this->Html->tag('h2', 'Convertisseur CSV -> SQL');
    echo $this->Form->create('Csv', array('type' => 'file'));
    echo $this->Form->input('file', array('type' => 'file'));
    echo $this->Form->end('Convertir');
}