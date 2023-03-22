<?php

$fullUrl="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (!defined('errorcheck')) {
    exit('ok man have a nice day now! :)');
}else if(defined("login"))
{
    switch(true)
    {
        case stristr($fullUrl,"error=alreadyLoggedIn"):
            echo '<div class="error">Already logged in.</div>';break;
        case stristr($fullUrl,"error=EmptyFields"):
            echo '<div class="error">Empty fields.</div>';break;
        case stristr($fullUrl,"error=pwdWrong"):
            echo '<div class="error">Password is wrong.</div>';break;
        case stristr($fullUrl,"error=badUsername"):
            echo '<div class="error">Username does not exist.</div>';break;
        case stristr($fullUrl,"error=databaseconnectionerror"):
            echo '<div class="error">Server error.</div>';break;
        case stristr($fullUrl,"success=registration"):
            echo '<div class="success">Registered.</div>';break;
            default:break;
    }
}
else if(defined("registration"))
{
    switch(true)
    {
        case stristr($fullUrl,"error=EmptyFields"):
            echo '<div class="error">Empty fields.</div>';break;
        case stristr($fullUrl,"error=invalidmailoruser"):
            echo '<div class="error">Email or username is not valid.</div>';break;
        case stristr($fullUrl,"error=invailidemail"):
            echo '<div class="error">Email not valid.</div>';break;
        case stristr($fullUrl,"error=passworddoesnotmatch"):
            echo '<div class="error">Passwords do not match.</div>';break;
        case stristr($fullUrl,"error=invaliduser"):
            echo '<div class="error">Username not valid.</div>';break;
        case stristr($fullUrl,"error=nameOrEmailAlreadyExists"):
            echo '<div class="error">Username or email already exists.</div>';break;
        case stristr($fullUrl,"error=sqlError"):
            echo '<div class="error">Server error.</div>';break;
        case stristr($fullUrl,"success=true"):
            echo '<div class="error success">Success.</div>';break;
            default:break;
    }
}


