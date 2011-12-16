<?php

/**
 * Mubo - admin.php
 *
 *
 * This file is part of Mubo.
 *
 * Bradley Friemel, <bfriemel@island.tamucc.edu>
 * Description: This is the admin page that deals with all of the admin
 features. It has links on it to the other parts of the admin functions. Then
 when one is clicked it returns the value to itself then loads the proper
 page. It has access to the approve user, remove session, and settings page.
 The approve user is where a user would be approved or denyed. This would just
 have to do with the user registration page. Then the remove session page is a
 way to log out a user. Settings page is a page that changs some of the
 information on the web page
 **/
require_once 'includes/backend.php';

//check to make sure current user has a role of an administrator
if($Mubo->session->isAdministrator() != 1)
    $Mubo->common->redirect("index.php");

//figures out if the page to display is set to user
if(isset($_GET['page']) && $_GET['page']=="approveuser")
{//{{{2
    if(isset($_GET['option']))
    {//{{{3
        $option = $_GET['option'];
        if(isset($_GET['username']))
        {
            $username = $_GET['username'];
            if($option == "approve")
            {
                switch($username)
                {
                    case "User":
                        $Mubo->userRegistration->approveAllUserType("User");
                        break;
                    case "Manager":
                        $Mubo->userRegistration->approveAllUserType("Manager");
                        break;
                    case "Administrator":
                        $Mubo->userRegistration->approveAllUserType("Administrator");
                        break;
                    case "All":
                        $Mubo->userRegistration->approveAllUserType("All");
                        break;
                    default:
                        $email =  $Mubo->userRegistration->approveUser($username);
                        break;
                }
           }
           if($option == "deny")
            {
                 switch($username)
                {
                    case "User":
                        $Mubo->userRegistration->denyAllUserType("User");
                        break;
                    case "Manager":
                        $Mubo->userRegistration->denyAllUserType("Manager");
                        break;
                    case "Administrator":
                        $Mubo->userRegistration->denyAllUserType("Administrator");
                        break;
                    case "All":
                        $Mubo->userRegistration->denyAllUserType("All");
                        break;
                    default:
                        $Mubo->userRegistration->denyUser($username);
                        break;
                }
            }
        }
    }//}}}3

    $header =<<<JAVASCRIPT
    <script type="text/javascript">
    function showUser(str)
    {
        if (str=="")
            return;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","requestHandler/getUserByRole.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("q="+str);
    }
    </script>
JAVASCRIPT;

    $Mubo->page->setHeader($header);
    $page =<<<HTML
        <form>
            <select name="users" id="users" onchange="showUser(this.value)">
                <option value="">Select a user type:</option>
                <option value="User">Users</option>
                <option value="Manager">Manager</option>
                <option value="Administrator">Administrators</option>
                <option value="All">All</option>
            </select>
        </form>

        <div id="txtHint">Users waiting approval<br /></div>
HTML;
}//}}}2

//figures out if the page is set to display the default page
elseif(isset($_POST['action']) && $_POST['action']=="Edit")
{
    //get new user object here
    editUser();
}
else
{//{{{2
    $page = "Welcome Admin <br />";
    $page .= displaySessions($Mubo);
    $server = $_SERVER['PHP_SELF'];
    $page .= <<<HTML
        <table>
            <tr>
            <th>Manage Users</th>
            </tr>
            <tr>
              <td>
              <form action="$server" method="post">
                  <select>
                      <option>---</option>
HTML;

    $usernameStack = $Mubo->session->user->getAllUserNames();
    foreach($usernameStack as $anUser)
    {
        $page .=<<<HTML
            <option value="$anUser">$anUser</option>
HTML;
    }
$page .= <<<HTML
                  </select>
              </td>
              <td>
                  <input type="button" name="action" value="Edit" /><input type="button" name="action" value="Delete" />
                </form>
              </td>
           </tr>
            <tr>
              <td> <a href="admin.php?page=approveuser">Approve Users</a> </td>
           </tr>
           <tr>
           <td><a href="settings.php">Settings</a></td>
           </tr>
       </table>
HTML;
}//}}}2

$Mubo->page->displayPage($page,"Administration");


function displaySessions($Mubo)
{//{{{1

    $server = $_SERVER["PHP_SELF"];
    if(isset($_GET['session']))
        $Mubo->session->logoutSession($_GET['session']);
    $TableData = $Mubo->session->getAllActiveSessions();
    $page = <<<HTML
        <table id = 'adminTable'>
            <tr>
                <th> User Name </th>
                <th> IP Address </th>
                <th> Date Created </th>
                <th> Last Seen </th>
                <th> </th>
            </tr>
HTML;
    foreach($TableData as $dRow)
    {
        $UserName = $dRow->getUserName();
        $IpAddress = $dRow->getIpAddress();
        $DateCreated = date('n/d/y  g:i:s T',$dRow->getDateCreated());
        $LastSeen = date('n/d/y g:i:s T',$dRow->getLastSeen());
        $Token = $dRow->getSessionKey();
        $page .=<<<HTML
            <tr>
                <td> $UserName </td>
                <td> $IpAddress </td>
                <td> $DateCreated </td>
                <td> $LastSeen </td>
                <td> <a href ="admin.php?page=session&username=$UserName&session=$Token">Logout user</a> </td>
            </tr>
HTML;
    }
    $page.= "</table>";
    return $page;
}//}}}1
///=============================================



