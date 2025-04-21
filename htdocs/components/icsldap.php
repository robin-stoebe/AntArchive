<?php


   namespace app\components;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;



   class icsldap {
    var $config = array('server', 'port', 'ou', 'fields', 'conj','userid','passwd');

    var $fields = array();		// fieldset to return (default=ALL)
    var $server = "ldaps://ldap0.ics.uci.edu";
    var $port   ='636';
    var $basedn = 'dc=ics,dc=uci,dc=edu';
    var $ou     = "ou=people,dc=ics,dc=uci,dc=edu";

    var $conj	= '&';			// conjuction for query ('&' or '|')
    var $ds;				// LDAP link identifier
    var $sr;				// LDAP search result
    var $n;				// result row counter

    var $errno;
    var $error;

    var $userid;
    var $passwd;
    
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
      
   function query($filter=array(),$args=null) {
        
        global $debug;
        
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
        
        // Debug::debug('connected');
        
        if(!empty($this->userid)){
            if (!@ldap_bind($this->ds,$this->userid,$this->passwd)){    // this is NOT an "anonymous" bind
                Debug::debug('could not bind:'. $this->userid);
                print_r(ldap_error($this->ds));
//                ldap_get_option($this->ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
                return false;
            }
            Debug::debug('Bind as :'. $this->userid);
        } else {
            if (!@ldap_bind($this->ds)){    // this is an "anonymous" bind
                Debug::debug('could not anonymous bind');
                return false;
            }
        }
        // Debug::debug($filter, 'search text');
        
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
//	if ($this->sr)
//	    ldap_sort($this->ds, $this->sr, 'lastfirstname');
	$this->reset();

	return $this->sr;
    }
      
      public function findByUCINetID($id){
          $now = date('Ymd000000Z');
        //   $search = "(&(ucnetid=$id)(unixaccountexpiredate>$now))";
          $search = "(ucnetid=$id)";
               global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            if(!$this->query($search, array('conj'=>'&','basedn'=>'ou=People,dc=ics,dc=uci,dc=edu')))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 
                 $v = $this->current();
                 Debug::debug($v,'current icsldap record',true);
                 
                //  if(strtolower($v['ucnetid'][0]=strtolower($id)))
                //     return $v;

                 $people[]=$v;
                 $numRec++;
             }

        return $people;
    
      }
      public function findByUID($id){
          if(empty($id))
            return false;
          $search = "(uid=$id)";
               global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            if(!$this->query($search, array('conj'=>'&')))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v= $this->current();
                //  Debug::debug($v,'rec findByUID');
                 if($v[dn]=="uid=$id,ou=People,dc=ics,dc=uci,dc=edu")
                     return $v;

                 $people[]=$v;
                 $numRec++;
             }

        
        return false;
    
      }
      
      public function icsEmail($id){
          $email = false;
          $recs = $this->findByUCINetID($id);
          if(count($recs)==1){
              $email= $recs[uid][0].'@ics.uci.edu';
              
          }
          
           Debug::debug($email,"icsEmail found for $id");
          return $email;
      }

      public function uciEmail($id){
          if(empty($id))
            return false;
          
          $email = false;
          if($rec = $this->findByUID($id))
              $email =  $rec[ucnetid][0].'@uci.edu';
          Debug::debug($email,"uciEmail found for $id");
          return $email;
      }

   

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
}
?>