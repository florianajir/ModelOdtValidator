<?php
/**
 * Application: webdelib / Adullact.
 * Date: 22/11/13
 * @author: Florian Ajir <florian.ajir@adullact.org>
 * @license CeCiLL V2 <http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html>
 */

App::uses('PhpOdtApi', 'ModelOdtValidator.Lib');
App::uses('File', 'Utility');
class DebugController extends ModelOdtValidatorAppController
{
    public $aucunDroit;

    public function scan()
    {
        $variables = array();
        $sections = array();
        $modele = array();
        $i = 0;
        foreach (scandir('test-models/') as $file) {
            if ($file[0] == '.') continue;
            $lib = new PhpOdtApi();
            $lib->loadFromFile('test-models/' . $file);
            foreach ($lib->getUserFields() as $var) {
                if (!in_array(trim($var), $variables))
                    $variables[] = trim($var);
            }
            foreach ($lib->getSections() as $section) {
                if (!in_array(trim($section), $sections))
                    $sections[] = trim($section);
            }
            $i++;
        }
        $this->set('sections', $sections);
        $this->set('modele', $modele);
        $this->set('variables', $variables);
    }

    public function printTestModelXml($testModelName = null)
    {
        if (empty($this->data) && !empty($testModelName)){
            $this->request->header('Content-Type: text/xml');
            $path = getcwd() . '/test-models/modele_' . $testModelName . '.odt';
            exit ($this->Modelvalidation->getXmlFromFile($path));
        }elseif (!empty($this->data)) {
            if ($this->request->data['Odt']['file']['error'] != 0) {
                $this->Session->setFlash("Erreur lors de l'upload", 'growl', array('type' => 'error'));
                $this->redirect(array('action' => 'printTestModelXml'));
            }
            //DEBUT DES TESTS
            $path = $this->request->data['Odt']['file']['tmp_name'];
            $lib = new PhpOdtApi();
            $lib->loadFromOdtBin(file_get_contents($path), 'w', true);

//            debug($lib->getUserFieldsDeclared());

            if ($this->data['Odt']['dl']){
                if (!$lib->hasUserFieldsInSection('fichier', 'Annexes')){
                    $lib->appendUserField('fichier', 'string', 'Annexes');
                }
                $odt = $lib->save(true);
                header("application/vnd.oasis.opendocument.text");
                header("Content-Length: " . count($odt));
                header("Content-Disposition: attachment; filename=nouveau.odt");
                echo $odt;
                exit;
            }else{
                if (!$lib->hasUserFieldsInSection('fichier', 'Annexes')){
//                    debug('ajout variable fichier section annexes');
                    $lib->appendUserField('fichier', 'string', 'Annexes');
                }
                $lib->save();
            }
            $this->set('xml', $lib->content);
        }
    }

    public function csvtosql()
    {
        if (!empty($this->data)) {
            if ($this->request->data['Csv']['file']['error'] != 0) {
                $this->Session->setFlash("Erreur lors de l'upload", 'growl', array('type' => 'error'));
                $this->redirect(array('action' => 'csvtosql'));
            } elseif ($this->request->data['Csv']['file']['type'] != 'text/csv') {
                $this->Session->setFlash("Erreur de format, le fichier doit être un fichier csv", 'growl', array('type' => 'error'));
                $this->redirect(array('action' => 'csvtosql'));
            }
            $row = 0;
            $cpt = 1;
            $insert_variable = array();
            $insert_join = array();
            $insert_section = array(
                '1' => 'Document',
                '2' => 'Projets',
                '3' => 'Seances',
                '4' => 'AvisProjet',
                '5' => 'Annexes',
            );

            $insert_types = array(
                '1' => 'Toutes Editions',
                '2' => 'Projet',
                '3' => 'Délibération',
                '4' => 'Convocation',
                '5' => 'Ordre du jour',
                '6' => 'PV sommaire',
                '7' => 'PV détaillé',
                '8' => 'Recherche',
                '9' => 'Multi-séance',
            );

            $sql = "";
            $variables = array();

            if (($handle = fopen($this->request->data['Csv']['file']['tmp_name'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                    if ($row == 0) {
                        $row++;
                        continue;
                    }
                    $row++;
                    if (!empty($data[0])) {
                        $variable_name = str_replace(' ', '', trim(addslashes($data[0])));
                        if (array_search($variable_name, $variables)){
                            $variable_id = array_search($variable_name, $variables);
                        }else{
                            $variable_id = $cpt;
                            $cpt++;
                            $insert_variable[] = "INSERT INTO modelvariables (id,name,description,created,modified) VALUES ('$variable_id','" . $variable_name . "','" . trim(str_replace("'", "''", $data[1])) . "',now(),now());";
                            $variables[$variable_id] = $variable_name;
                        }
                        $section = array_search($data[8], $insert_section) ? array_search($data[8], $insert_section) : 1;
                        //type toute edition
                        //$insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 1, $section, 0, 0);";
                        if (!empty($data[2])) //colonne projet
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 2, $section, 0, 0);";
                        if (!empty($data[3])) //colonne convocation
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 4, $section, 0, 0);";
                        if (!empty($data[4])) //colonne odj
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 5, $section, 0, 0);";
                        if (!empty($data[5])) //colonne deliberation
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 3, $section, 0, 0);";
                        if (!empty($data[6])) { //colonne PV
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 6, $section, 0, 0);";
                            $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 7, $section, 0, 0);";
                        }
                        //type recherche
                        $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 8, $section, 0, 0);";
                        //type multi-séance
                        $insert_join[] = "INSERT INTO modelvalidations (modelvariable_id, modeltype_id, modelsection_id, min, max) VALUES ($variable_id, 9, $section, 0, 0);";
                    }
                }
                fclose($handle);
                $sql .= "\n-- Insertion des sections de modèle\n";
                foreach ($insert_section as $id => $sect) {
                    $sql .= "INSERT INTO modelsections (id,parent_id,name,description,created,modified) VALUES ('$id','1','$sect','Itérations sur les $sect',now(),now());\n";
                }
                $sql .= "\n-- Définition des sections de modèle\n";
                $sql .= "-- type Toutes éditions (id:1)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'1','2','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'1','3','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'1','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'1','5','0','2');\n";
                $sql .= "-- type Projet (id:2)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'2','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'2','5','0','2');\n";
                $sql .= "-- type Délibération (id:3)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'3','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'3','5','0','2');\n";
                $sql .= "-- type Convocation (id:4)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'4','2','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'4','5','0','2');\n";
                $sql .= "-- type ODJ (id:5)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'5','2','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'5','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'5','5','0','2');\n";
                $sql .= "-- type PV Sommaire (id:6)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'6','2','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'6','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'6','5','0','2');\n";
                $sql .= "-- type PV Détaillé (id:7)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'7','2','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'7','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'7','5','0','2');\n";
                $sql .= "-- type Recherche (id:8)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'8','2','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'8','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'8','5','0','2');\n";
                $sql .= "-- type Multi-séance (id:9)\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'9','2','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'9','3','1','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'9','4','0','1');\n";
                $sql .= "INSERT INTO modelvalidations (modelvariable_id,modeltype_id,modelsection_id,min,max) VALUES (null,'9','5','0','2');\n";

                $sql .= "\n-- Insertion des variables de modèle\n";
                foreach ($insert_variable as $var) {
                    $sql .= "$var\n";
                }
                $sql .= "-- Insertion des liaisons entre {types, variables, sections} de modèle\n";
                foreach ($insert_join as $join) {
                    $sql .= "$join\n";
                }

                header("text/sql");
                header("Content-Length: " . count($sql));
                header("Content-Disposition: attachment; filename=create.sql");
                echo $sql;
                exit;
            } else {
                $this->Session->setFlash("Erreur lors de l'ouverture du fichier", 'growl', array('type' => 'error'));
                $this->redirect(array('action' => 'csvtosql'));
            }
        }
    }
} 