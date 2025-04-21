<?php


/*

This is a modified version of OIT's Shibboleth class found here:
    https://github.oit.uci.edu/iam-community/shib-php

It has been adapted to extend Yii's Component class
and to fix several problems in the original:
    
    - Login url and method were missing  (Typical usage would do automatic login when
     trying to access a secure page?)

    - No attributes were being set correctly. (May depend on how the server asks for them.)
        - including "common variables returned in apache shibboleth configurations" 
            e.g. $this->{'Shib-Handler'}, $this->{'Shib-Application-ID'}, $this->{'Shib-Session-ID'}
            I am not familiar with this syntax, but it wasn't working as is. I have replaced them
            with names like  $this->shib_handler  (lowercase, substitute underscores for hyphens)
            These are used in the isLoggedIn() method, so that has been changed to use the
            updated var names

*/


/*

shibboleth.php
--------------------------------------------------------------------------------

UCI SHIBBOLETH CLASS

Provides Access to UC Irvine's Shibboleth Attributes for PHP 5+.

--------------------------------------------------------------------------------

CONTRIBUTORS
2021
University of California, Irvine
Office of Research Administration

Christopher Price

--------------------------------------------------------------------------------

*/



namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

use yii\helpers\Url;




class shibboleth extends Component
{



    //public $login_url = 'https://coby-dev.ics.uci.edu/Shibboleth.sso/Login';

    // try using this instead, from the SERVER variables 
    //public $login_url = $_SERVER['Shib-Handler'] . '/Login';
    // "unexpected $_SERVER" error?  try declaring here and moving the actual assignment to __construct area

    public $login_url;


    
    // The URLs to the web authentication service
    public $shibboleth_idp_url = '';


    //public $shibboleth_local_url     = '/Shibboleth.sso/logoutuci?return=';
    public $shibboleth_local_url     = '/Shibboleth.sso/Logout?return=';

    public $shibboleth_idp_url_old   = 'https://shib.nacs.uci.edu/idp/profile/Logout?return_url=';
    public $shibboleth_idp_url_new   = 'https://shib.service.uci.edu/idp/profile/Logout?return_url=';

    // The user's remote address
    public $remote_addr = '';

    // Backwards compatibility variable (no-op)
    public $age_in_seconds = 0;

    // See constructor for common variables returned in apache shibboleth configurations

    // These are common variables returned in shibboleth configurations at UCI
    public $ucinetid         = '';
    public $campus_id        = '';
    public $uci_affiliations = ''; // semi-colon delimited originally; replaced with commas for backwards compatibility
    public $fullname         = '';



    public $logout_url;



    public $shib_handler, $shib_application_id, $shib_session_id, $shib_identity_provider, $shib_authentication_instant, $shib_authentication_method, $shib_authn_context_class, $shib_session_index, $shib_session_expires, $shib_session_inactivity;



    // Constructor for authentication object
    public function __construct()
    {
        // Get client's IP address
        $this->remote_addr = $this->getClientAddress();

        // Construct the return URL for the client
        $this->shibboleth_idp_url = $this->shibboleth_idp_url_new;

        $this->setUrl();



        $this->login_url = $_SERVER['Shib-Handler'] . '/Login';



// what do these braces do?

    //$property_name = 'foo';
    //$object->{$property_name} = 'bar';
        // same as $object->foo = 'bar';


        // These are common variables returned in apache shibboleth configurations
        //$this->{'Shib-Handler'}                = $this->getReqVar('Shib-Handler');
        //$this->{'Shib-Application-ID'}         = $this->getReqVar('Shib-Application-ID');
        //$this->{'Shib-Session-ID'}             = $this->getReqVar('Shib-Session-ID');
        //$this->{'Shib-Identity-Provider'}      = $this->getReqVar('Shib-Identity-Provider');
        //$this->{'Shib-Authentication-Instant'} = $this->getReqVar('Shib-Authentication-Instant');
        //$this->{'Shib-Authentication-Method'}  = $this->getReqVar('Shib-Authentication-Method');
        //$this->{'Shib-AuthnContext-Class'}     = $this->getReqVar('Shib-AuthnContext-Class');
        //$this->{'Shib-Session-Index'}          = $this->getReqVar('Shib-Session-Index');
        //$this->{'Shib-Session-Expires'}        = $this->getReqVar('Shib-Session-Expires');
        //$this->{'Shib-Session-Inactivity'}     = $this->getReqVar('Shib-Session-Inactivity');



        $this->shib_handler                = $this->getReqVar('Shib-Handler');
        $this->shib_application_id         = $this->getReqVar('Shib-Application-ID');
        $this->shib_session_id             = $this->getReqVar('Shib-Session-ID');
        $this->shib_identity_provider      = $this->getReqVar('Shib-Identity-Provider');
        $this->shib_authentication_instant = $this->getReqVar('Shib-Authentication-Instant');
        $this->shib_authentication_method  = $this->getReqVar('Shib-Authentication-Method');
        $this->shib_authn_context_class    = $this->getReqVar('Shib-AuthnContext-Class');
        $this->shib_session_index          = $this->getReqVar('Shib-Session-Index');
        $this->shib_session_expires        = $this->getReqVar('Shib-Session-Expires');
        $this->shib_session_inactivity     = $this->getReqVar('Shib-Session-Inactivity');




        //$this->ucinetid                = $this->getReqVar('ucinetid');
        $this->ucinetid                = $this->getReqVar('SHIB_UCInetID');

        $this->campus_id               = $this->getReqVar('campusid');
        $this->uci_affiliations        = str_replace(';', ',', $this->getReqVar('uciaffiliation'));
        $this->fullname                = $this->getReqVar('cn');

        // campusid may be mapped to ucicampusid
        if ( strlen($this->campus_id) === 0 )
        {
            $this->campus_id = $this->getReqVar('ucicampusid');
        }

        return;
    }

    // Returns the value of a var in $_SERVER
    public function getReqVar( $var_name )
    {
        $var_value = '';

        if ( isset( $_SERVER[ $var_name ] ) )
        {
            $var_value = strval($_SERVER[ $var_name ]);
        }
        else if ( isset( $_SERVER[ 'AJP_' . $var_name ] ) )
        {
            $var_value = strval($_SERVER[ 'AJP_' . $var_name ]);
        }

        return $var_value;
    }

    // Returns the value of a var in our class instance
    public function getVar( $var_name = '' )
    {
        return $this->$var_name;
    }

    // Returns class instance
    public function getAll()
    {
        return $this;
    }

    // Allows changing the URL passed to logout redirect
    public function setUrl( $_url = '' )
    {
        if ( $_url === '' )
        {
            // Check the server port to determine url type
            $prefix = 'http://';

            // Note: According to http://php.net/manual/en/reserved.variables.server.php, SERVER_PORT is not reliable
            //
            //if ( $_SERVER['SERVER_PORT'] === '443' )

            $is_https_set       = !empty( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] !== 'off' );
            $is_https_forwarded = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';

            if ( $is_https_set || $is_https_forwarded )
            {
                $prefix = 'https://';
            }

            // Construct the executing script path from $_SERVER vars
            //
            // Note: According to http://php.net/manual/en/reserved.variables.server.php, HTTP_HOST is set by a header. This means it might not be there or could be spoofed.
            //
            //$_url = $prefix . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

            $_url = $prefix . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

            // Add _GET vars to the url request
            if ( count( $_GET ) > 0 )
            {
                $i = 0;

                foreach ( $_GET as $k => $v )
                {
                    $url_prefix = '&';

                    if ( $i === 0 )
                    {
                        $url_prefix = '?';
                    }

                    $_url .= $url_prefix . rawurlencode( $k ) . '=' . rawurlencode( $v );

                    $i++;
                }
            }
        }

        $this->logout_url = $this->shibboleth_local_url . rawurlencode( $this->shibboleth_idp_url . rawurlencode( $_url ) );

        return;
    }

    // Guess the best client address
    public function getClientAddress()
    {
        // Assume the client ip is the remote address
        $remote_address = $_SERVER['REMOTE_ADDR'];

        // Attempt to get the client's IP address if the client is instead requesting through a proxy chain, if the web server supplies the information

        // Note: The IP(s) in the $_SERVER['HTTP-X-FORWARDED-FOR'] var cannot be trusted, because they can be easily spoofed. See https://en.wikipedia.org/wiki/User:Brion_VIBBER/Cool_Cat_incident_report

        // Get true http request headers if available (e.g. Apache)
        if ( function_exists('getallheaders') )
        {
            $server_headers = getallheaders();
        }
        else
        {
            // Fall back to an insecure lookup
            $server_headers = $_SERVER;
        }

        $server_header_forwarded_for = '';

        if ( isset( $server_headers['X-Forwarded-For'] ) )
        {
            $server_header_forwarded_for = $server_headers['X-Forwarded-For'];
        }

        if ( strlen( trim( $server_header_forwarded_for ) ) > 0 )
        {
            $forwarded_ip_addresses = explode( ',', $server_header_forwarded_for );

            // Trust the last IP address based on advisory from wikipedia team in the link provided in the note above
            $last_forwarded_ip = trim( end( $forwarded_ip_addresses ) );

            $remote_address = $last_forwarded_ip;
        }

        return $remote_address;
    }



    // Assume file accessed in shibboleth protected space, return true if shibboleth activity < expiration epoch, and we haven't been redirected by the shibboleth service
    public function isLoggedIn( $check_type = 'all' )
    {

        //$status = (isset($this->{'Shib-Session-Inactivity'}) && isset($this->{'Shib-Session-Expires'}) && is_numeric($this->{'Shib-Session-Inactivity'}) && is_numeric($this->{'Shib-Session-Expires'}) && (intval($this->{'Shib-Session-Inactivity'}) < intval($this->{'Shib-Session-Expires'})));

        $status = (isset($this->shib_session_inactivity) && 
                   isset($this->shib_session_expires) && 
                   is_numeric($this->shib_session_inactivity) && 
                   is_numeric($this->shib_session_expires) && 
                   (intval($this->shib_session_inactivity) < intval($this->shib_session_expires)));

        return $status;
    }



    // Redirect user to shibboleth login
    public function login()
    {

        header( 'Location: ' . $this->login_url . '?target=' . Url::current([], 'https') );
                                                                // first arg is any additional values you want to pass, second is the desired scheme
                                                                //  without the empty array it will discard any params. which may not be a big deal for this usage.
                                                                // Url::current([], true)  is supposed to use the same scheme currently
                                                                //  being used but is always coming back as "http" -- why?

        exit();

        return;
    }



    // Redirect user to shibboleth logout
    public function logout()
    {
        header( 'Location: ' . $this->logout_url );

        exit();

        return;
    }

}