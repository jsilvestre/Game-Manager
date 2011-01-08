<?php

/**
 * BaseCarte
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_carte
 * @property string $nom
 * @property string $description
 * @property Doctrine_Collection $Batiment
 * @property Doctrine_Collection $Criminel
 * @property Doctrine_Collection $Joueur
 * @property Doctrine_Collection $Pnj
 * @property Doctrine_Collection $Sol
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCarte extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('carte');
        $this->hasColumn('id_carte', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('nom', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('description', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Batiment', array(
             'local' => 'id_carte',
             'foreign' => 'id_carte'));

        $this->hasMany('Criminel', array(
             'local' => 'id_carte',
             'foreign' => 'id_carte'));

        $this->hasMany('Joueur', array(
             'local' => 'id_carte',
             'foreign' => 'id_carte'));

        $this->hasMany('Pnj', array(
             'local' => 'id_carte',
             'foreign' => 'id_carte'));

        $this->hasMany('Sol', array(
             'local' => 'id_carte',
             'foreign' => 'id_carte'));
    }
}