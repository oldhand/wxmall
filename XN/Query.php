<?php 
if (! class_exists('XN_Query')) {
class XN_Query 
{
    const EQ = '=';
    const NE = '<>';
    const EIC = 'eic';
    const NEIC = 'neic';
    const LT = '<';
    const LE = '<=';
    const GT = '>';
    const GE = '>=';
    const LIKE = 'like';
    const LIKEIC = 'likeic';
    const IN = 'in';
    const NIN = '!in';

   
    const SUBJECT_CONTENT = 'Content';   
	const SUBJECT_CONTENT_COUNT = 'Content_Count'; 
    const SUBJECT_BIGCONTENT = 'BigContent';       
    const SUBJECT_BIGCONTENT_COUNT = 'BigContent_Count';	
    const SUBJECT_MESSAGE = 'Message';       
    const SUBJECT_MESSAGE_COUNT = 'Message_Count'; 
	const SUBJECT_MAINCONTENT = 'MainContent'; 
    const SUBJECT_MAINCONTENT_COUNT = 'MainContent_Count';
	const SUBJECT_YEARCONTENT = 'YearContent'; 
    const SUBJECT_YEARCONTENT_COUNT = 'YearContent_Count';	
	const SUBJECT_YEARMONTHCONTENT = 'YearmonthContent'; 
    const SUBJECT_YEARMONTHCONTENT_COUNT = 'YearmonthContent_Count';	 
	const SUBJECT_PROFILE_COUNT = 'Profile_Count';
	const SUBJECT_MQ = 'Mq';    
    const SUBJECT_APPLICATION = 'Application';
	const SUBJECT_PROFILE = 'Profile';

    private $subject;
    private $returnIds = false;
    private $begin = 0;
    private $tag = "";
    private $end;
    /** alwaysReturnTotalCount starts off as true so we can detect
     * when a caller explicitly changes it */
    private $alwaysReturnTotalCount = true;
    private $orders = array();
    private $filters = array(
                             'tag' => array(),
                             'rollup' => array(),
    						 'group' => array(),
						 );
    private $resultFrom;
    private $resultTo;
    private $totalCount; 
   
    public static function create($subject)
	{
        return new XN_Query($subject);
    }
    private function __construct($subject)
	{
        if (strcasecmp($subject ,self::SUBJECT_CONTENT) != 0 &&
			strcasecmp($subject, self::SUBJECT_CONTENT_COUNT) != 0 &&
            strcasecmp($subject, self::SUBJECT_BIGCONTENT) != 0 &&
            strcasecmp($subject, self::SUBJECT_BIGCONTENT_COUNT) != 0 &&
			strcasecmp($subject, self::SUBJECT_MAINCONTENT) != 0 &&
            strcasecmp($subject, self::SUBJECT_MAINCONTENT_COUNT) != 0 &&
			strcasecmp($subject, self::SUBJECT_YEARCONTENT) != 0 &&
            strcasecmp($subject, self::SUBJECT_YEARCONTENT_COUNT) != 0 &&
			strcasecmp($subject, self::SUBJECT_YEARMONTHCONTENT) != 0 &&
            strcasecmp($subject, self::SUBJECT_YEARMONTHCONTENT_COUNT) != 0 &&
			strcasecmp($subject, self::SUBJECT_PROFILE_COUNT) != 0 &&
			strcasecmp($subject, self::SUBJECT_MQ) != 0 &&
            strcasecmp($subject, self::SUBJECT_MESSAGE) != 0 &&
            strcasecmp($subject, self::SUBJECT_MESSAGE_COUNT) != 0 &&
            strcasecmp($subject ,self::SUBJECT_APPLICATION) != 0 &&
			strcasecmp($subject ,self::SUBJECT_PROFILE) != 0 ) 
		{
            throw new XN_Exception("Invalid query subject: $subject. Only 'Content','Content_Count','Application' are supported");
        }
        $this->subject = $subject;
    }     
    public function filter() 
	{
        $args = func_get_args();
        $argc = func_num_args();
        if ($argc == 0) 
		{
            trigger_error('Missing argument(s) for XN_Query::filter()', E_USER_WARNING);
        } 
        else if (($argc == 1) && ($args[0] instanceof XN_Query_InternalType_FilterClause)) 
		{
        	if ((strcasecmp($this->subject,'Content') != 0) &&
				(strcasecmp($this->subject,'MainContent') != 0) &&
				(strcasecmp($this->subject,'YearContent') != 0) &&
				(strcasecmp($this->subject,'YearmonthContent') != 0) &&
                (strcasecmp($this->subject,'Profile') != 0) &&
                (isset($this->strategyImplementor) &&
                 (! $this->strategyImplementor->subclausesAreAllowed()))) 
			{
                throw new XN_IllegalArgumentException("Sub-clauses are not allowed on {$this->subject} queries.");
            }
            if (isset($this->strategyImplementor)) 
			{
                $tree = $args[0]->_toFilterTree($this->strategyImplementor);
                $this->strategyImplementor->acceptFilterTree($tree);
            } 
			else 
			{
                $tree = $args[0]->_toFilterTree($this->subject);
                $this->filters['content'][] = $tree;
            }
        }
        else 
		{
            $prop = $args[0];
            $operator = isset($args[1]) ? $args[1] : null;
            $value    = isset($args[2]) ? $args[2] : null;
            $type     = isset($args[3]) ? $args[3] : null;
            XN_Filter::_verify($prop, $operator, $value, $type);
            if (strncasecmp($this->subject, 'Content',7)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }
			else if (strncasecmp($this->subject, 'MainContent',11)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }
			else if (strncasecmp($this->subject, 'YearContent',11)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }			
			else if (strncasecmp($this->subject, 'YearmonthContent',15)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }			
         	else if (strncasecmp($this->subject, 'BigContent',10)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }
         	else if (strncasecmp($this->subject, 'Message',7)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }
        	else if (strncasecmp($this->subject, 'Mq',2)==0){
                $this->_addContentFilter($prop, $operator, $value, $type);
            }        	
            else if (strcasecmp($this->subject, 'Application')==0){
                $this->_addApplicationFilter($prop, $operator, $value, $type);
            }
			else if (strncasecmp($this->subject, 'Profile',7)==0){
                $this->_addProfileFilter($prop, $operator, $value, $type);
            }
            else if (isset($this->strategyImplementor)) {
                $this->strategyImplementor->filter($prop, $operator, $value, $type);
            }
        }
        return $this;
    } 
    public function order($prop, $direction = null, $type='string')
	{
        if (strcasecmp($this->subject, 'Content')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }
		else if (strcasecmp($this->subject, 'Profile')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }
     	else if (strcasecmp($this->subject, 'Content_Count')==0){
            $this->orders[] = $this->_createContentOrder( $prop, $direction, $type);
        }
     	else if (strcasecmp($this->subject, 'Profile_Count')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }
		else if (strcasecmp($this->subject, 'MainContent')==0){
            $this->orders[] = $this->_createContentOrder( $prop, $direction, $type);
        }
		else if (strcasecmp($this->subject, 'MainContent_Count')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }
		else if (strcasecmp($this->subject, 'YearContent')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }		
		else if (strcasecmp($this->subject, 'YearContent_Count')==0){
            $this->orders[] = $this->_createContentOrder( $prop, $direction, $type);
        }		
		else if (strcasecmp($this->subject, 'YearmonthContent')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }		
		else if (strcasecmp($this->subject, 'YearmonthContent_Count')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }		
        else if (strcasecmp($this->subject, 'BigContent')==0){
            $this->orders[] = $this->_createContentOrder($prop, $direction, $type);
        }     	
		else if (strcasecmp($this->subject, 'Mq')==0){
            $this->orders[] = $this->_createContentOrder( $prop, $direction, $type);
        }  
		else if (strcasecmp($this->subject, 'Message')==0){
            $this->orders[] = $this->_createContentOrder( $prop, $direction, $type);
        }  
        else if (strcasecmp($this->subject,'Application') == 0) {
            $this->orders[] = $this->_createApplicationOrder($prop, $direction);
        } 
        else if (isset($this->strategyImplementor)) {
            $this->strategyImplementor->order($prop, $direction, $type);
        }       
        return $this;
    }    
    public function rollup($prop='') 
	{
        if (strcasecmp($this->subject, 'Content_Count') != 0 && 
		    strcasecmp($this->subject, 'MainContent_Count') != 0 && 
			strcasecmp($this->subject, 'YearContent_Count') != 0 && 
			strcasecmp($this->subject, 'YearmonthContent_Count') != 0 && 
		    strcasecmp($this->subject, 'Profile_Count') != 0 && 
			strcasecmp($this->subject, 'Message_Count') != 0 && 
		    strcasecmp($this->subject, 'BigContent_Count') != 0 ) {
            throw new XN_IllegalArgumentException("rollup() only supported for Content_Count queries");
        }
        if ($prop=='')
        {
	        if (isset($this->strategyImplementor)) 
			{
	            $this->strategyImplementor->alwaysReturnTotalCount(true);
	        } 
			else 
			{
	            $this->alwaysReturnTotalCount = true;
	        }
	        return $this;
        }
        $this->_addRollupFilter('field','=',$prop);
        return $this;
    }
/**
     * Specifies the rollup field for Content_Count queries
     *
     * @param $prop string the property to rollup on: contributor or type
     * @param $prop  世纪(@century) 年份域除以100  century  
     * @param $prop  日期(@day) (月分)里的日期域(1-31) day  
     * @param $prop  年代(@decade) 年份域除以10 decade  
     * @param $prop  星期几(@dow) 每周的星期号(0 - 6；星期天是 0)  dow 
     * @param $prop  年日(@doy) 一年的第几天(1 -365/366)  doy 
     * @param $prop  纪元(@epoch) epoch  
     * @param $prop  小时(@hour) 小时域 (0 - 23) hour  
     * @param $prop  微秒(@microseconds) 
     * @param $prop  千年(@millennium) 年域除以 1000
     * @param $prop  毫秒(@milliseconds) 秒域，包括小数部分，乘以 1000．请注意它包括完整的秒．
     * @param $prop  分钟(@minute) 分钟域 (0 - 59)
     * @param $prop  月份(@month) 它是一年里的月份数(1 - 12)； 
     * @param $prop  季度(@quarter) 该天所在的该年的季度(1 - 4)(仅用于 timestamp)
     * @param $prop  秒(@second) 秒域，包括小数部分 (0 - 59 [1])
     * @param $prop  周(@week) 从一个 timestamp 数值里计算该天在所在的年份里 是第几周．根据定义 (iso 8601)，一年的 第一周包含该年的一月四日．(iso 的周从星期一开始．) 换句话说，一年的第一个星期四在第一周．
     * @param $prop  年(@year) 年份域
     * @return XN_Query the XN_Query object
     */
    public function group($prop) 
	{
        if (strcasecmp($this->subject, 'Content_Count') != 0 && 
		    strcasecmp($this->subject, 'MainContent_Count') != 0 && 
			strcasecmp($this->subject, 'YearContent_Count') != 0 && 
			strcasecmp($this->subject, 'YearmonthContent_Count') != 0 && 
		    strcasecmp($this->subject, 'Profile_Count') != 0 && 
			strcasecmp($this->subject, 'Message_Count') != 0 && 
		    strcasecmp($this->subject, 'BigContent_Count') != 0) {
            throw new XN_IllegalArgumentException("group() only supported for Content_Count queries");
        }   
        $this->_addGroupFilter('field','=',$prop);
        return $this;
    }
    public function tag($tag)
	{
        $this->tag = $tag;
        return $this;
    }
    public function begin($begin)
	{
        if (isset($this->strategyImplementor)) 
		{
            $this->strategyImplementor->begin($begin);
        } 
		else
		{
            $this->begin = $begin;
        }
        return $this;
    }  
    public function end($end)
	{
        if (isset($this->strategyImplementor)) 
		{
            $this->strategyImplementor->end($end);
        } 
		else 
		{
            if (($end < $this->begin + 1) && ($end != -1)) 
			{
                if (! (($this->subject == self::SUBJECT_MEMBER) && ($this->begin == 0) && ($end == 0))) 
				{
                    throw new XN_IllegalArgumentException("Cannot set end position '".$end."' as it is less than the begin position '".$this->begin."' + 1");
                }
            }
            $this->end = $end;
        }
        return $this;
    }  
    public function alwaysReturnTotalCount($always=false)
	{
        if (isset($this->strategyImplementor)) 
		{
            $this->strategyImplementor->alwaysReturnTotalCount($always);
        } 
		else
		{
            $this->alwaysReturnTotalCount = $always;
        }
        return $this;
    }
    public function getAlwaysReturnTotalCount() 
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->getAlwaysReturnTotalCount();
        } 
		else 
		{
            return $this->alwaysReturnTotalCount;
        }
    }
    public function getResultFrom()
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->getResultFrom();
        } 
		else 
		{
            if (!isset($this->resultFrom))
                throw new XN_IllegalStateException("Cannot call getResultFrom() before the query is executed");
            return $this->resultFrom;
        }
    }   
    public function getResultTo()
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->getResultTo();
        } 
		else 
		{
            if (!isset($this->resultTo))
                throw new XN_IllegalStateException("Cannot call getResultTo() before the query is executed");
            return $this->resultTo;
        }
    }   
    public function getResultSize()
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->getResultSize();
        } 
		else 
		{
            if (!isset($this->resultTo))
                throw new XN_IllegalStateException("Cannot call getResultSize() before the query is executed");
            return $this->resultTo - $this->resultFrom;
        }
    }
    public function getTotalCount()
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->getTotalCount();
        } 
		else 
		{
            if (!isset($this->totalCount))
                throw new XN_IllegalStateException("Cannot call getTotalCount() before the query is executed");
            if ($this->alwaysReturnTotalCount !== true) 
			{
                throw new XN_IllegalStateException("Cannot call getTotalCount() if begin > 0 and alwaysReturnTotalCount is false");
            }
            return $this->totalCount;
        }
    }
    public function execute($returnIds=false) 
	{
        if (isset($this->strategyImplementor)) 
		{
            $this->strategyImplementor->setReturnIds($returnIds);
        } 
		else 
		{
            $this->returnIds = $returnIds;
        }
        return $this->_executeQuery();
    }

	public function chunk_execute($fieldname,$ids,$chunk=50) 
	{
		$lists = array();
		foreach ( array_chunk($ids,$chunk,true) as $chunk_ids)
		{
			$query = clone $this;
			$query->filter ($fieldname, 'in', $chunk_ids); 
			$lists = array_merge($lists,$query->execute()); 
		}
		if (count($this->orders) > 0)
		{
			$order = $this->orders[0]; 
			$property = str_replace("my.","",$order->getProperty());
			$sort = $order->getSort(); 	
			 
			//冒泡排序			
		    $len = count($lists);
	 	    //该层循环控制 需要冒泡的轮数
	 	    for($i=1;$i<$len;$i++)
	 	    { //该层循环用来控制每轮 冒出一个数 需要比较的次数
		 	       for($k=0;$k<$len-$i;$k++)
		 	       {
					   if ($sort == "D" || $sort == "D_N")
					   {
						   if (in_array($property,array("published","updated","author","title")))
						   {
				 	           if($lists[$k]->$property < $lists[$k+1]->$property)
				 	           {
				 	                $tmp=$lists[$k+1];
	  			 	                $lists[$k+1]=$lists[$k];
	  			 	                $lists[$k]=$tmp;
				 	           }
						   }
						   else
						   {
				 	           if($lists[$k]->my->$property < $lists[$k+1]->my->$property)
				 	           {
				 	                $tmp=$lists[$k+1];
	  			 	                $lists[$k+1]=$lists[$k];
	  			 	                $lists[$k]=$tmp;
				 	           }
						   }
			 	           
					   }
					   else
					   {
						   if (in_array($property,array("published","updated","author","title")))
						   {
				 	           if($lists[$k]->$property > $lists[$k+1]->$property)
				 	           {
				 	                $tmp=$lists[$k+1];
	  			 	                $lists[$k+1]=$lists[$k];
	  			 	                $lists[$k]=$tmp;
				 	           }
						   }
						   else
						   {
				 	           if($lists[$k]->my->$property > $lists[$k+1]->my->$property)
				 	           {
				 	                $tmp=$lists[$k+1];
	  			 	                $lists[$k+1]=$lists[$k];
	  			 	                $lists[$k]=$tmp;
				 	           }
						   } 
					   }
			 	           
		 	       }
	 	     } 
		} 
		return $lists;
	}
    public function debugString() 
	{
        if (isset($this->strategyImplementor)) 
		{
            $returnIds = $this->strategyImplementor->returnIds;
            $begin = $this->strategyImplementor->begin;
            $end = $this->strategyImplementor->end;
            $artc = $this->strategyImplementor->alwaysReturnTotalCount;
            $orders = $this->strategyImplementor->orders;
            $filters = $this->strategyImplementor->filters;
        } 
		else 
		{
            $returnIds = $this->returnIds;
            $begin = $this->begin;
            $end = $this->end;
            $artc = $this->alwaysReturnTotalCount;
            $orders = $this->orders;
            $filters = $this->filters;
        }

        $retval = "XN_Query:\n" .
            "  subject [".$this->subject."]\n".
            "  returnIds [". $returnIds ."]\n".
            "  begin [".$begin."]\n".
            "  end [".$end."]\n".
            "  alwaysReturnTotalCount [".$artc."]\n".
            "  orders [\n";
        foreach ($orders as $order) 
		{
            $retval.= "    ".$order->debugString()."\n";
        }
        $retval .= "  ]\n";
        $retval .= "  filters [\n";
        foreach ($filters as $filterType => $filters) {
            $retval .= self::_getSubtreeDebugString($filters);
        }
        $retval .= "\n  ]";
        return $retval;
    }
	public static function _prepareProfileFilter($prop, $operator, $value, $type = null) 
	{
        $prop = implode('.', explode('->', $prop));
        $lowerProp = strtolower($prop);
        $filterType = 'content';  
		if ($prop == "id")
		{
			if (is_array($value))
			{
				$newvalue = array();
				foreach($value as $childvalue)
				{
					$newvalue[] = "'".$childvalue."'";
				}
				$value = $newvalue;
			}
			else
			{
			    $value = "'".$value."'";
			}
		}
        return array('filterType' => $filterType,
                     'prop' => $prop,
                     'operator' => $operator,
                     'value' => $value,
                     'type' => $type);
	}
    public static function _prepareContentFilter($prop, $operator, $value, $type = null) 
	{
        $prop = implode('.', explode('->', $prop));
        $lowerProp = strtolower($prop);
        $filterType = 'content'; 
        return array('filterType' => $filterType,
                     'prop' => $prop,
                     'operator' => $operator,
                     'value' => $value,
                     'type' => $type);
    }
    public static function _prepareApplicationFilter($subject, $prop, $operator, $value, $type = null) 
	{
         $prop = str_replace('->','.',$prop);
         $lowerProp = strtolower($prop);
         if ((! self::$_applicationFilterMap[$lowerProp][$operator]) && ($lowerProp != 'popular'))
		 {
             throw new XN_Exception("Operator $operator not allowed with $lowerProp filter in Application query: only '" . implode("', '", array_keys(self::$_applicationFilterMap[$lowerProp])) . "'");
         }
         if ($value instanceof XN_Profile) 
		 {
             $value = $value->profileid;
         }
         else if (is_array($value)) 
		 {
             foreach ($value as $k => $v) 
			 {
                 if ($v instanceof XN_Profile) 
				 {
                     $value[$k] = $v->profileid;
                 }
             }
         }         
         if (($lowerProp != 'popular') && (is_null($value) || (mb_strlen($value) == 0))) 
		 {
             throw new XN_Exception("Value must be specified for $lowerProp filter in Application query.");
         }         
         $type = XN_Attribute::STRING; 
         return array('filterType' => 'application', 'prop' => $lowerProp, 'operator' => $operator, 'value' => $value, 'type' => $type);
    }
    private function _addContentFilter($prop, $operator, $value, $type = null)
	{
        if (is_array($filterInfo = self::_prepareContentFilter($prop, $operator, $value, $type))) 
		{ 
            $this->filters[$filterInfo['filterType']][] = new XN_Filter($filterInfo['prop'], $filterInfo['operator'],$filterInfo['value'],$filterInfo['type']);
        }
        return $this;
    }
	private function _addProfileFilter($prop, $operator, $value, $type = null)
	{
        if (is_array($filterInfo = self::_prepareProfileFilter($prop, $operator, $value, $type))) 
		{ 
            $this->filters[$filterInfo['filterType']][] = new XN_Filter($filterInfo['prop'], $filterInfo['operator'],$filterInfo['value'],$filterInfo['type']);
        }
        return $this;
    }
    private function _addApplicationFilter($prop, $operator, $value, $type = null) 
	{
        if (count($this->filters['application']) != 0) 
		{
            throw new XN_IllegalArgumentException("Only one filter allowed for Application queries");
        }
        if (is_array($filterInfo = self::_prepareApplicationFilter($this->subject, $prop, $operator, $value, $type))) 
		{
            $this->filters[$filterInfo['filterType']][] = new XN_Filter($filterInfo['prop'], $filterInfo['operator'],$filterInfo['value'],$filterInfo['type']);
        }
        return $this;
    }
    private function _addRollupFilter($prop, $operator, $value, $type = null) 
	{
        $prop = implode('.', explode('->', $prop));
        $lowerProp = strtolower($prop);
        $this->filters['rollup'][] = new XN_Filter($prop, $operator, $value, $type);
    }    
    private function _addGroupFilter($prop, $operator, $value, $type = null) 
	{
        $prop = implode('.', explode('->', $prop));
        $lowerProp = strtolower($prop);
        $this->filters['group'][] = new XN_Filter($prop, $operator, $value, $type);
    }
    private function _createContentOrder($prop, $direction, $type)
	{
        $prop = str_replace(' ','', $prop);
        $prop = str_replace('->','.', $prop);
        $lowerProp = strtolower($prop);
        return new XN_Order($prop, $direction, $type);
    }
    protected function _createApplicationOrder($prop, $direction) 
	{
        return new XN_Order($prop, $direction, null);
    }
    public function toEndpoint() 
	{
        if (isset($this->strategyImplementor)) 
		{
            return $this->strategyImplementor->toEndpoint();
        } 
        else 
		{
            return null;
        }
    }
    public function _toAtomEndpoint($host=true)
	{
        $atomEndpointHost = XN_AtomHelper::ENDPOINT_APP(XN_Application::$CURRENT_URL);
        $qsParams = array();
        $selectors = array('content' => array(),
                           'rollup' => array(),
         				   'group' => array(),
					   );
        $apps = array();
        $removeAppFilters = false;
      
        foreach ($this->filters as $filterCategory => $filters) 
		{
            $selectors[$filterCategory] = self::_getSubtreeSelector($filters, '&', $removeAppFilters);
        }

		if (strcasecmp($this->subject, self::SUBJECT_CONTENT) == 0) 
		{
		    if ($this->alwaysReturnTotalCount) 
			{
				$qsParams[] = 'count=true';
		    } 
			else {
				$qsParams[] = 'count=false';
		    }
		}
        $qsParams[] = 'xn_out=xml';
        $qsParams[] = 'from='.intval($this->begin);
        if (isset($this->end)) 
		{
            $qsParams[] = 'to='.$this->end;
        } 
		else 
		{
            $qsParams[] = 'to='.(100 + intval($this->begin));
        }
        foreach ($this->orders as $order) 
		{
            $qsParams[] = $order->_toQueryStringValue();
        }
		if (count($selectors['content']) == 0)
		{
			$selectors['content'] = "";
		}
		
        $url = "";

        if (strcasecmp($this->subject, self::SUBJECT_CONTENT) == 0) 
		{
            $url .= "/content({$selectors['content']})";
		} 
		else if (strcasecmp($this->subject, self::SUBJECT_MAINCONTENT) == 0) 
		{
            $url .= "/maincontent({$selectors['content']})";
		} 
		else if (strcasecmp($this->subject, self::SUBJECT_YEARCONTENT) == 0) 
		{
            $url .= "/yearcontent({$selectors['content']})";
		} 
		else if (strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT) == 0) 
		{
            $url .= "/yearmonthcontent({$selectors['content']})";
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT) == 0) 
		{
        	$url .= "/bigcontent({$selectors['content']})";
        }
		else if (strcasecmp($this->subject, self::SUBJECT_MESSAGE) == 0) 
		{
        	$url .= "/message({$selectors['content']})";
		} 
		else if (strcasecmp($this->subject, self::SUBJECT_MQ) == 0) 
		{
        	$url .= "/mq({$selectors['content']})";        	  
	    } else if (strcasecmp($this->subject, self::SUBJECT_PROFILE) == 0) 
		{
        	$url .= "/profile({$selectors['content']})";           
        } 
		else if ((strcasecmp($this->subject, self::SUBJECT_CONTENT_COUNT) == 0) && isset($selectors['group']))
		{
            $url .= "/content({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})"; 
        } 
		else if ((strcasecmp($this->subject, self::SUBJECT_PROFILE_COUNT) == 0) && isset($selectors['group']))
		{
            $url .= "/profile({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})"; 
		} 
		else if ((strcasecmp($this->subject, self::SUBJECT_MAINCONTENT_COUNT) == 0) && isset($selectors['group']))
		{
            $url .= "/maincontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})"; 
	    } 
		else if ((strcasecmp($this->subject, self::SUBJECT_YEARCONTENT_COUNT) == 0) && isset($selectors['group']))
		{
           $url .= "/yearcontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})";        
        } 
		else if ((strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT_COUNT) == 0) && isset($selectors['group']))
		{
           $url .= "/yearmonthcontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})";     
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_CONTENT_COUNT) == 0)
		{
            $url .= "/content({$selectors['content']})/rollup({$selectors['rollup']})";
		} 
		else if (strcasecmp($this->subject, self::SUBJECT_MAINCONTENT_COUNT) == 0)
		{
            $url .= "/maincontent({$selectors['content']})/rollup({$selectors['rollup']})";
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT_COUNT) == 0)
		{
            $url .= "/bigcontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})"; 
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_YEARCONTENT_COUNT) == 0)
		{
            $url .= "/yearcontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})";       
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT_COUNT) == 0)
		{
            $url .= "/yearmonthcontent({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})";        
        } 
		else if (strcasecmp($this->subject, self::SUBJECT_MESSAGE_COUNT) == 0)
		{
            $url .= "/message({$selectors['content']})/rollup({$selectors['rollup']})/group({$selectors['group']})";        
        } 
        $url .= '?'.implode('&',$qsParams);
		if ($host) {
            return $atomEndpointHost.$url;
        }
		else{
            return $url;
        }
    }
    public function _toNetworkSearchEndpoint() 
	{
        $url = 'http://' . XN_AtomHelper::HOST_APP(XN_Application::$CURRENT_URL) . '/xn/rest/1.0/application';		
        $selectors = array('application' => array());
        $removeAppFilters = false;
    	foreach ($this->filters as $filterCategory => $filters) 
		{
            $selectors[$filterCategory] = self::_getSubtreeSelector($filters, '&', $removeAppFilters);
        }        
        if (strlen($selectors['application'])) 
        {
            $url .= "({$selectors['application']})";
        } 
        else 
        {
         	$url .= "()";
        }
        $qsParams[] = 'from='.intval($this->begin);
        if (isset($this->end)) 
		{
            $qsParams[] = 'to='.$this->end;
        } 
		else 
		{
            $qsParams[] = 'to='.(100 + intval($this->begin));
        }
        if  (count($this->orders) == 0) 
		{
            $this->order('published','desc');
        }
        foreach ($this->orders as $order) 
		{
            $qsParams[] = $order->_toQueryStringValue();
        }
        $url .= '?xn_out=xml&' . implode('&',$qsParams);
        $this->alwaysReturnTotalCount = true;        
        return $url;
    } 
    private static function _getSubtreeSelector($filters, $operator, $removeAppFilters) 
	{
        $tmp = array();
        foreach ($filters as $filterOrSubtree) 
		{
            if ($filterOrSubtree instanceof XN_Filter) 
			{
                if ((! $removeAppFilters) || ($removeAppFilters && ($filterOrSubtree->getProperty() != 'owner.relativeUrl'))) 
				{
                    $tmp[] = rawurlencode($filterOrSubtree->_toSelectorValue());
                }
            }			
            else 
			{
                $tmp[] = '(' . self::_getSubtreeSelector(array_slice($filterOrSubtree, 1), $filterOrSubtree[0], $removeAppFilters). ')';
            }
        }
        return  implode(rawurlencode($operator), $tmp);
    } 
    private static function _getSubtreeDebugString($filters, $operator = '&', $level = 1) 
	{
        static $opWords = array('&' => 'AND', '|' => 'OR');
        $lines = array();
        foreach ($filters as $filterOrSubtree) 
		{
            if ($filterOrSubtree instanceof XN_Filter) 
			{
                $lines[] = str_repeat(' ',2 * $level) . $filterOrSubtree->debugString();
            }			
            else 
			{
                $lines[] = self::_getSubtreeDebugString(array_slice($filterOrSubtree, 1), $filterOrSubtree[0], $level + 1);
            }
        }
        return implode("\n" . str_repeat(' ', 2 * $level) . $opWords[$operator] . "\n", $lines);
    }
    private function _executeQuery()
	{
        if (isset($this->strategyImplementor)) 
		{
            try 
			{
                return $this->strategyImplementor->execute();
            } 
			catch (Exception $ex)
			{
                throw XN_Exception::reformat("Failed query:\n".$this->debugString(), $ex);
            }
        }
        try 
		{
            if (strcasecmp($this->subject, 'Application') == 0) 
            {
				$url = $this->_toNetworkSearchEndpoint();
				$headers = array();
				if ($this->tag != "")
			    {
					$headers['tag'] = $this->tag;
				}
				$rsp = XN_REST::get($url, $headers);
				$x = XN_AtomHelper::XPath($rsp);
				$this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
				return XN_AtomHelper::loadFromAtomFeed($rsp,'XN_Application',false);				
            }
			else if (strcasecmp($this->subject, 'Profile') == 0) 
            {
				$url = $this->_toAtomEndpoint();
				$headers = array();
				if ($this->tag != "")
			    {
					$headers['tag'] = $this->tag;
				}
				$rsp = XN_REST::get($url, $headers);
				$x = XN_AtomHelper::XPath($rsp);
				$this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
				return XN_AtomHelper::loadFromAtomFeed($rsp,'XN_Profile',false); 
            }
			else 
			{
                $version = '1.0';
                $headers = null;
                $url = $this->_toAtomEndpoint(false);
                if (strlen($url) < 8000)
                {
                    $atomEndpointHost = XN_AtomHelper::ENDPOINT_APP(XN_Application::$CURRENT_URL);
                    $url = $atomEndpointHost.$url;
                    $headers = array('tag' => $this->tag,);
                    $x = XN_AtomHelper::XPath(XN_REST::get($url, $headers), $version);
                }
                else
                {
                    $version = '1.0';
                    $atomEndpointHost = XN_AtomHelper::ENDPOINT_APP(XN_Application::$CURRENT_URL,'2.0');
                    $posturl = $atomEndpointHost."/postcontent";
                    $headers = array('tag' => $this->tag,);
                    $x = XN_AtomHelper::XPath(XN_REST::post($posturl, '/xn/rest/1.0'.$url,'text/xml',$headers), $version);
                }
                $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                $this->resultFrom = (integer) $this->begin;
                if ((strcasecmp($this->subject, self::SUBJECT_CONTENT) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;               
				}
				else  if ((strcasecmp($this->subject, self::SUBJECT_MAINCONTENT) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;
			    }
				else  if ((strcasecmp($this->subject, self::SUBJECT_YEARCONTENT) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                   $entries = $x->query('/atom:feed/atom:entry');
                   $this->resultTo = $this->resultFrom + $entries->length;		       
			    }
				else  if ((strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT) == 0) &&	($this->alwaysReturnTotalCount !== true)) 
				{
	                 $entries = $x->query('/atom:feed/atom:entry');
	                 $this->resultTo = $this->resultFrom + $entries->length;		       
                }
				else  if ((strcasecmp($this->subject, self::SUBJECT_BIGCONTENT) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;        
                }
				else  if ((strcasecmp($this->subject, self::SUBJECT_MESSAGE) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;        
				}
				else  if ((strcasecmp($this->subject, self::SUBJECT_MQ) == 0) && ($this->alwaysReturnTotalCount !== true)) 
				{
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;      
                }
				else  if (strcasecmp($this->subject, self::SUBJECT_CONTENT_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;
				}
				else  if (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;
				}
				else  if (strcasecmp($this->subject, self::SUBJECT_MESSAGE_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;
                }
				else  if (strcasecmp($this->subject, self::SUBJECT_PROFILE_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;              
				}
				else  if (strcasecmp($this->subject, self::SUBJECT_MAINCONTENT_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;
				}
				else  if (strcasecmp($this->subject, self::SUBJECT_YEARCONTENT_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;				
				}
				else  if (strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT_COUNT) == 0) 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    $entries = $x->query('/atom:feed/atom:entry');
                    $this->resultTo = $this->resultFrom + $entries->length;				
                }
				else 
				{
                    $this->totalCount = (integer) $x->textContent('/atom:feed/xn:size');
                    if ($this->end == 0) 
					{
                        $this->resultTo = $this->totalCount;
                    } 
					else 
					{
                        $this->resultTo   = min((integer) $this->end, $this->totalCount);
                    }
                }

                if ($this->returnIds == 'true') {
                    return self::_atomFeedToIDs($x);
                }
                else if (strcasecmp($this->subject, self::SUBJECT_CONTENT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
				else if (strcasecmp($this->subject, self::SUBJECT_MAINCONTENT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false,true,4);
                }
				else if (strcasecmp($this->subject, self::SUBJECT_YEARCONTENT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false,true,7);
                }				
				else if (strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false,true,9);
                }				
				else if (strcasecmp($this->subject, self::SUBJECT_PROFILE)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false,true,4);
                }				
            	else if (strcasecmp($this->subject, self::SUBJECT_CONTENT_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
            	else if (strcasecmp($this->subject, self::SUBJECT_PROFILE_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }				
				else if (strcasecmp($this->subject, self::SUBJECT_MAINCONTENT_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
				else if (strcasecmp($this->subject, self::SUBJECT_YEARCONTENT_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }				
				else if (strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }				
				else if (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
            	else if (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
				else if (strcasecmp($this->subject, self::SUBJECT_MESSAGE_COUNT)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
                }
            	else if (strcasecmp($this->subject, self::SUBJECT_MESSAGE)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false);
				}
				else if (strcasecmp($this->subject, self::SUBJECT_MQ)==0) 
				{
                    return XN_Atomhelper::loadFromAtomFeed($x, 'XN_Content', false,false,2);
                }            	
                else if ((strcasecmp($this->subject, self::SUBJECT_CONTENT_COUNT)==0)||
						(strcasecmp($this->subject, self::SUBJECT_PROFILE_COUNT)==0)||
						(strcasecmp($this->subject, self::SUBJECT_MAINCONTENT_COUNT)==0)||
						(strcasecmp($this->subject, self::SUBJECT_YEARCONTENT_COUNT)==0)||
						(strcasecmp($this->subject, self::SUBJECT_YEARMONTHCONTENT_COUNT)==0)||
		                (strcasecmp($this->subject, self::SUBJECT_BIGCONTENT_COUNT)==0))
				{
                    return self::_loadRollupFromAtomFeed($x);
                }                
            }
        } 
		catch (XN_Exception $ex)
		{        
            if (($ex->getCode() == 404) && (strcasecmp($this->subject, self::SUBJECT_SEARCH) != 0)) 
			{
                return array();
            } 
			else 
			{
                throw XN_Exception::reformat("Failed query:\n".$url.$this->debugString(), $ex);
            }
        } 
		catch (Exception $ex) 
		{
            throw XN_Exception::reformat("Failed query:\n".$url.$this->debugString(), $ex);
        }
    }
    private static function _atomFeedToIDs(XN_XPathHelper $x) 
	{
        $ids = array();
        foreach ($x->query('/atom:feed/atom:entry/atom:id') as $node) 
		{
            $ids[] = $node->textContent;
        }
        return $ids;
    }
    private static function _loadRollupFromAtomFeed(XN_XPathHelper $x) 
	{
        $rollup = array();
        foreach ($x->query('/atom:feed/xn:rollup') as $node) 
		{
            if (! is_null($key = XN_XPathHelper::attribute($node, 'key'))) 
			{
                $rollup[$key] = (integer) $node->textContent;
            }
        }
        return $rollup;
    }
}


class XN_Order {
    private $sort;
    private $property;
    private $type;

    const ASC = 'A';
    const ASC_NUMBER = 'A_N';
    const DESC = 'D';
    const DESC_NUMBER = 'D_N';

    private static $sorts = array(self::ASC => 'asc',  
    							  self::ASC_NUMBER => 'asc number',  		 
                                  self::DESC_NUMBER => 'desc number',  
                                  self::DESC => 'desc',  	        
							  );    
   
    public static function asc($property, $type="string")
	{
        return new XN_Order($property, self::ASC, $type);
    }   
    public static function desc($property, $type="string")
	{
        return new XN_Order($property, self::DESC, $type);
    }
   
    public function debugString() 
	{
        $str = $this->property." : ";
        if (isset(self::$sorts[$this->sort])) 
		{
            $str .= self::$sorts[$this->sort];
        }
        $str .= " : ".$this->type;
        return $str;
    }
    function isAscending(){  return ($this->sort == 'A'); }
    function getType(){ return $this->type; }   
    function getProperty(){ return $this->property; }
	function getSort(){ return $this->sort; }
    
    function __construct($property, $sort, $type)
	{
        $this->type = $type;
        $this->property = $property;        
        if (! is_null($sort)) {
            if ($type == 'number'){
	            if ($sort == 'asc') { $sort = self::ASC_NUMBER; }
	            else if ($sort == 'desc') { $sort = self::DESC_NUMBER; }
	            else if ($sort === true) { $sort = self::ASC_NUMBER; }
	            else if ($sort === false) { $sort = self::DESC_NUMBER; }
	            else if (! isset(self::$sorts[$sort])) { $sort = self::DESC_NUMBER; } 
            }
            else
            {
	            if ($sort == 'asc') { $sort = self::ASC; }
	            else if ($sort == 'desc') { $sort = self::DESC; }
	             else if ($sort === true) { $sort = self::ASC; }
	            else if ($sort === false) { $sort = self::DESC; }
	            else if (! isset(self::$sorts[$sort])) { $sort = self::DESC; }     	
            }
        } 
		else 
		{
            $sort = self::DESC; 
        }
        $this->sort = $sort;
    }
    function _toQueryStringValue() 
	{ 
        if (isset(self::$sorts[$this->sort])) 
		{
            return 'order='.urlencode($this->property).'@'.$this->sort;
        } 
		return "";
    }
}

function XN_Filter($prop, $operator = null, $value = null, $type = null) 
{
    XN_Filter::_verify($prop, $operator, $value, $type);
    $payload = array('prop' => $prop, 'operator' => $operator,'value' => $value, 'type' => $type);
    return new XN_Query_InternalType_RawFilter($payload);
}

class XN_Filter {
    const LITERAL = 'literal';
    const QUOTED = 'quoted';
    const FORCE_GLOBAL_ENDPOINT = '_global_endpoint';
     public static function any() 
	 {
         $args = func_get_args();
		 if(func_num_args() == 1)
		 {
		 	$args = func_get_arg(0);
			if(!is_array($args))
			{
				$args = func_get_args();
			}
		 }
         return self::_clause('|', $args);
     }

     public static function all() 
	 {
         $args = func_get_args();
		 if(func_num_args() == 1)
		 {
		 	$args = func_get_arg(0);
			if(!is_array($args))
			{
				$args = func_get_args();
			}
		 }
         return self::_clause('&', $args);
     }

     protected static function _clause($op, $args) 
	 {
         foreach ($args as $i => $arg) 
		 {
             if (! (($arg instanceof XN_Query_InternalType_RawFilter)||($arg instanceof XN_Query_InternalType_FilterClause))) 
			 {
                 throw new XN_IllegalArgumentException("Argument #$i is neither the result of calling XN_Filter(), XN_Filter::any(), nor XN_Filter::all()");
             }
         }
         $payload = array($op, $args);
         return new XN_Query_InternalType_FilterClause($payload);
     }

    public static function _verify($prop, $operator, $value, $type) 
	{
        if (! is_null($value)) 
		{
            if (strcasecmp($operator,XN_Query::IN)==0) 
			{
                if (!is_array($value)) 
				{
                    $lowerProp = strtolower($prop);
                    $isContributor = (($lowerProp == 'contributor')||($lowerProp == 'contributorname')||($lowerProp=='contributor.screenname'));
                    if ($isContributor) 
					{
                        if  (! ($value instanceof XN_Query_InternalType_Friends)) 
						{
                            throw new XN_IllegalArgumentException("The value argument for an 'in' filter must be an array or XN_Query::FRIENDS() result");
                        }
                    } 
					else 
					{
                        throw new XN_IllegalArgumentException("The value argument for an 'in' filter must be an array");
                    }
                }
            } 
			elseif (strcasecmp($operator,XN_Query::NIN)==0) 
			{
                if (!is_array($value)) 
				{
                    throw new XN_IllegalArgumentException("The value argument for an '!in' filter must be an array");
                }
            } 
			elseif (is_array($value)) 
			{
                throw new XN_IllegalArgumentException("The value argument for an $operator filter can not be an array.");
            }
        }
    }
    public function _getFilterApps() 
	{
        if ($this->property != 'owner.relativeUrl') 
		{
            return null;
        }
        if (($this->operator != '=') && (strcasecmp($this->operator,'eic') != 0) && (strcasecmp($this->operator,'in') != 0)) 
		{
            return XN_Filter::FORCE_GLOBAL_ENDPOINT;
        }
        if (is_array($this->value)) 
		{
            $tmp = array();
            foreach ($this->value as $host) {
                $strippedHost = substr($host,1);
                $strippedHost = substr($strippedHost,0,-1);
                $tmp[] = $strippedHost;
            }
            return $tmp;
        } 
		else {
            $strippedHost = substr($this->value,1);
            $strippedHost = substr($strippedHost,0,-1);
            return $strippedHost;
        }
    }

    public static function ne($property, $value, $type=null){
        return new XN_Filter($property, '<>', $value, $type);
    }
    public static function eq($property, $value=null, $type=null){
        return new XN_Filter($property, '=', $value, $type);
    }
    public static function lt($property, $value, $type=null){
        return new XN_Filter($property, '<', $value, $type);
    }
    public static function gt($property, $value, $type=null){
        return new XN_Filter($property, '>', $value, $type);
    }
    public static function le($property, $value, $type=null){
        return new XN_Filter($property, '<=', $value, $type);
    }
    public static function ge($property, $value, $type=null){
        return new XN_Filter($property, '>=', $value, $type);
    }
    public static function eic($property, $value, $type=null){
        return new XN_Filter($property, 'eic', $value, $type);
    }
    public static function neic($property, $value, $type=null){
        return new XN_Filter($property, 'neic', $value, $type);
    }
    public static function like($property, $value, $type=null){
        return new XN_Filter($property, 'like', $value, $type);
    }
    public static function in($property, $array, $type=null){
        return new XN_Filter($property, 'in', $array, $type);
    }
    public static function nin($property, $array, $type=null){
        return new XN_Filter($property, '!in', $array, $type);
    }
    public function debugString() 
	{
        if (is_array($this->value)) 
		{
            $val = implode(',',$this->value);
            $type = self::determineType($this->value[0]);
        } 
		else 
		{
            $val = $this->value;
            $type = self::determineType($this->value);
        }
        return $this->property." : ".$this->operator." : " . $val . ' : ' . $type;
    }

    private static function determineType($value) 
	{
        if ((substr($value,0,1) == "'") && (substr($value,-1) == "'")) 
		{
            $type = XN_Attribute::STRING;
        } 
		else if (preg_match('@^-?\d*(\.\d+)?$@', $value)) 
		{
            $type = XN_Attribute::NUMBER;
        } 
		else if (preg_match('@^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d(.\d+)?(Z|((\+|-)\d\d:\d\d))$@', $value)) 
		{
            $type = XN_Attribute::DATE;
        } 
		else 
		{
            $type = XN_Attribute::STRING;
        }
        return $type;
    }

    private $property = "";
    private $operator = "==";
    private $value;
    private $paramType;

    private static $operatorMap = array('<>' => '!=');
    private static $propertyMap = array('owner.relativeUrl' => 'application', 'contact' => 'id');
    private static $idFilters = array('id' => true, 'content.id' => true, 'contentId' => true, 'referencerId' => true);

    function __construct($name, $operator, $value=null,$type=null) 
	{
        $this->property = $name;
        $this->operator = $operator;

        if ($type == XN_Attribute::DATE && (! strlen($value))) 
		{
            $value = null;
        }

        if (is_array($value)) 
		{
            $this->value = array();
            foreach ($value as $checkVal) 
			{
                if (is_scalar($checkVal)) 
				{
                    $scalarVal = $checkVal;
                } 
				elseif (is_object($checkVal) && is_callable(array($checkVal,'_getId'))) 
				{
                    $scalarVal = $checkVal->_getId();
                    if (! is_scalar($scalarVal)) 
					{
                        throw new XN_IllegalArgumentException("Very invalid value for 'in' filter");
                    }
                } 
				else 
				{
                    throw new XN_IllegalArgumentException("Invalid value for 'in' filter");
                }
                if ($type == self::QUOTED) 
				{
                    $this->value[] = "'".XN_REST::singleQuote($scalarVal)."'";
                } 
				elseif (is_int($scalarVal) || is_float($scalarVal)) 
				{
                    $this->value[] = $scalarVal;
                } 
				elseif (isset(self::$idFilters[$name]) || ($type == self::LITERAL)) 
				{
                    $this->value[] = $scalarVal;
                } 
				else 
				{
                    $this->value[] = "'".XN_REST::singleQuote($scalarVal)."'";
                }
            }
        } 
		elseif (! is_null($value)) 
		{
            if (! is_null($type)) 
			{
                if ($type == XN_Attribute::STRING || $type == self::QUOTED) 
				{
                    $this->value = "'".XN_REST::singleQuote($value)."'";
                } 
				else if ($type == XN_Attribute::BOOLEAN) 
				{
                    $this->value = ((boolean) $value) ? 'true' : 'false';
                } 
				else 
				{
                    $this->value = $value;
                }
            } 
			else 
			{
                if (is_int($value) || is_float($value)) 
				{
                    $this->value = $value;
                } elseif (isset(self::$idFilters[$name])) 
				{
                    $this->value = $value;
                } 
				else 
				{
                    $this->value = "'".XN_REST::singleQuote($value)."'";
                }
            }
        } 
		else 
		{
            if (isset(self::$idFilters[$name])) 
			{
                throw new XN_IllegalArgumentException("Value for filter '$name' cannot be null.");
            } 
			else 
			{
                $this->value = 'null';
            }
        }
    }
    function getProperty(){return $this->property;}
    function getOperator(){return $this->operator;}
    function getValue(){return $this->value;}
    function getType(){return $this->paramType;}
    function _toSelectorValue($maps = null) 
	{
        $operator = isset(self::$operatorMap[$this->operator]) ? self::$operatorMap[$this->operator] : $this->operator;
        $property = isset(self::$propertyMap[$this->property]) ? self::$propertyMap[$this->property] : $this->property;
        
        if (is_array($maps) && isset($maps['property']) && isset($maps['property'][$property])) 
		{
            $property = $maps['property'][$property];
        }
        $value = is_array($this->value) ? ('[' . implode(',',$this->value) . ']') : $this->value;
        return "$property $operator $value";
    }
   
    public function _toLuceneStyleValue() 
	{
        $field = ($this->property == 'fulltext') ? '' : (self::escapeForLuceneStyle($this->property).':');
        $quot = ($this->operator == '=') ? '"' : '';
        if (is_array($this->value)) 
		{
            throw new XN_IllegalStateException("Arrays not allowed with lucene-style values");
        }        
        $value = self::escapeForLuceneStyle($this->value);		
        if (($this->operator == 'like') && ($this->property != 'fulltext')) 
		{
            $value = "($value)";
        }
        return "$field$quot$value$quot";
    }
    protected static function escapeForLuceneStyle($s) {
        return preg_replace('/[\+\-&\|!\(\)\{\}\[\]\^"~\*\?:\\\\]/u','\\\\$0', $s);
    }
}

class XN_Query_InternalType
{
    protected $_payload;

    public function __construct($payload) { $this->_payload = $payload; }

    public function __get($prop) 
	{
        if ($prop == 'payload') 
		{
            return $this->_payload;
        } 
		else 
		{
             throw new Exception('Unknown property: ' . $prop);
        }
    }
    public function __toString() { return $this->_payload; }
    public function _getId() { return $this->_payload; }
}

class XN_Query_InternalType_Friends extends XN_Query_InternalType 
{
    public function _getId() {
        $s = str_replace('\\','\\\\', $this->payload);
        $s = str_replace("'", "\\'", $this->payload);
        return "friends('".$s."')";
    }
}

class XN_Query_InternalType_RawFilter extends XN_Query_InternalType 
{

    public function __toString() { return implode(', ', $this->_payload); }

    public function _toSubjectSpecificFilter($subjectOrStrategyImplementor) 
	{
        if ($subjectOrStrategyImplementor instanceof XN_Query_Subject) 
		{
            return $subjectOrStrategyImplementor->processFilter($this->payload['prop'],
                                                                $this->payload['operator'],
                                                                $this->payload['value'],
                                                                $this->payload['type']);
            

        } 
		else 
		{
            $filterInfo = null;
            if (strcasecmp($subjectOrStrategyImplementor, 'Content') == 0 || 
				strcasecmp($subjectOrStrategyImplementor, 'MainContent') == 0 ||
				strcasecmp($subjectOrStrategyImplementor, 'YearContent') == 0 ||
				strcasecmp($subjectOrStrategyImplementor, 'YearmonthContent') == 0 ||
				strcasecmp($subjectOrStrategyImplementor, 'Content_Count') == 0 ||
                strcasecmp($subjectOrStrategyImplementor, 'Message') == 0) 
			{
                $filterInfo = XN_Query::_prepareContentFilter($this->payload['prop'],
                                                              $this->payload['operator'],
                                                              $this->payload['value'],
                                                              $this->payload['type']);
            } 
			else if (strcasecmp($subjectOrStrategyImplementor, 'Profie')||strcasecmp($subjectOrStrategyImplementor, 'Profile_Count') == 0 ) 
			{
                $filterInfo = XN_Query::_prepareProfileFilter($this->payload['prop'],
                                                              $this->payload['operator'],
                                                              $this->payload['value'],
                                                              $this->payload['type']);
            } 
			else 
			{
                throw new XN_IllegalArgumentException("Sub-clauses only allowed on content terms in Content or Search queries");
            }
            if (is_array($filterInfo)) 
			{
                if ($filterInfo['filterType'] != 'content' && $filterInfo['filterType'] != 'search') {
                    throw new XN_IllegalArgumentException("Sub-clauses only allowed on content terms in Content or Search queries");
                }
                if (strcasecmp($filterInfo['prop'], 'referencer') == 0) 
				{
                    throw new XN_IllegalArgumentException("referencerId not allowed in sub-clauses");
                }
                $filter = new XN_Filter($filterInfo['prop'], $filterInfo['operator'],$filterInfo['value'], $filterInfo['type']);
                return $filter;
            }  
			else 
			{
                return null;
            }
        }
    }
}

class XN_Query_InternalType_FilterClause extends XN_Query_InternalType 
{
    public function __toString() 
	{
        if (is_array($this->_payload) && isset($this->_payload[0])) 
		{
            $tmp = array();
            if (isset($this->_payload[1]) && is_array($this->_payload[1])) 			
			{
                foreach ($this->_payload[1] as $filter) 
				{
                    $tmp[] = $filter->_toSelectorValue();
                }
            }
            return '(' . implode($this->_payload[0], $tmp) . ')';
        } 
		else 
		{
            return '';
        }
    }
   
    public function _toFilterTree($subjectOrStrategyImplementor) 
	{
        $tree = array();
        if (is_array($this->_payload) && isset($this->_payload[0])) 
		{
            $tree[] = $this->payload[0];
            if (isset($this->_payload[1]) && is_array($this->payload[1])) 
			{
                foreach ($this->_payload[1] as $rawFilter) 
				{
                    if ($rawFilter instanceof XN_Query_InternalType_RawFilter) 
					{
                        $tree[] = $rawFilter->_toSubjectSpecificFilter($subjectOrStrategyImplementor);
                    } 
					else if ($rawFilter instanceof XN_Query_InternalType_FilterClause) 
					{
                        $tree[] = $rawFilter->_toFilterTree($subjectOrStrategyImplementor);
                    } 
					else 
					{
                        throw new XN_IllegalArgumentException("$rawFilter is neither a raw filter nor a filter clause");
                    }
                }
            }
        }
        return $tree;
    }
}

} 
