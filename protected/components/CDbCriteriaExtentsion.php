<?php

    class CDbCriteriaExtentsion extends CDbCriteria
    {
        
        // общие 
        const FILTER_NOT         = 0;   const FILTER_NOT_LABEL = 'без условий';
        const FILTER_EQUAL       = 1;   const FILTER_EQUAL_LABEL = 'равно (=)';
        const FILTER_NOT_EQUAL   = 2;   const FILTER_NOT_EQUAL_LABEL = 'не равно (#)';
        const FILTER_ON_LIST     = 3;   const FILTER_ON_LIST_LABEL = 'из перечня (1/2/3)';
        const FILTER_NOT_ON_LIST = 4;   const FILTER_NOT_ON_LIST_LABEL = 'вне перечня #(1/2/3)';
        const FILTER_NULL        = 5;   const FILTER_NULL_LABEL = 'пусто';
        const FILTER_NOT_NULL    = 6;   const FILTER_NOT_NULL_LABEL = 'не пусто';
       
        // для чисел и даты
        const FILTER_MORE        = 7;   const FILTER_MORE_LABEL = 'больше (>)';
        const FILTER_LESS        = 8;   const FILTER_LESS_LABEL = 'меньше (<)';
        const FILTER_MORE_EQUAL  = 9;   const FILTER_MORE_EQUAL_LABEL = 'не меньше (>=)';
        const FILTER_LESS_EQUAL  = 10;  const FILTER_LESS_EQUAL_LABEL = 'не больше (<=)';
        const FILTER_RANGE       = 11;  const FILTER_RANGE_LABEL = 'в диапазоне (от ...до ...)';
        
        // для текста
        const FILTER_BEGIN       = 12;  const FILTER_BEGIN_LABEL = 'начинается (текст...)';
        const FILTER_END         = 13;  const FILTER_END_LABEL = 'заканчивается (...текст)';
        const FILTER_CONTAINS    = 14;  const FILTER_CONTAINS_LABEL = 'содержит (...текст...)';
        const FILTER_NOT_CONTAINS= 15;  const FILTER_NOT_CONTAINS_LABEL = 'не содержит';
                
                
        /*        
        public function compare($column, $value, $partialMatch=false, $isString=false, $operator='AND', $escape=true)
        {            
            if ($value == '=')
            {               
                return $this->addCondition("(".$column." is null".($isString ? " OR ".$column."=''" : "").")");
            }
            elseif ($value == '<>')
            {
                return $this->addCondition("(".$column." is not null".($isString ? " OR ".$column."<>''" : "").")");
            }
            else
            {            
                return parent::compare($column, $value, $partialMatch, $operator, $escape);
            }               
        }
        */        
        
        public function addMyCondition($query)
        {            
            return $this->addCondition($query);
        }
        
        public function compare($column, $condition, $value, $isString=false, $partialMatch=false, $operator='AND', $escape=true)
        {   
            if (!($condition==self::FILTER_NULL || $condition==self::FILTER_NOT_NULL) && trim($value)=='')
                return parent::compare($column, $value, $partialMatch, $operator, $escape);
            
            switch ($condition)
            {
                case self::FILTER_NOT: return $this;
                
                case self::FILTER_EQUAL:                    
                    return $this->addCondition($column." = :".$column)
                        ->params += array(":".$column => $value);                                                                
                
                case self::FILTER_NOT_EQUAL:
                    return $this->addCondition($column." <> :".$column)
                        ->params += array(":".$column => $value);  
                
                case self::FILTER_ON_LIST:
                    return $this->addInCondition($column, explode('/',$value));
                    
                case self::FILTER_NOT_ON_LIST:
                    return $this->addNotInCondition($column, explode('/',$value));
               
               case self::FILTER_NULL:
                    return $this->addCondition($column." is null".(($isString) ? " OR RTRIM(LTRIM(".$column."))=''": ""));  
               
               case self::FILTER_NOT_NULL:
                    return $this->addCondition($column." is not null".(($isString) ? " OR RTRIM(LTRIM(".$column."))<>''": ""));  
                
               case self::FILTER_MORE:                    
                    return $this->addCondition($column." > :".$column)
                        ->params += array(":".$column => $value); 
                
                case self::FILTER_LESS:                    
                    return $this->addCondition($column." < :".$column)
                        ->params += array(":".$column => $value); 
                                                
                case self::FILTER_MORE_EQUAL:                    
                    return $this->addCondition($column." >= :".$column)
                        ->params += array(":".$column => $value); 
                
                case self::FILTER_LESS_EQUAL:                    
                    return $this->addCondition($column." <= :".$column)
                        ->params += array(":".$column => $value);         
                        
                case self::FILTER_RANGE:
                    return $this->addBetweenCondition($column, explode('/',$value)[0], explode('/',$value)[1]);    
                        
                case self::FILTER_BEGIN:
                    return $this->addSearchCondition($column, $value.'%', false);       
                        
                case self::FILTER_END:
                    return $this->addSearchCondition($column, '%'.$value, false);                   
                
                case self::FILTER_CONTAINS:
                    return parent::compare($column, $value, true);
                
                case self::FILTER_NOT_CONTAINS:
                    return $this->addSearchCondition($column, $value, true, 'AND', 'NOT LIKE');                   
                            
                default:
                    return parent::compare($column, $value, $partialMatch, $operator, $escape);
            }
            
            return $this;
        }
        
        
        
        public function compareRangeDates($column, $valueFrom, $valueTo)
        {
            if ($valueFrom=='') { 
                return $this; 
            }
            
            if ($valueFrom == '=')
            {
                //return $this->addCondition("(".$column." is null OR ".$column."='')");
                return $this->addCondition("(".$column." is null"." OR ".$column."='')");
            }
            elseif ($valueFrom == '<>')
            {
                //return $this->addCondition("(".$column." is not null OR ".$column."<>''");
                return $this->addCondition("(".$column." is not null"." OR ".$column."<>'')");
            }
            elseif (preg_match('/^(<>|<=|>=|<|>|=)(.*)$/',$valueFrom))
            {
                return parent::compare($column, $valueFrom, true);
            }
            
            return $this->addCondition($column." BETWEEN '".$valueFrom."' AND '"
                .((trim($valueTo)<>'') ? $valueTo : $valueFrom)."'");
        }
        
        
        public function filterNumeric()
        {
            return array(
                self::FILTER_NOT => self::FILTER_NOT_LABEL,
                self::FILTER_EQUAL => self::FILTER_EQUAL_LABEL,
                self::FILTER_NOT_EQUAL => self::FILTER_NOT_EQUAL_LABEL,
                self::FILTER_MORE => self::FILTER_MORE_LABEL,
                self::FILTER_LESS => self::FILTER_LESS_LABEL,
                self::FILTER_MORE_EQUAL => self::FILTER_MORE_EQUAL_LABEL,
                self::FILTER_LESS_EQUAL => self::FILTER_LESS_EQUAL_LABEL,
                self::FILTER_RANGE  => self::FILTER_RANGE_LABEL,     
                self::FILTER_ON_LIST => self::FILTER_ON_LIST_LABEL,
                self::FILTER_NOT_ON_LIST => self::FILTER_NOT_ON_LIST_LABEL,
                self::FILTER_NULL => self::FILTER_NULL_LABEL,
                self::FILTER_NOT_NULL => self::FILTER_NOT_NULL_LABEL,                  
            );
        }
        
        public function filterString()
        {
            return array(
                self::FILTER_NOT => self::FILTER_NOT_LABEL,
                self::FILTER_EQUAL => self::FILTER_EQUAL_LABEL,
                self::FILTER_NOT_EQUAL => self::FILTER_NOT_EQUAL_LABEL,
                self::FILTER_BEGIN => self::FILTER_BEGIN_LABEL,
                self::FILTER_END => self::FILTER_END_LABEL,
                self::FILTER_CONTAINS => self::FILTER_CONTAINS_LABEL,
                self::FILTER_NOT_CONTAINS => self::FILTER_NOT_CONTAINS_LABEL,
                self::FILTER_ON_LIST => self::FILTER_ON_LIST_LABEL,
                self::FILTER_NOT_ON_LIST => self::FILTER_NOT_ON_LIST_LABEL,
                self::FILTER_NULL => self::FILTER_NULL_LABEL,
                self::FILTER_NOT_NULL => self::FILTER_NOT_NULL_LABEL,                  
            );
        }
        
    }