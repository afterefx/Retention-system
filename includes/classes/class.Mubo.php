<?php

error_reporting(E_ALL);

/**
 * This is the main object that contains the backend
 *
 * @author Christopher Carlisle
 * @since 2010-10-13
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

require_once('class.Common.php');
require_once('class.DatabaseManager.php');
require_once('class.Page.php');
require_once('class.Session.php');
require_once('class.User.php');
require_once('class.UserRegistration.php');
require_once('class.Settings.php');


/* user defined includes */

/* user defined constants */

/**
 * This is the main object that contains the backend
 *
 * @access public
 * @author Christopher Carlisle
 * @since 2010-10-13
 */
class Mubo
{
    // --- ATTRIBUTES ---
    public $settings ;
    public $common ;
    public $db ;
    public $session ;
    public $page ;
    public $userRegistration ;

    // --- OPERATIONS ---

    /**
     * Short description of method __constructor
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
	 * @modifiedBy Jacob Hadden, <jhadden@islander.tamucc.edu>
     * @modified 11/04/10
     * @return mixed
     */
    public function __construct()
    {
        $settings = new Settings();
        $common = new Common();
        $db = new DatabaseManager($settings);
        $session = new Session($db, $settings, $common);
        $page = new Page($session);
        $userRegistration = new UserRegistration($db);

        $this->settings = $settings;
        $this->common = $common;
        $this->db = $db;
        $this->session = $session;
        $this->page = $page;
        $this->userRegistration = $userRegistration;
    }

    /**
     * Detects the type of device the system is being used from
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return mixed
     */
    public function detectDeviceType()
    {
    }

} /* end of class Mubo */

?>
