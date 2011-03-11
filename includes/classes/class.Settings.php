<?php
require_once 'class.Strings.php';

error_reporting(E_ALL);

/**
 * Uses xml
 *
 * @author firstname and lastname of author, <author@example.org>
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */

/* user defined constants */

/**
 * This is the settings object, most of the settings if not all
 * are retrieved from the xml document in the includes folder
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class Settings
{
    // --- OPERATIONS ---

    /**
     * Returns the url for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the url for the database back
     */
    public function getDbUrl()
    {
      $dbUrlString = new Strings("includes/muboStrings.xml");
      return $dbUrlString->getStringByName("dburl");
    }
    /**
     * Returns the url for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the username for the database back
     */
    public function getDbUsername()
    {
      $dbUsernameString = new Strings("includes/muboStrings.xml");
      return $dbUsernameString->getStringByName("dbusername");
    }

    /**
     * Returns the password for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the password for the database back
     */
    public function getDbPassword()
    {
      $dbPasswordString = new Strings("includes/muboStrings.xml");
      return $dbPasswordString->getStringByName("dbpassword");
    }

    /**
     * Returns the database for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the database for the database back
     */
    public function getDatabase()
    {
      $databaseString = new Strings("includes/muboStrings.xml");
      return $databaseString->getStringByName("database");
    }


    /**
     * Returns the database for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the database for the database back
     */
    public function getTitle()
    {
      $titleString = new Strings("includes/muboStrings.xml");
      return $titleString->getStringByName("Title");
    }


    /**
     * Returns the database for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the database for the database back
     */
    public function getSiteTitle()
    {
      $siteTitleString = new Strings("includes/muboStrings.xml");
      return $siteTitleString->getStringByName("Site Title");
    }


    /**
     * Returns the database for the database
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return sends the database for the database back
     */
    public function getSlogan()
    {
      $getSloganString = new Strings("includes/muboStrings.xml");
      return $getSloganString->getStringByName("Slogan");
    }

} /* end of class Settings */

?>
