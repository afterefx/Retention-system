<?php
require_once 'class.Strings.php';

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
 * Generates the web page for the device and loads appropriate stylesheet
 *
 * @access public
 * @author Christophoer Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class Page
{
    // --- ATTRIBUTES ---
    private $notification;
    private $extraHeaderItems;
    private $session;
    private $footText;
    private $headText;
    private $pageText;
    private $sideLinks;

    // --- OPERATIONS ---

    public function __construct($_session)
    { $this->session = $_session; }

    private function getNotification()
    { return $this->notification; }

    public function getSideLinks()
    {}

    private function getTitle()
    {
        $titleString = new Settings();
        return $titleString->getTitle();
    }

    private function getSiteTitle()
    {
        $siteTitleString = new Settings();
        return $siteTitleString->getSiteTitle();
    }

    private function getSiteSlogan()
    {
        $sloganString= new Settings();
        return $sloganString->getSlogan();
    }

    private function getExtraHeader()
    { return $this->extraHeaderItems; }

    private function getHeader()
    {//{{{2
        $header =<<<HTML
            <!DOCTYPE html>
            <html>
            <head>
            <title>
HTML;

        $header.= $this->getTitle();//Get the title for the current page

        $header.=<<<HTML
            </title>
            <link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
HTML;

        $header .= $this->getExtraHeader();

        $header.="</head>";

        return $header;

    }//}}}2

    private function getMenuItems($title)
    {//{{{2

        //send the appropriate string for the title that is sent in
        $home = ($title == "Home") ? 'class="current"' : '';
        $reports = ($title == "Reports") ? 'class="current"' : '';
        $importStudents = ($title == "Import Students") ? 'class="current"' : '';
        $admin = ($title == "Administration") ? 'class="current"' : '';

        $page=<<<HTML
            <li><a $home  href="index.php" title="Home">HOME</a></li>
            <li><a $reports href="#" title="Reports">REPORTS</a></li>
            <li><a $importStudents href="#" title="Import Students">IMPORT STUDENTS</a></li>
HTML;
        //check for an administrator to decide whether or not to give the
        //administrator page
        if($this->session->isAdministrator())
            $page.="<li><a $admin href=\"admin.php\" title=\"Administration\">ADMINISTRATION</a></li>";
        return $page;
    }//}}}2

    /**
     * Returns content for footer to be displayed on the webpage. Grabs content
     * footer from the strings table. It also grabs any additional content that
     * to be inserted into the footer before it is delivered to the webpage.
     *
     * @access private
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return mixed
     */
    private function getFooter()
    {//{{{2
        return <<<HTML
            <div id="footer">
            <div class="right">
            &copy; Copyright 2010, TAMUCC - Center for Academic Student Achievement -
            <a href="http://www.templatesold.com/" target="_blank">Website Templates</a>
            by <a href="http://www.free-css-templates.com/" target="_blank">Free CSS Templates</a> <br />
            </div>
            </div>
HTML;
    }//}}}2

    /**
     * Returns the html for the login area
     *
     * @access private
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return mixed
     */
    private function getLoginArea()
    {//{{{2
        //if a session exists then tell the user Welcome in the login area
        //and give them a login area
        if($this->session->sessionExists())
        {
            $name = $this->session->user->getFirstName();
            $page=<<<HTML
                <h3>Welcome</h3>
                Hello <a href="profile.php">$name</a><br />
                <a href="logout.php">logout</a>
HTML;

        }
        //if a session does not exist then show the login area
        else
        {
            $server = $_SERVER['PHP_SELF'];
            $page=<<<HTML
                <h3>Login or <a href="register.php">register</a></h3>
                <form action="login.php?page=$server" method="post">
                Username:<br />
                <input type="text" name="user" /><br />
                Password:<br />
                <input type="password" name="pass" /><br />
                <input type="checkbox" name="remember" />Remember me
                <span id="loginButton"><input type="submit" value="Login" /></span>
                </form>
                <a href="#">Forgot password...</a>
HTML;
        }
        return $page; //return whatever was put into the page
    }//}}}2

    //{{{1 setters
    /**
     * Receives text and appends it to the footer text (footText) of this
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  content
     * @return mixed
     */
    public function setFooter($content)
    {
    }

    /**
     * Receives text and appends it to the header text (headText) of this
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  text
     * @return mixed
     */
    public function setHeader($text)
    { $this->extraHeaderItems=$text; }

    /**
     * Receives text and appends it to the notification text (notificationText)
     * this instance.
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@kestrel.tamucc.edu>
     * @param  Text to be appended to page's current notification text (notificationText)
     */
    public function setNotification($text)
    {
        if(isset($this->notification))
            $this->notification .= "<br />" . $text;
        else
            $this->notification =  $text;

    }

    public function setSideLink($sideLink)
    {
        if(!isset($this->sideLinks))
            $this->sideLinks = array(); 
        array_push($this->sideLinks, $sideLink);
    }

    /**
     * Constructs a webpage for the appropriate device
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  page, title
     */
    public function displayPage($page, $title)
    {//{{{2

        //get the top part of the page, aka the headers for the webpage
        $echo = $this->getHeader();

        //--- Top portion of page

        //visible part of the webpage starts here
        $echo .=<<<HTML

            <body>

            <div class="content">
            <div id="top">
            <div id="icons">
HTML;
        //get the icons for the top right of the page

        $echo .= "</div>";
        $echo .="<h1>" . $this->getSiteTitle() . "</h1>"; //insert the site title
        $echo .="<h2>" . $this->getSiteSlogan() . "</h2>"; //insert the site slogan

        //---Menu starts here ----

        //end the top portion of the webpage and start creating the menu
        $echo .= <<<HTML
            </div>

            <div id="menu">
            <ul>
HTML;

        $echo .= $this->getMenuItems($title);

        $echo .=<<<HTML
            </ul>
            </div>
HTML;

        //this is the middle area of the webpage
        //--first we will create the right sidebar
        $echo .=<<<HTML
            <div id="main">
            <div id="right_side">
            <div class="pad">
HTML;

        //getting the appropriate content for whether or not the user is logged in
        $echo .= $this->getLoginArea();

        $echo .= $this->getSideLinks();
        $echo.=<<<HTML
            <h3>Categories</h3>
            <ul>
            <li><a href="#">bob</a></li>

            </ul>

            </div>
            </div>
HTML;

        //this is where the left hand side of the webpage starts
        $echo .=<<<HTML
            <div id="left_side">
            <div class="intro">
            <div class="pad">
HTML;
        $echo .=  $this->getNotification(); //this is where we place the notification for the user if we need any

        $echo .=<<<HTML
            </div>
            </div>

            <div class="mpart">
HTML;

        $echo .= $page;

        //now we end the main area of the lefthand side of the page
        $echo .=<<<HTML
            </div>
            </div>
            </div>
HTML;

        $echo .= $this->getFooter();

        $echo .=<<<HTML
            </div>
            </body>
            </html>
HTML;
        echo $echo;
    }//}}}2



} /* end of class Page */

?>
