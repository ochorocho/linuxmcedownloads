<?php
namespace Linuxmce\Linuxmcemaps\Domain\Repository;

/**
 * Class UserRepository
 *
 * @package Linuxmce\Linuxmcemaps\Domain\Repository
 */
 
class UserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    protected $defaultOrderings = array(
        'fullname' => 'ASC'
    );
   
}