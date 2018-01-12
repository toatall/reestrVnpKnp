<?php
    
    /** ***********************************************
     * Аудит действий пользователя
     * Версия: 1.0
     * Дата создания: 29.07.2015
     * Дата изменения: -
     * ---------------------------
     * Переменные:
     *      $table - таблица в которой выполняется хранение информации
     * 
     * 
     * ---------------------------
     * Автор: Трусов Олег Алексеевич, _alexeevich_@lsit.ru
     * ************************************************/
     
     /** 
       
        CREATE TABLE [dbo].[arr_audit](
        	[id] [int] IDENTITY(1,1) NOT NULL,
        	[operation] [int] NOT NULL,
        	[model] [varchar](50) NULL,
        	[model_id] [int] NULL,
        	[user_ip] [varchar](20) NULL,
        	[user_host] [varchar](50) NULL,
        	[user_name] [varchar](50) NULL,
        	[user_fio] [varchar](150) NULL,
        	[user_platform] [varchar](20) NULL,
        	[user_browser] [varchar](20) NULL,
        	[user_browser_version] [varchar](5) NULL,
        	[user_win64] [bit] NULL,
        	[user_win32] [bit] NULL,
        	[user_agent_str] [varchar](500) NULL,
        	[date_create] [datetime] NULL,
            CONSTRAINT [PK_arr_audit_1] PRIMARY KEY CLUSTERED 
            (
        	   [id] ASC
            ) WITH (PAD_INDEX  = OFF, 
                STATISTICS_NORECOMPUTE  = OFF, 
                IGNORE_DUP_KEY = OFF, 
                ALLOW_ROW_LOCKS  = ON,
                 ALLOW_PAGE_LOCKS  = ON) 
            ON [PRIMARY]
        ) ON [PRIMARY]        
        GO
       
      * **/
    
    class CAuditComponent extends CApplicationComponent
    {
                        
        const TYPE_OPERATION_LOGIN = 1;
        const TYPE_OPERATION_DB_INSERT = 2;
        const TYPE_OPERATION_DB_UPDATE = 3;
        const TYPE_OPERATION_DB_DELETE = 4;
        const TYPE_OPERATION_EXPORT = 5;
        
        const TYPE_OPERATION_LOGIN_TEXT = 'Вход';
        const TYPE_OPERATION_DB_INSERT_TEXT = 'Вставка';
        const TYPE_OPERATION_DB_UPDATE_TEXT = 'Изменение';
        const TYPE_OPERATION_DB_DELETE_TEXT = 'Удаление';
        const TYPE_OPERATION_EXPORT_TEXT = 'Экспорт';                
        
        // имя таблицы
        public $table = 'audit';                
        
        
        
        public function writeAudit($operation, $model=null, $id=null)
        {            
           $this->insertDb($operation,$model,$id);                        
        }
        
        
        private function getBrowser() 
        { 
            $u_agent = $_SERVER['HTTP_USER_AGENT']; 
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version= "";
        
            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
            }
            elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                $platform = 'mac';
            }
            elseif (preg_match('/windows|win32/i', $u_agent)) {
                $platform = 'windows';
            }
            
            // Next get the name of the useragent yes seperately and for good reason
            if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
            { 
                $bname = 'Internet Explorer'; 
                $ub = "MSIE"; 
            } 
            elseif(preg_match('/Firefox/i',$u_agent)) 
            { 
                $bname = 'Mozilla Firefox'; 
                $ub = "Firefox"; 
            } 
            elseif(preg_match('/Chrome/i',$u_agent)) 
            { 
                $bname = 'Google Chrome'; 
                $ub = "Chrome"; 
            } 
            elseif(preg_match('/Safari/i',$u_agent)) 
            { 
                $bname = 'Apple Safari'; 
                $ub = "Safari"; 
            } 
            elseif(preg_match('/Opera/i',$u_agent)) 
            { 
                $bname = 'Opera'; 
                $ub = "Opera"; 
            } 
            elseif(preg_match('/Netscape/i',$u_agent)) 
            { 
                $bname = 'Netscape'; 
                $ub = "Netscape"; 
            } 
            
            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }
            
            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                    $version= $matches['version'][0];
                }
                else {
                    $version= $matches['version'][1];
                }
            }
            else {
                $version= $matches['version'][0];
            }
            
            // check if we have a number
            if ($version==null || $version=="") {$version="?";}
            
            return array(
                'userAgent' => $u_agent,
                'name'      => $bname,
                'version'   => $version,
                'platform'  => $platform,
                'pattern'    => $pattern
            );
        }
        
        private function insertDb($operation, $model, $idModel)
        {            
            //$user_browse_array = get_browser(null, true); 
            $user_browse_array = $this->getBrowser();
            
            
            Yii::app()->db->createCommand()->insert($this->table, array(
                'operation' => $operation,
                'model' => $model,
                'model_id' => $idModel,
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'user_host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'user_name' => Yii::app()->user->name,
                //'user_fio' => Yii::app()->user->description,
                'user_platform' => $user_browse_array['platform'],
                'user_browser' => $user_browse_array['name'],
                'user_browser_version' => $user_browse_array['version'],
                //'user_win64' => $user_browse_array['win64'],
                //'user_win32' => $user_browse_array['win32'],
                'user_agent_str' => $_SERVER['HTTP_USER_AGENT'],
                'date_create' => new CDbExpression('getdate()'),
            ));
        }
        
        
        
        
        
        
        
        
    }