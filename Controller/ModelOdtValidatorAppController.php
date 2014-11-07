<?php
/**
 * Application: webdelib / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */

App::uses('PhpOdtApi', 'ModelOdtValidator.Lib');
class ModelOdtValidatorAppController extends AppController {

    public $aucunDroit;
    public $components = array( 'RequestHandler');
    public $helpers = array( 'Html');

    public $uses = array(
        'ModelOdtValidator.Modeltype',
        'ModelOdtValidator.Modelvariable',
        'ModelOdtValidator.Modelsection',
        'ModelOdtValidator.Modelvalidation',
        'ModelOdtValidator.Modeltemplate'
    );

}