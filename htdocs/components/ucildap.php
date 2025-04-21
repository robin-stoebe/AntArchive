<?php


   namespace app\components;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;
   use app\models\Log;
   use app\components\Debug;



   class ucildap extends Component
   {

      var $config = array('server', 'port', 'ou', 'fields', 'conj');
      var  $server = "ldaps://ldap.oit.uci.edu";
      var  $port = "636";
      var  $basedn = "dc=uci,dc=edu";
      var  $ou ="ou=people,dc=uci,dc=edu";
      var $fields = array();		// fieldset to return (default=ALL)
      var $conj ='&';
      var $ds;				// LDAP link identifier
      var $sr;				// LDAP search result
      var $n;				// result row counter
      var $info;
      
      var $errno;
      var $error;
      
      
     function __construct($server=null,$ou=null) {
        global $debug;
	if (is_array($server)) {
	    $this->setargs($server);
	}
	else {
	    if (strlen($server))
		$this->server = $server;
	    if (strlen($ou))
		$this->ou = $ou;
	}
    }
      
      /*private*/
    function setargs($args) {
	if (is_array($args))
	    foreach ($this->config as $k)
		if (array_key_exists($k, $args))
		    $this->$k = $args[$k];
    }
      
      public function ldap_connect($username='', $password='')
      {

          
          $log = new \app\models\Log;
          
         if ($this->ds = ldap_connect($this->server, $this->port))
         {

             
               
            ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0);

            //Yii::$app->session->setFlash('success', 'connected to ldap host $ldap_server.');
             if(!empty($username)){
                $name = "uid=$username,ou=People,". self::BASE_DN;
                if(!@ldap_bind($this->ds, $name, $password)){
                     Debug::debug('could not bind:'. $this->userid);
                     $log->entry('ldap bind error',print_r(ldap_error($this->ds),true));
//                ldap_get_option($this->ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
                return false;
                }
             } else {
                if(!@ldap_bind($this->ds)){
                   Debug::debug('could not anonymous bind');
                   $log->entry('ldap bind error',print_r(ldap_error($this->ds),true));
                   return false; 
                }

             }

            //Yii::$app->session->setFlash('success', '\$test = '. $test);

            

         }
         else
         {

            //Yii::$app->session->setFlash('danger', 'could not connect to ldap server');

            return false;
         }


      }

  function query($filter=array(),$args=null) {
        
        global $debug;
        
        
        $log = new \app\models\Log;
	if (empty($filter))		// empty string, or empty array
	    return false;

	if (!is_null($args))		// optional $args
	    $this->setargs($args);

	if ($this->ds)
	    $this->free();

        if(!empty($this->port))
            $this->ds = ldap_connect($this->server, $this->port) or
	    die('ldap_connect failed');
        else
            $this->ds = ldap_connect($this->server) or
	    die('ldap_connect failed');
        
        
        ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0);
        
        Debug::debug('connected');
        
        $this->ldap_connect();
        
        
        Debug::debug($filter, 'search text');
        
	if (is_array($filter)) {	// conjunct an array of filter args
	    $a = array();
	    foreach ($filter as $k=>$v) {
                if(!is_array($v)){
                    $a[] = "($k=$v)";
                } else {
                      $filterString='';
                      foreach($v as $val){
                          $filterString.= "($k=$val)";
                      }
                      $a[] = "(|$filterString)";
                }
#		$a[] = "($k=".$this->quote($v).")";
	    }
	    $filter = '(' . $this->conj . join('', $a) . ')'; // AND or OR 
            Debug::debug($filter);
	}

	// ignore possible 'size limit exceeded' error
        if(!empty($this->fields))
            $this->sr=ldap_search($this->ds, $this->basedn, $filter, $this->fields); 
        else {
            $this->sr=ldap_search($this->ds, $this->basedn, $filter);     
        }
	$this->errno = ldap_errno($this->ds);
	$this->error = ldap_error($this->ds);
        if($this->errno!=0){
            $log->entry('ldap search error',print_r([$this->errno,$this->error],true));
        }
//	if ($this->sr)
//	    ldap_sort($this->ds, $this->sr, 'lastfirstname');
	$this->reset();

	return $this->sr;
    }

    // ldap_quote - quote special chars in ldap query string
    // need to quote: backslash, left-paren, right-paren, asterisk, slash, null
    // http://www.ietf.org/rfc/rfc2254.txt
    //
    function quote($s)
    {
	static $k = array('@\x5c@', '@\x28@', '@\x29@', '@\x2a@', '@\x2f@', '@\x00@',);
	static $v = array('\\\\5c', '\\\\28', '\\\\29', '\\\\2a', '\\\\2f', '\\\\00',);

	return preg_replace($k, $v, $s);
    }

    function num_rows() {
	return ($this->sr) ? ldap_count_entries($this->ds, $this->sr) : null;
    }
    function free() {
	ldap_close($this->ds);
	$this->ds = null;
	$this->info = null;
    }
    
      /* public */
    function key() {			// get current key
	return $this->n;
    }
    function current() {		// get current value
	return is_array($this->info) ? $this->info[$this->n] : null;
    }
    function valid() {			// true while current is valid
	return ($this->n < $this->info['count']);
    }
    function rewind() {			// reset pointer, get first element
	$this->reset();
    }
    function next() {			// advance pointer, get next element
	$this->n++;
    }

    /* PHP4 iterator functions: */

    function reset() {			// rewind to before first element
	$this->info = ($this->sr) ? ldap_get_entries($this->ds, $this->sr) : null;
	$this->n = 0;			// first row is 0
    }
    function each() {			// return next element as an array
	$result = $this->valid() ? array($this->key(),$this->current()) : false;
	$this->next();
	return $result;
    }
    /* deprecated */
    function fetch() {	// NOTE: this won't work correctly if element === false
	$result = $this->valid() ? $this->current() : false;
	$this->next();
	return $result;
    }

    function findUCINetID($s) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q[uid] = "$s*";

            if(!$this->query($q, array('conj'=>'|')))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();


                 $people[]=$v;
                 $numRec++;
             }

        return $people;
    }


    function find($s,$toSearch=array(),$type='', $dups='no') {
        global $debug;

        
        $field='ucinetid';
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            
            if(empty($toSearch)){
                $q[sn] = "$s*";
                $q[uid] = "$s*";
            } else {
                foreach($toSearch as $f){
                    $q[$f]="$s*";
                }
            }

            if(!$this->query($q, array('conj'=>'|')))
                    return false;

//		Debug::debug($this,'ldap rec after query');

             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();
//		     Debug::debug($v, '$v returned from uci');

                 if($v[objectclass][0]=='uciforward')
		     	continue;

//                     print_r($v);exit;
		     unset($rec);

		     $rec = array("Name"=> $v[sn][0].", ".$v[givenname][0],"UCINet ID"=>$v[uid][0]);

		     if(strtolower($v[uciaffiliation][$v[uciaffiliation][count]-1])=='student')
		     	$rec["uciaffiliation"] = "Student: " . $v[major][0] . " - " . $v[studentlevel][0];
		     else
		     	$rec["uciaffiliation"] = $v[uciaffiliation][$v[uciaffiliation][count]-1].": " . $v[department][0];


                     if($type=='add'){
                           $umodel=new User;
                         if( !($udata = $umodel->findbyCampusID($v[ucicampusid][0])) or ($field!='ucinetid') )
                                    $rec["Select"]= "<input type='radio' name='$field' value='".$v[uid][0]."'>";
                         else
                            $rec["Select"]= "Already a user in application";
                     }
		     $people[]=$rec;

//                 $people[]=$v;
                 $numRec++;
//                     if($numRec>256)
//                         break;
             }

        return $people;
    }


 function validateUCInetID($s) {
    
    
    	$umodel=new User;
        $people=array();
    
    	$q[uid] = "$s";
    	$this->query($q, array('conj'=>'|'));
    
    	//print_r(array($this,'ldap rec after query'));
    
    	$n = 1;
    	for ($this->rewind(); $this->valid(); $this->next()) {
    	$v = $this->current();
    		# Debug::debug($v, '$v returned from uci');
    
    		if($v[objectclass][0]=='uciforward')
    		continue;
    
    	    if(strtoupper($v[uid][0])!=strtoupper($s))
    	    	continue;
    	    
    		//print_r(array($s,$v));
    		unset($rec);
    
    		$rec = array("Name"=> $v[sn][0].", ".$v[givenname][0],"UCINet ID"=>$v[uid][0]);
    
    		if(strtolower($v[uciaffiliation][$v[uciaffiliation][count]-1])=='student')
    			$rec["uciaffiliation"] = "Student: " . $v[major][0] . " - " . $v[studentlevel][0];
   			else
    			$rec["uciaffiliation"] = $v[uciaffiliation][$v[uciaffiliation][count]-1].": " . $v[department][0];
   			
   			$people[]=$rec;
    
    	}
    
    	return $people;
    }
    
   }



?>