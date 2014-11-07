<?php
App::uses('PhpOdtApi', 'ModelOdtValidator.Lib');
App::uses('Utils', 'ModelOdtValidator.Lib');
App::uses('AppModel','Model');
class ModelOdtValidatorAppModel extends AppModel{
    public $actsAs = array('Containable');
}