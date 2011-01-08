<?php

// at this point, all the core libraries have been loaded but nothing has been done yet

// add the CSS files you want to use
GameManager::getInstance()->library('configuration')->addCss(array('base'));

// add the javascript files you want to use
GameManager::getInstance()->library('configuration')->addJavascript(array('jquery-1.4.2.min','linking'));


// set up the authentificator
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_CLASS,'Joueur');
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_ID_FIELD,'id_joueur');
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_LOGIN_FIELD,'pseudo');
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_PASSWORD_FIELD,'password');
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_ENCRYPT,Authentificator::ENCRYPT_SHA1);
GameManager::getInstance()->library('authentificator')->setConf(Authentificator::AUTH_SALT,'myOwnSalt');


// why not adding the current user data in the session in order to do one SQL request ?
// we should ask the database to find the user whose id is session->getData('user')->Authentificator::AUTH_ID_FIELD
// then serialize the data and add it to the session library
// add it as a hook

