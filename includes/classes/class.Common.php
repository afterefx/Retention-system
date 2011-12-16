<?php

error_reporting(E_ALL);

/**
 * Mubo - class.Common.php
 *
 *
 * This file is part of Mubo.
 *
 * @author: Bradley Friemel, <bfriemel@islander.tamucc.edu>
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
 * Short description of class Common
 *  This class contains all the functions that wil be used accross the
 *     pages.
 *
 * @access public
 * @author: Bradley Friemel, <bfriemel@islander.tamucc.edu>
 */
class Common
{
    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * encrypt()
     *  Takes text and encrypts it with sha512 then returns it
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $text
     * @return encrypted text
     */
    public function encrypt($text)
    { return hash("sha512",$text); }

    /**
     * hash()
     *  Takes the string and passes it to the encrypt function and returns it
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $text
     * @return encrypted text
     */
    public function hash($text)
    { return $this->encrypt($text); }

     /**
     * redirect()
     *    sends header location of the page to be redirected to
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params $url
     */
    public function redirect($url)
    { header("Location: $url"); }

    /**
     * Generates an alphanumeric string that is 200 characters long.
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return a token that is unique
     */
    public function generateToken()
    {
        $number = time(); //this will give us a number that never repeats
        $token = $this->encrypt($number);
        return $token;
    }

    /**
     * Takes a file name and a message. The message is written to the file that
     *  was given
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params fileName , message
     * @return nothing
     */
    public function appendToFile($fileName, $message)
    {
        $handler = fopen($fileName,'a');//open the file in an append mode
        fwrite($handler, "\n". $message); //write the passed message to the file
        fclose($handler); //close the file
    }
} /* end of class Common */

?>
