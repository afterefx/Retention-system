<?php
   /**
     * Takes a user name or user type of a user and gets all of the users from
     the user reg table. Then it builds a table and echos it back to the page
     that calls it. Then it will display all the data that will be needed to
     approve or deny a user or all users of a type. This page is used with the
     admin function to approve users. Another functionality of it is it
     dynamicaly loads the page and does not need to refresh the page to get the
     correct data.
     *
     * @author Bradley Friemel, <bfriemel@islander.tamucc.edu>
     * @params q
     */

require_once '../includes/backend.php';
$p = $_POST["q"];
//$p = "Vendor";
$stack = $Mubo->userRegistration->getUsersByType($p);

echo "<table id =\"adminTable\">
<tr>
<th>      </th>
<th>      </th>
<th>      </th>
</tr>";
//puts the approve all at the top since it ca be a long list

  echo "<tr>";
  echo "<td>" . "</td>";
 // echo "<td>" . $p . "</td>";
  echo "<td>" ."<a href='admin.php?page=user&option=approve&username=". $p .
  "'>Approve All</a>" . "</td>";
  echo "<td>" . "<a href='admin.php?page=user&option=deny&username=". $p . "'>Deny
  All</a>" . "</td>";
  echo "</tr>";
//while($row = mysql_fetch_array($result))
    foreach($stack as $entry)
  {
  echo "<tr>";
  echo "<td>" . $entry . "</td>";
 // echo "<td>" . $p . "</td>";
  echo "<td>" ."<a href='admin.php?page=user&option=approve&username=". $entry . "'>Approve</a>" . "</td>";
  echo "<td>" . "<a href='admin.php?page=user&option=deny&username=". $entry . "'>Deny</a>" . "</td>";
  echo "</tr>";
  }
echo "</table>";

?> 
