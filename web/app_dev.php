<?php
// This file is mainly here for use with symfony server commands.
// Recommended rewrite rules for apache and nginx does not use this.

// If you don't want to setup permissions the proper way (in dev), just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.

putenv('SYMFONY_ENV=dev');

require 'app.php';
