<?php

/**
 * Application: webdelib / Adullact.
 * Date: 26/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */
class FidoComponent extends Component {

    /**
     * @var array formats reconnus par l'application
     */
    public $formats;

    /**
     * @var array résultats de la derniere analyse
     */
    public $lastResults;

    /**
     * Constructeur
     * Chargement de la librairie Fido pour analyse de fichier
     */
    function FidoComponent() {
        $this->formats = Configure::read("DOC_TYPE");
        $this->lastResults = array();
        App::import('Lib', 'ModelOdtValidator.Fido');
    }

    /**
     * Analyse d'un fichier par fido
     * @param $file
     * @return array|bool
     */
    public function analyzeFile($file) {
        $this->lastResults = Fido::analyzeFile($file);
        if ($this->lastResults['result'] == 'OK') {
            $this->_getDetails();
            return $this->lastResults;
        } else {
            return false;
        }
    }

    /**
     * @param string $mime
     * @param string $puid
     * @return bool format activé
     */
    private function _checkFormat($mime, $puid) {
        if (isset($this->formats[$mime]['puid'][$puid])) {
            return $this->formats[$mime]['puid'][$puid]['actif'];
        } else {
            return false;
        }
    }

    /**
     * @param $file
     * @return bool
     */
    public function checkFile($file) {
        $this->lastResults = Fido::analyzeFile($file);
        if ($this->lastResults['result'] == 'OK') {
            $this->_getDetails();
            return $this->_checkFormat($this->lastResults['mimetype'], $this->lastResults['puid']);
        } else {
            return false;
        }
    }

    /**
     * Résultats analyse fichier avec format actif ou non
     * @return array results
     */
    private function _getDetails() {
        //Si le type mime est repertorié
        if (array_key_exists($this->lastResults['mimetype'], $this->formats)) {

            //Extrait les infos de la config
            $configDetails = $this->formats[$this->lastResults['mimetype']];

            //Retire la branche avec les puid
            $configDetails = Hash::remove($configDetails, 'puid');
            if (array_key_exists($this->lastResults['puid'], $this->formats[$this->lastResults['mimetype']]['puid']))
                $this->lastResults['actif'] = $this->formats[$this->lastResults['mimetype']]['puid'][$this->lastResults['puid']]['actif'];
            else {
                $this->log($this->lastResults, 'error');
                $this->lastResults['actif'] = false;
            }
            $this->lastResults = Hash::merge($this->lastResults, $configDetails);
        } else { //Type mime non repertorié dans la liste
            $this->lastResults['actif'] = false;
        }

        return $this->lastResults;
    }

}