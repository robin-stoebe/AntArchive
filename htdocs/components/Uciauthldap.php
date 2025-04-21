<?php
// $Id: ldap.inc.php 853 2008-09-24 20:41:03Z jromine $


  namespace app\components;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;
   
   use app\components\Uciauthladp;
   


/*
 *
 *  We are using this class for search uci directory when adding a new user
 *  to the system.  This was originally from GATS.
 * 
 */

class Uciauthldap /* implements Iterator */ {
    // list of optional configuration settings
    var $config = array('server', 'port', 'ou', 'fields', 'conj','mode');

    var $fields = array();		// fieldset to return (default=ALL)
    var $server = "ldaps://ldap-auth.oit.uci.edu";
    var $port   ='636';
    var $basedn = 'dc=uci,dc=edu';
    var $ou     = "ou=people,dc=uci,dc=edu";
    var $mode   = 'ucpath';
    var $conj	= '&';			// conjuction for query ('&' or '|')
    var $ds;				// LDAP link identifier
    var $sr;				// LDAP search result
    var $n;				// result row counter
    
    var $errno;
    var $error;
    var $userid = '';
    var $passwd = '';


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

    // $ld->query(array('ucinetid'=>'ccc'))
    //
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
        require_once(__DIR__."/../config/vault.php");
        $this->userid=getpw('sysops/data/webapps/ucildap','ucildap_user');
        $this->passwd=getpw('sysops/data/webapps/ucildap','ucildap_pass');



        if(!empty($this->userid)){
            if (!@ldap_bind($this->ds,$this->userid,$this->passwd)){    // this is NOT an "anonymous" bind
                Debug::debug('could not bind:'. $this->userid);
                print_r(ldap_error($this->ds));
//                ldap_get_option($this->ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $err);
                return false;
            }
            
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
            // Debug::debug($filter);
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

    // This is simple, so we'll just include an
    // Iterator right in the class, rather than
    // creating a separate LDAPIterator class

    // This allows three styles of iteration:
    // 
    // 1. PHP5 emulation (preferred)
    // for ($i->rewind(); $i->valid(); $i->next()) {
    //     $key = $i->key();
    //     $value = $i->current();
    //     // do something
    // }
    // 
    // 2. PHP4 'each' style
    // $i->reset();
    // while (list($key, $value) = $i->each()) {
    //     // do something
    // }
    //
    // 3. PHP4 alternate (not boolean-safe) syntax:
    // $ld->reset();
    // while ($value = $ld->fetch()) { ... }


    /* PHP5 Iterator-compatible functions */

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

    function findUCINetID($s,$match=false) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q[uid] = "$s";

            if(!$this->query($q, array('conj'=>'|')))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();

                 if($match==true and $v['uid'][0]==$s)
                    return $v;

                 $people[]=$v;
                 $numRec++;
             }

        return $people;
    }





    function findInstructors($s){
        $people = [];
        $this->query($s, array('conj'=>'|'));
        for ($this->rewind(); $this->valid(); $this->next()) {
            $v = $this->current();
                    # debug_pre($v, '$v returned from uci');
    
                    if($v['objectclass'][0]=='uciforward')
                    continue;
    
                    Debug::debug(array($s,$v),'return from validateUCInetID');
                    unset($rec);
    
                    $rec = [
                        'Name' => $v['sn'][0].", ".$v['givenname'][0],
                        'Display Name' => $v['displayname'][0],
                        'UCINet ID' => $v['uid'][0],
                        'E-mail' => $v['mail'][0],
                        'Phone' => $v['telephonenumber'][0],
                        //'all' => $v,
                    ];                    
    
                    $people[] = $rec;
    
        }
    
        return $people;
     }
    



    function findStudentID($s) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q['ucistudentid'] = "$s";

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
    

    function find($q,$args) {
        global $debug;

        
        $field='ucinetid';
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            Debug::debug(['query'=>$q,'args'=>$args],'about to ldap search');

            if(!$this->query($q,$args))
                    return false;

//		debug_pre($this,'ldap rec after query');

             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();
		 $people[]=$v;

                 $numRec++;
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
                # debug_pre($v, '$v returned from uci');
    
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

   function findbyalias($s) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q['mail'] = $s;

            if(!$this->query($q))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();
                 return $v['uid'][0].'@uci.edu';

               
             }

        return false;
    }    
    function findbyemail($s) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q['mail'] = $s;

            if(!$this->query($q))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();
                 return $v;

               
             }

        return false;
    }    

   function findUCIMAilByUID($s) {
        global $debug;

        
        
            $numRec = 0;
//                $umodel=new User;
            $people=array();

            $q=array();
            $q['uid'] = $s;

            if(!$this->query($q))
                    return false;


             $n = 1;
             for ($this->rewind(); $this->valid(); $this->next()) {
                 $v = $this->current();
                 return $v['mail'][0];

               
             }

        return false;
    }    
    
}
