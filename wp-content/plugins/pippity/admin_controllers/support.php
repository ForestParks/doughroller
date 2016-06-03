<?php

/*
 * Controller for 'Main' Admin Page
 ***********************************/

global $pty;
$fname = isset($pty->user['fname']) ? $pty->user['fname'] : '' ;
$lname = isset($pty->user['lname']) ? $pty->user['lname'] : '' ;
$email = isset($pty->user['email']) ? $pty->user['email'] : '' ;
