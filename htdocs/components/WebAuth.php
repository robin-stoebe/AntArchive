<?php
/*
WebAuth.php
--------------------------------------------------------------------------------
UCI WEBAUTH CLASS
Implements UC Irvine's Web Authentication with Duo Two-Factor support for PHP 5+.
- For more info about WebAuth, see http://www.oit.uci.edu/idm/webauth/
- For OIT documentation of the protocol, see https://wiki.oit.uci.edu/display/public/Protecting+Your+Web+Application+Using+WebAuth+And+Duo+Multi-Factor+Authentication
--------------------------------------------------------------------------------
CONTRIBUTORS
2015 - 2017
University of California, Irvine
Student Life & Leadership
Steve Tajiri, Christopher Price, Josh Lien
Original code
Eric Carter <ecarter@uci.edu>
--------------------------------------------------------------------------------
USAGE EXAMPLE:
Start by creating a new file, such as "auth-test.php", and add the following:
<?php
    // Require the WebAuth class file.
    require_once 'WebAuth.php';
    // Create a new authentication object.
    $auth_object = new WebAuth();
    // Alternatively, you can force Duo Two-Factor authentication.
    $auth_object = new WebAuth(true);
    // Or you can set it manually
    $auth_object->enforce_duo = true;
    // This is an example to allow clients to login or logout.
    // Go to http://mypage.uci.edu/auth-test.php?login=1
    // or to http://mypage.uci.edu/auth-test.php?logout=1
    if ( !empty( $_GET['login'] ) )
    {
        $auth_object->login();
    }

    if ( !empty( $_GET['logout'] ) )
    {
        $auth_object->logout();
    }
    // Next, we can know whether or not the client is logged
    // in by checking the isLoggedIn() method.
    if ( $auth_object->isLoggedIn() )
    {
        // client is logged in
        // the client's ucinetid is accessible in $auth->ucinetid
    }
    else
    {
        // client is not logged in
    }
?>
*/





   namespace app\components;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;





class WebAuth extends Component
{
    // The URLs to the web authentication services (test domain: login2.uci.edu)
    public $login_url       = 'https://login.uci.edu/ucinetid/webauth';
    public $login_url_duo   = 'https://login.uci.edu/duo/webauth';
    public $logout_url      = 'https://login.uci.edu/ucinetid/webauth_logout';
    public $check_url       = 'https://login.uci.edu/ucinetid/webauth_check';


    // The timeouts for all outside requests
    public $timeout_check         = 5;
    public $timeout_duo           = 10;

    public $timeout_check_max     = 3;
    public $timeout_check_count   = 0;
    // This stores whether or not we should force active Duo authentication as part of being logged in
    // check class member login() for more details
    public $enforce_duo = false;
    // The cookie: the name of the cookie is 'ucinetid_auth'
    public $cookie;
    // The client's URL: indicates the authentication redirect url based on client
    public $url;
    // The user's remote address: checked after auth to match auth_host
    public $remote_addr;
    // $errors[1] = php version check
    // $errors[2] = cannot connect to login.uci.edu
    // $errors[3] = auth_host doesn't match
    public $errors = array();
    // These are the defined vars, all string type, from login.uci.edu (updated Dec 15, 2015)
    public $ucinetid                = '';
    public $auth_host               = ''; //ip address
    public $x_forwarded_for         = '';
    public $time_created            = ''; //unix epoch
    public $last_checked            = ''; //unix epoch
    public $max_idle_time           = ''; //number
    public $campus_id               = '';
    public $uci_affiliations        = ''; //comma delimited
    public $auth_methods            = ''; //comma delimited: requires return_auth_contexts=true parameter in check
    public $auth_contexts           = ''; //comma delimited: requires return_auth_contexts=true parameter in check
    public $age_in_seconds          = ''; //number
    public $seconds_since_checked   = ''; //number


    public $auth_fail;
    public $error_code;


    // Constructor for authentication object
    public function __construct( $enforce_duo = false, $remove_login_logout_from_return_url = true )
    {
        // Check PHP version
        if ( phpversion() < 5 )
        {
            $this->errors[1] = 'Warning, this class is designed to work with PHP 5.x';
        }
        // Option to enable additional Duo authentication
        $this->enforce_duo = $enforce_duo;

        // Get client's IP address
        $this->remote_addr = $this->getClientAddress();
        // Duo two-factor authentication parameter request: returns how user was authenticated
        $this->check_url .= '?return_auth_contexts=true';

        // Send 'ucinetid_auth' cookie authentication data if it exists
        if ( isset( $_COOKIE['ucinetid_auth'] ) )
        {
            $this->cookie = $_COOKIE['ucinetid_auth'];
            $this->check_url .= '&ucinetid_auth=' . rawurlencode( $this->cookie );
        }
        // Check authentication status
        // Attempt automatic rechecks when timeout is reached during webauth check
        do
        {
            $this->timeout_check_count++;
            unset( $this->errors[2] );

            $result = $this->checkAuth();
        } while( !$result && isset( $this->errors[2] ) && $this->timeout_check_count < $this->timeout_check_max );

        // Construct the return URL for the client
        $this->url = $this->createReturnURL( $remove_login_logout_from_return_url );

        // Add our return URL to the login and logout urls

        // The following commented code block allows skipping an extra
        // redirect to the application and another redirect to duo on
        // a duo enforced app. However, a direct redirect to the duo
        // application now works and this is not required but left
        // for reference in case something changes.

        //// Have duo redirects conditional on login status because of the process
        //// of url decoding between internal redirects (singlefactor -> twofactor)
        //if ( $this->isLoggedIn('singlefactor') )
        //{
        //    // already have singlefactor login, so redirect to DUO
        //    $this->login_url_duo .= '?return_url=' . rawurlencode( $this->url );
        //}
        //else
        //{
        //    // no single factor login, so redirect to single factor login
        //    // with return url to DUO which has return url to application
        //    $this->login_url_duo = $this->login_url . '?return_url=' . rawurlencode( $this->login_url_duo . '?return_url=' . rawurlencode( $this->url ) ) . '';
        //}

        $this->login_url_duo .= '?return_url=' . rawurlencode( $this->url );


        $this->login_url  .= '?return_url=' . rawurlencode( $this->url );
        $this->logout_url .= '?return_url=' . rawurlencode( $this->url );

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

    // Make requests with options for method, timeout, and others
    public function makeRequest( $type = 'http', $method = 'GET', $location = '', $timeout = 30, $request_headers = NULL )
    {
        $request_options                     = array();
        $request_options[ $type ]            = array();
        $request_options[ $type ]['method']  = $method;
        $request_options[ $type ]['timeout'] = $timeout;

        if ( isset( $request_headers ) )
        {
            $request_options[ $type ]['header'] = $request_headers;
        }
        $request_context    = stream_context_create( $request_options );
        $request_data       = @file_get_contents( $location, false, $request_context ); // @ used to suppress E_WARNING generated by failed fetch

        return $request_data;
    }

    // Create the return url for the client
    public function createReturnURL( $remove_login_logout_from_return_url )
    {
        $return_url = '';

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
        //$return_url = $prefix . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

        $return_url = $prefix . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
        // Add _GET vars to the url request, optionally removing login/logout vars
        if ( count( $_GET ) > 0 )
        {
            $i = 0;
            foreach ( $_GET as $k => $v )
            {
                if ( !$remove_login_logout_from_return_url || ( $k !== 'login' && $k !== 'logout' ) )
                {
                    $url_prefix = '&';
                    if ( $i === 0 )
                    {
                        $url_prefix = '?';
                    }
                    $return_url .= $url_prefix . rawurlencode( $k ) . '=' . rawurlencode( $v );
                    $i++;
                }
            }
        }

        return $return_url;
    }
    // Check the authentication from the cookie
    public function checkAuth()
    {
        // Check and require the cookie
        if ( empty( $this->cookie ) || $this->cookie === 'no_key' )
        {
            return false;
        }
        // Check and require connection to login.uci.edu
        //$auth_array = file( $this->check_url );

        $request_data = $this->makeRequest( 'http', 'GET', $this->check_url, $this->timeout_check );
        //if ( $auth_array === false )
        if ( $request_data === false )
        {
            $this->errors[2] = 'Unable to connect to login.uci.edu';
            return false;
        }

        $auth_array = explode( "\n", $request_data );


//print_r($auth_array);


        // Save the authentication return values
        foreach ( $auth_array as $k => $v )
        {
            if ( empty( $v ) === false )
            {
                $v = trim( $v );
                $auth_values = explode( '=', $v );
                if ( empty( $auth_values[0] ) === false && empty( $auth_values[1] ) === false )
                {
                    $var_name   = $auth_values[0];
                    $var_value  = $auth_values[1];
                    $this->$var_name = $var_value;
                }
            }
        }
        // Check and require that auth_host matches with the client's ip
        if ( $this->auth_host !== $this->remote_addr )
        {
            $this->errors[3] = 'Warning, the auth host doesn\'t match.';
            return false;
        }
        return true;
    }
    // Determine if someone's logged in
    // $check_type can be one of 'all', 'singlefactor', 'twofactor'
    // 'all'          => Conditional login check based on status on twofactor enabled
    // 'singlefactor' => Login check restricted to singlefactor
    // 'twofactor'    => Login check restricted to twofactor
    public function isLoggedIn( $check_type = 'all' )
    {
        $status = false;

        $auth_contexts = explode( ',', $this->auth_contexts );

        $has_single_factor = in_array( 'password', $auth_contexts, true );
        $has_two_factor    = in_array( 'multifactor', $auth_contexts, true );

        if ( $check_type === 'all' )
        {
            // Verify that the client's authentication method includes Duo if enforcing Duo.
            if ( $this->enforce_duo )
            {
                $status = $has_single_factor && $has_two_factor;
            }
            else
            {
                $status = $has_single_factor;
            }
        }
        else if ( $check_type === 'singlefactor' )
        {
            $status = $has_single_factor;
        }
        else if ( $check_type === 'twofactor' )
        {
            $status = $has_single_factor && $has_two_factor;
        }
        // The following is the old method to determine single factor status
        // $this->time_created can't be 0, false, null, empty string
        //if ( $this->time_created != false )
        //{
        //    return true;
        //}
        return $status;
    }
    // Redirect user to WebAuth login (Duo login if parameter is true)
    public function login()
    {
        if ( $this->enforce_duo )
        {
            header( 'Location: ' . $this->login_url_duo );
        }
        else
        {
            header( 'Location: ' . $this->login_url );
        }
        exit();
        return;
    }
    // Redirect user to WebAuth logout (which also clears Duo login)
    public function logout()
    {
        header( 'Location: ' . $this->logout_url );
        setcookie('ucinetid_auth','', time() - 3600,"/",'.uci.edu');
//        exit();
//        return;
    }
}
