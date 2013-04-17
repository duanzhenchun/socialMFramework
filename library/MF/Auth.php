<?php
    class MF_Auth
    {
        /**
         * 
         * Singleton object
         * @var MF_Auth
         */
        private static $_instance;
        
        /**
         * 
         * Model User object
         * @var User
         */
        public $user;
		
        /**
         * 
         * true if any user is logged in
         * @var boolean
         */
        private $loggedIn;
        
        /**
         * 
         * Secret key for hash the password
         * @var string
         */
        private $secret_key = '~h#!Y)8&%_ys&HY';

        /**
         * 
         * Call with no arguments to attempt to restore a previous logged in session
         * which then falls back to a guest user (which can then be logged in using
         * $this->login($email, $pw). Or pass a user_id to simply login that user. The
         * $seriously is just a safeguard to be certain you really do want to blindly
         * login a user. Set it to true.
         */
        private function __construct() {
            $this->loggedIn       = false;
            $this->user = new User();
            
            if($this->attemptSessionLogin()){
                return;
            }
        }

        /**
         * Standard singleton
         * @return MF_Auth
         */
        public static function getInstance() {
            if( self::$_instance === null )
                self::$_instance = new MF_Auth();
            return self::$_instance;
        }

        /**
         * 
         * You'll typically call this function when a user logs in using
         * a form. Pass in their email and password.
         * Takes a email and a *plain text* password
         * @param string $email email
         * @param string $pw unhashed password
         */
        public function login($email, $pw) {
            if( !$this->attemptLogin($email, $pw) ) return false;
            $this->user->last_login = date("Y-m-d H:i:s");
            $this->user->save();
            return true;
        }
		
        /**
         * 
         * Close the user session
         */
        public function logout(){
            $this->loggedIn       = false;
			$this->user = new User();
            unset($_SESSION['user_id']);
        }
		
        /**
         * 
         * Is a user logged in? This was broken out into its own function
         * in case extra logic is ever required beyond a simple bool value.
         * @return loggedIn
         */
        public function isLogged() {
            return $this->loggedIn;
        }
        
    	/**
         * 
         * Is a user logged in and are an administrator? This was broken out into its own function
         * in case extra logic is ever required beyond a simple bool value.
         * @return isAdmin
         */
        public function isAdmin() {
            return $this->loggedIn && $this->user->level == 'admin';
        }
		
        // 
        // 
        // 
        // 
        /**
         * 
         * Login a user simply by passing in their email or id. Does
         * not check against a password. Useful for allowing an admin user
         * to temporarily login as a standard user for troubleshooting.
         * @param unknown_type $user_to_impersonate Takes an id or email
         */
        public function impersonate($user_to_impersonate){
			$this->user = new User();
			$table_name = $this->user->tableName;
            $db = MF_Database::getDatabase();
            if(ctype_digit($user_to_impersonate))
                $sql = 'SELECT * FROM `'.$table_name.'` WHERE id = ' . $db->quote($user_to_impersonate);
            else
                $sql = 'SELECT * FROM `'.$table_name.'` WHERE email = ' . $db->quote($user_to_impersonate);
			$row = $db->getRow( $sql );
            if(is_array($row)) {
				$this->user = new User();
				$this->user->select( $row['id'] );

                $this->storeSessionData($this->user->id);
                $this->loggedIn = true;
				$this->user->last_login = date("Y-m-d H:i:s");
				$this->user->save();
                return true;
            }

            return false;
        }
        
        /**
         * 
         * Attempt to login using data stored in the current session
         */
        private function attemptSessionLogin(){
            if(isset($_SESSION['user_id']))
                return $this->impersonate($_SESSION['user_id']);
            else
                return false;
        }
		
        /**
         * 
         * The function that actually verifies an attempted login and
         * processes it if successful.
         * @param password $email email
         * @param unknown_type $pw <strong>hashed</strong> password
         * @return boolean true if the attemp is successful
         */
        private function attemptLogin($email, $pw) {
			$this->user = new User();
			$table_name = $this->user->tableName;
        	$pw = $this->createHashedPassword($pw);
        	
            $db = MF_Database::getDatabase();
            
            // We SELECT * so we can load the full user record into the user Model later
            $row = $db->getRow('SELECT * FROM `'.$table_name.'` WHERE email = ' . $db->quote($email));
            if($row === false) return false;
			
            if($pw != $row['password']) return false;

            // Load any additional user info if Model and User are available
			$this->user->select($row['id']);
            $this->storeSessionData($this->user->id);
            $this->loggedIn = true;

            return true;
        }

        // Takes a email and a *hashed* password
        /**
         * 
         * Store a user in the user session data
         * @param int $u_id User id
         */
        private function storeSessionData($u_id){
            if(headers_sent()) return false;
            $_SESSION['user_id'] = $u_id;
        }
		
        /**
         * 
         * Hashed and return a password
         * @param string $pw unhashed password
         * @return hashed_password
         */
        public function createHashedPassword($pw) {
            return md5($this->secret_key.$pw);
        }
    }
