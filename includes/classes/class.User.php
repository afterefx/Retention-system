<?php

error_reporting(E_ALL);

/**
 * Mubo - class.User.php
 *
 *
 * This file is part of Mubo.
 *
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This is the main object that contains the backend
 *
 * @author Christopher Carlisle
 * @since 2010-10-13
 */
require_once('class.Mubo.php');

/* user defined includes */

/* user defined constants */

/**
 * Holds all of the information about the user
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class User
{
    // --- ATTRIBUTES ---
    private $userid; //userid for the current user
    private $username; //username for the current user
    private $firstName; //first name for the user
    private $lastName; //last name for the user
    private $email; //email for the user
    private $lastModified; //the time the user was last modified
    private $modifiedBy; //username of user that modified the current user
    private $roleID; //roleID of the user that is logged in
    private $zipcode; //zip code for the current user
    private $birthdate; //birthday for the current user

    // --- OPERATIONS ---//{{{1

    /**
     * Instantiates an user object.
     * The class needs the database object to make sql queries,
     * settings object to query certain settings and common object
     * to hash passwords and other functions for the class
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _db, _settings, _common
     */
    public function __construct($_db, $_settings, $_common)
    {//{{{2
        $this->db = $_db;
        $this->settings = $_settings;
        $this->common = $_common;
    }//}}}2

    // Getters and setters{{{2

    //getters {{{3
    /**
     * Returns the birthdate
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return birthdate
     */
    public function getBirthDate()
    { return $this->birthdate; }

    /**
     * Returns the userid
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return userid
     */
    public function getUserID()
    { return $this->userid ; }

    /**
     * Returns the username
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return username
     */
    public function getUsername()
    { return $this->username ; }

    /**
     * Returns the firstname of the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return firstName
     */
    public function getFirstName()
    { return $this->firstName ; }

    /**
     * Returns the lastname of the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return lastName
     */
    public function getLastName()
    { return $this->lastName ; }

    /**
     * Returns the email of the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return email
     */
    public function getEmail()
    { return $this->email ; }

    /**
     * Returns the time that the user was last modified
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return lastModified
     */
    public function getLastModified()
    { return $this->lastModified ; }

    /**
     * Returns the name of the person that
     * last modified the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return modifiedBy
     */
    public function getModifiedBy()
    { return $this->modifiedBy ; }

    /**
     * Returns the role id of the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return roleID
     */
    public function getRoleID()
    { return $this->roleID ; }

    /**
     * Returns the zipcode of the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return zipcode
     */
    public function getZipcode()
    { return $this->zipcode ; }

    //}}}3  end getters ======

    //{{{3 setters
    /**
     * Sets the userid
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _userid
     */
    public function setUserid($_userid)
    { $this->userid  = $_userid; }

    /**
     * Sets the username
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _username
     */
    public function setUsername($_username)
    { $this->username = $_username ; }

    /**
     * Sets the firstname for the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _firstName
     */
    public function setFirstName($_firstName)
    { $this->firstName = $_firstName ; }

    /**
     * Sets the lastname for the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _lastName
     */
    public function setLastName($_lastName)
    { $this->lastName = $_lastName ; }

    /**
     * Sets the email for the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _email
     */
    public function setEmail($_email)
    { $this->email = $_email ; }

    /**
     * Sets last modified time
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _lastModified
     */
    public function setLastModified($_lastModified)
    { $this->lastModified = $_lastModified ; }

    /**
     * Sets modified by. This is the person that modified the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _modifiedBy
     */
    public function setModifiedBy($_modifiedBy)
    { $this->modifiedBy = $_modifiedBy ; }

    /**
     * Sets the role id
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params _roleID
     */
    public function setRoleID($_roleID)
    { $this->roleID = $_roleID ; }

    /**
     * Sets the zipcode for the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params zipcode
     */
    public function setZipcode($_zipcode)
    { $this->zipcode = $_zipcode ; }

    /**
     * Sets the birthdate for the user
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @params birthdate
     */
    public function setBirthdate($_birthdate)
    { $this->birthdate = $_birthdate ; }

    //}}}3 end setters =====

    // -- end getters and setters }}}2

    /**
     * Loads the users information into the object
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  username
     */
    public function loadUserByUserName($username)
    {
        $sql = sprintf("SELECT * FROM users WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database");
        else
        {
            $row = mysql_fetch_array($result);
            $this->setUsername($username);
            $this->setFirstName($row['fname']);
            $this->setLastName($row['lname']);
            $this->setEmail($row['email']);
            $this->setUserid($row['userid']);
            $this->setLastModified($row['modified']);
            $this->setModifiedBy($row['modifiedBy']);
        }
    }

    /**
     * Loads an user into an instantiated object by the userid provided
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return userid
     */
    public function loadUsersByID($userid)
    {
        $sql = sprintf("SELECT * FROM users WHERE userid=%s",
                mysql_real_escape_string($userid));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database");
        else
        {
            $row = mysql_fetch_array($result);
            $this->setUsername($row['username']);
            $this->setFirstName($row['fname']);
            $this->setLastName($row['lname']);
            $this->setEmail($row['email']);
            $this->setUserid($userid);
            $this->setLastModified($row['modified']);
            $this->setModifiedBy($row['modifiedBy']);
        }
    }

    /**
     * Checks to see if the username is already in the
     * table and returns a bool
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@kestrel.tamucc.edu
     * @param  username
     * @return mixed
     */
    public function checkForUser($username)
    {
        $sql = sprintf("SELECT * FROM users WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query the database");

        if(mysql_num_rows($result) > 0)
            return true;
        else
            return false;
    }
    //-- end operations -- }}}1

} /* end of class User */

?>
