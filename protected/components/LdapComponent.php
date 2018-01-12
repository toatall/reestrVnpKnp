<?php

    Yii::import('application.vendor.adLDAP.src.adLDAP');
    
    class LdapComponent extends adLDAP {
        
        public $baseDN;
        public $accountSuffix;
        public $domainControllers;
        public $adminUsername;
        public $adminPassword;
        
        public function __construct() {
            
        }
        
        public function init() {
            parent::__construct();
        }
        
    }