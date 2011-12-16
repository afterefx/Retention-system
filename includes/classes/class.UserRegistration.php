<?php

error_reporting(E_ALL);

/**
 * Mubo - class.UserRegistration.php
 *
 *
 * This file is part of Mubo.
 *
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
 * Short description of class UserRegistration
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu> and Bradley Friemel, <bfriemel@islander.tamucc.edu>
 */
class UserRegistration
{
    // --- ATTRIBUTES ---
    private $db;
    private $common;

    // --- OPERATIONS ---
    public function __construct($_db)
    { $this->db = $_db; }

    /**
     * Creates a token for the user and inserts into the database. It emails the
     * the token and how to confirm their account. To ensure the token is
     * completely unique we append the username onto the end and then hash
     * the string again.
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  username
     * @return mixed
     */
    public function createToken($username)
    {
        $obj = new Common();
        $string = $obj->generateToken();
        $string .= $username;//append string
        return $obj->hash($string);//rehash
    }

    /**
     * Confirms the user in the user registration table and activates the user
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  token
     * @return mixed
     */
    public function confirmToken($token)
    {
        $sql=sprintf("SELECT * from  userRegistration WHERE token ='%s'",
            mysql_real_escape_string($token));
        $result = $this->db->query($sql);
        if($result===FALSE)
            die("Could not find token in database");
        $row = mysql_fetch_array($result);
        return $this->approveUser($row['username']);
    }

    /**
     * Adds the user to the user registration table to await confirmation or
     * approval of account
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  token
     * @return mixed
     */
    public function addUser($time, $email, $username, $password, $fname, $lname, $role)
    {
        //create a token for this registration
        $token = $this->createToken($username);
        $obj = new Common();
        $password = $obj->hash($password);

        //insert into table
        $sql=sprintf("INSERT INTO userRegistration
                (token,username,role,fName,lName,password,email) VALUES
                ('%s','%s','%s','%s','%s','%s','%s')",
                mysql_real_escape_string($token),
                mysql_real_escape_string($username),
                mysql_real_escape_string($role),
                mysql_real_escape_string($fname),
                mysql_real_escape_string($lname),
                mysql_real_escape_string($password),
                mysql_real_escape_string($email));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not insert user into database");

        if($result)
            return true;
        else
            return false;

    }

    /**
     * Takes a user name that is in the user reg table and creates
     * a entry in the user table and in the role table. This allows
     * the user to log into the system
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userName
     */
    public function approveUser($userName)
    {//{{{1
        //get the max user id
        $sql ="SELECT MAX(userid) FROM users";
        $result = $this->db->query($sql);
        if($result === FALSE)
             die("Could not query database1");
        if(mysql_num_rows($result) > 0)
        {
            $array = mysql_fetch_array($result);
            $userID = $array[0]+1;
        }
        //create row in role table
        $sql = sprintf("SELECT * FROM userRegistration WHERE username = '%s'",
                mysql_real_escape_string($userName));

        $result = $this->db->query($sql);

        if($result ===FALSE)
            die("Could not query database2");

        if(mysql_num_rows($result) == 1)
        {
           //found the only user to have that name
           $row = mysql_fetch_array($result);
           $userName = $row['username'];
           $fName = $row['fName'];
           $lName = $row['lName'];
           $password = $row['password'];
           $email = $row['email'];
           $role = $row['role'];
           $user = 0;
           $manager = 0;
           $admin = 0;
           switch($role)
           {
             case "user":
                   $user = 1;
                   $manager = 0;
                   $admin = 0;
                   break;
              case "manager":
                   $user = 0;
                   $manager = 1;
                   $admin = 0;
                   break;
              case "administrator":
                   $user = 0;
                   $manager = 0;
                   $admin = 1;
                   break;

           }

           $sql = sprintf("INSERT INTO users
                   (userid,username,password,fname,lname,email,createdOn,modified,modifiedBy,birthDate,enabled,questionID,secretAnswer,lastLogIn) VALUES
                   (%d,'%s','%s','%s','%s','%s',%d,%u,'%s',%d,%d,%u,'%s',%d)",
                  $userID, $userName,$password,$fName,$lName,$email,time(),1," ",1,1,1," ",1);

           $result = $this -> db ->query($sql);

           if($result === FALSE)
           {
               echo($userID);
               die("Insertion of user failed1");
           }

           $sql = sprintf("INSERT INTO role VALUES (%d,%u,%u,%u)",
               $userID,$user,$manager,$admin);

           $result = $this->db->query($sql);

           if($result === FALSE)
           { die("Insertion of user failed2"); }

           $this->deleteUser($userName);

           return true;
        }
    }//}}}1

    /**
     * Takes a user name and calls the delete user function
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userName
     */
    public function denyUser($userName)
    { $this->deleteUser($userName); }

    /**
     * Takes a user name and removes it from the user reg table.
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userName
     */
    public function deleteUser($userName)
    {//{{{2
        $sql = sprintf("DELETE FROM userRegistration WHERE username ='%s'", mysql_real_escape_string($userName));
        $result = $this->db->query($sql);
        return $result;
    }//}}}2

    /**
     * Takes a user type and queries the user reg table to get all of
     *  that type. Then calles the deny user function for all the usernames.
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userType
     */
    public function denyAllUserType($userType)
    {
        switch($userType)
        {
            case "User":
                $sql = "SELECT username from userRegistration WHERE role = 'user'";
                break;
            case "Manager":
                $sql = "SELECT username from userRegistration where role = 'manager'";
                break;
           case "Administrator":
                $sql = "SELECT username FROM userRegistration WHERE role = 'administrator'";
                break;
                default:
                $sql = "SELECT username FROM userRegistration";
                break;
        }

        $result = $this->db->query($sql);

        if($result == FALSE)
            die("Could not query database");

        $stack = array();
        while($row = mysql_fetch_array($result))
        {
            $value = $row['username'];

            $this->denyUser($value);
        }
        return $stack;
    }

    /**
     * Takes a user type and selects all the type from the user reg table.
     *  Then it calls the approve user function for each user name.
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userName
     */
    public function approveAllUserType($userType)
    {
        switch($userType)
        {
            case "User":
                $sql = "SELECT username from userRegistration WHERE role = 'user'";
                break;
            case "Manager":
                $sql = "SELECT username from userRegistration where role = 'manager'";
                break;
           case "Administrator":
                $sql = "SELECT username FROM userRegistration WHERE role = 'administrator'";
                break;
                default:
                $sql = "SELECT username FROM userRegistration";
                break;
        }

        $result = $this->db->query($sql);

        if($result == FALSE)
            die("Could not query database");

        while($row = mysql_fetch_array($result))
        {
            $value = $row['username'];

            $this->approveUser($value);
        }
    }

   /**
     * Takes a user type and gets all the users for that type from the user
     *  reg table.
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $userType
     */
    public function getUsersByType($userType)
    {//{{{2
        switch($userType)
        {
            case "User":
                $sql = "SELECT username from userRegistration WHERE role = 'user'";
                break;
            case "Manager":
                $sql = "SELECT username from userRegistration where role = 'manager'";
                break;
            case "Administrator":
                $sql = "SELECT username FROM userRegistration WHERE role = 'administrator'";
                break;
            default:
                $sql = "SELECT username FROM userRegistration";
                break;
        }
        $result = $this->db->query($sql);

        if($result == FALSE)
            die("Could not query database");

        $stack = array();
        while($row = mysql_fetch_array($result))
        {
            $value = $row['username'];

            array_push($stack,$value);
        }
        return $stack;
    }//}}}2

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
        $sql = sprintf("SELECT * FROM userRegistration WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query the database");

        if(mysql_num_rows($result) > 0)
            return true;
        else
            return false;
    }
}
?>
