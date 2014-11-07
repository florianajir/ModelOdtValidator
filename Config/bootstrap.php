<?php
/**
 * Fichier de configuration du plugin ModelOdtValidator
 */

/**
 * ModelOdtValidator.type : identifiants des types de modèle
 */
define('MODEL_TYPE_TOUTES',         1);
define('MODEL_TYPE_PROJET',         2);
define('MODEL_TYPE_DELIBERATION',   3);
define('MODEL_TYPE_CONVOCATION',    4);
define('MODEL_TYPE_ODJ',            5);
define('MODEL_TYPE_PVSOMMAIRE',     6);
define('MODEL_TYPE_PVDETAILLE',     7);
define('MODEL_TYPE_RECHERCHE',      8);
define('MODEL_TYPE_MULTISEANCE',    9);

/**
 * ModelOdtValidator.section : identifiants des sections de modèle
 */
define('MODEL_SECTION_DOCUMENT',    1);
define('MODEL_SECTION_PROJETS',     2);
define('MODEL_SECTION_SEANCES',     3);
define('MODEL_SECTION_AVISPROJET',  4);
define('MODEL_SECTION_ANNEXES',     5);

/**
 * Définit dans quelle application est intégré le plugin
 * (gestion des cas particuliers)
 */
Configure::write('APP_CONTAINER', 'WEBDELIB');
