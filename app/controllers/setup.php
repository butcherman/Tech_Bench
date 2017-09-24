<?php
/*
|   Setup controller begins the application setup process to create the database and initial configuration paramaters
*/
class Setup extends Controller
{
    //  Bring up the welcome page and test if the necessary folders are writable.
    public function index()
    {
        //  Set the valid and invalid image files
        $valid = 'go.png';
        $inval = 'stop.png';
        
        //  Determine if the necessary folders are writeable.  These are the folders to store logs, store the confi file, and upload files
        is_writable(__DIR__.'/../../logs') ? $data['logs'] = $valid : $data['logs'] = $inval;
        is_writable(__DIR__.'/../../config') ? $data['config'] = $valid : $data['config'] = $inval;
        
        $this->view('setup.beginSetup', $data);
        $this->template('standard');
        $this->render();
    }
    
    //  Step 1 is basic system information
    public function step1()
    {
        $this->view('setup.form1');
        $this->template('standard');
        $this->render();
    }
    
    //  Step 2 is database information
    public function step2()
    {
        $this->view('setup.form2');
        $this->template('standard');
        $this->render();
    }
    
    //  Step 3 is email information
    public function step3()
    {
        $this->view('setup.form3');
        $this->template('standard');
        $this->render();
    }
    
    //  Step 4 is file information
    public function step4()
    {
        $data['fileLocal'] = $_SERVER['DOCUMENT_ROOT'].'/_files';
        $data['maxFile'] = preg_replace("/[^0-9]/", "", ini_get('upload_max_filesize'));
        $data['encryptionKey'] = substr(md5(uniqid(rand(), true)), 0, 20);
        
        $this->view('setup.form4', $data);
        $this->template('standard');
        $this->render();
    }
    
    //  Bring up display that shows progress of setup process
    public function finish()
    {
        $this->view('setup.createSite');
        $this->template('standard');
        $this->render();
    }
    
    //  cycle through each step and run the designated function
    public function create($step)
    {
        $step = str_replace(' ', '', $step);
        if(method_exists($this, $step))
        {
            $result = $this->$step();
        }
        else
        {
            $result = 'Invalid Step';
        }
        
        $this->render($result);
    }
    
    //  Submit each form and place it into a SESSION variable
    public function submit()
    {
        foreach($_POST as $key => $value)
        {
            $_SESSION['setupData'][$key] = $value;
        }
        
        $this->render('success');
    }
    
    //  Test the database connection
    public function testDatabase()
    {
        //  Set all database variables
        $dbHost = $_POST['server'];
        $dbName = $_POST['database'];
        $dbUser = $_POST['user'];
        $dbPass = $_POST['password'];
        
        //  Since this is a test, it is possible for infalid information to be entered.  Errors will be supressed
        @$mysqli = new mysqli($dbHost, $dbUser, $dbPass);
        
        //  Determine if there was an error during setup or not
        if($mysqli->connect_errno)
        {
            $valid = mysqli_connect_error();
        }
        else
        {
            $valid = 'success';
        }
        
        echo json_encode($valid);
    }
    
    //  Test the email connection
    public function testEmail()
    {
        //  Email message that is sent in test email
        $msg = '<h1>Congratulations</h1><p>You have successfuly configured Tech Bench Email</p>';
        
        //  Prepare email data
        $emHost = $_POST['host'];
        $emPort = $_POST['port'];
        $emUser = $_POST['user'];
        $emAddr = $_POST['addr'];
        $emPass = $_POST['pass'];
        
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Mailer = 'smtp';
        $mail->Host = $emHost;
        $mail->Port = $emPort;
        $mail->SMTPAuth = true;
        $mail->Username = $emUser;
        $mail->Password = $emPass;
        $mail->setFrom = $emAddr;
        $mail->addAddress($emUser);
        $mail->Subject = 'Test Message From Tech Bench';
        $mail->msgHTML($msg);
        
        //  Send test email
        if($mail->send())
        {
            $valid = 'success';
        }
        else
        {
            $valid = 'Connection Failed';
        }
        
        //  Return the result in JSON format
        echo json_encode($valid);
    }
    
    //  Finalize step 1 is to create the configuration file
    public function configurationFile()
    {
        ob_start();
            require __DIR__.'/../views/setup/setup.defaultConfig.php';
        $configFile = ob_get_clean();
        $configPath = __DIR__.'/../../config/config.ini';
        file_put_contents($configPath, $configFile, LOCK_EX);
        
        sleep(2);
        
        return 'success';
    }
    
    //  Finalize step 2 is to create the database
    public function newDatabase()
    {
        //  Get information to create the database connection
        $dbHost = $_SESSION['setupData']['dbServer'];
        $dbUser = $_SESSION['setupData']['dbUser'];
        $dbPass = $_SESSION['setupData']['dbPass'];
        $charset = 'utf8';
        $dsn = 'mysql:host='.$dbHost.';charset='.$charset;
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        //  Try to connect to the database and create PDO object.
        try
        {
            $db = new PDO($dsn, $dbUser, $dbPass, $opt);
        }
        catch(PDOException $e)
        {
            echo 'Error Connecting to Database Server';
        }
        
        //  Bring in default databse files
        require __DIR__.'/../views/setup/setup.defaultDatabase.php';
        require __DIR__.'/../views/setup/setup.defaultTriggers.php';
        require __DIR__.'/../views/setup/setup.defaultViews.php';       
        
        //  Run the query to create the databse   
        try
        {
            $db->exec($database);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            die();
        }

        //  Run the queries to create the triggers
        foreach($triggers as $trigger)
        {
            try
            {
                $db->exec($trigger);
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
                die();
            }
        }
        
        //  Run the queries to create the views
        foreach($views as $view)
        {
            try
            {
                $db->exec($view);
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
                die();
            }
        }
        
        //  Insert the email information into the 
        
        return 'success';
    }
    
    //  Finalize step 3 is to build the system defaults
    public function defaults()
    {
        $mysqli = new mysqli($_SESSION['setupData']['dbServer'], $_SESSION['setupData']['dbUser'], $_SESSION['setupData']['dbPass'], $_SESSION['setupData']['dbName']);
        
        //  If the databse connection fails, kick out error
        if($mysqli->connect_errno)
        {
            echo mysqli_connect_error();
            die();
        }
        
        //  Create password hash
        $salt = substr(md5(uniqid(rand(), true)), 0, 5);
        $pass = hash('sha256', $salt.hash('sha256', $_SESSION['setupData']['sitePass']));
        
        //  Insert the user
        $qry = 'INSERT INTO USERS (`username`, `first_name`, `last_name`, `email`, `password`, `salt`, `active`, `change_password`) VALUES ("'.$_SESSION['setupData']['siteUser'].'", "System", "Administrator", "'.$_SESSION['setupData']['siteEmail'].'", "'.$pass.'", "'.$salt.'", 1, 0)';
        
        $mysqli->query($qry);
        
        sleep(2);
        
        //  Give the user the Site Admin functionality
        $qry2 = 'INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES (1, 1)';
        $mysqli->query($qry2);
        
        return 'success';
    }
    
    //  Finalize step 4 is to create the directory structure that all files will be stored in
    public function directoryStructure()
    {
        //  Folder array for each folder used
        $folders = array(
            Config::getFile('default'),
            Config::getFile('custPath'),
            Config::getFile('sysPath'),
            Config::getFile('tipPath'),
            Config::getFile('formPath'),
            Config::getFile('uploadPath')
        );
        
        //  Create the folders
        foreach($folders as $folder)
        {
            mkdir(Config::getFile('uploadRoot').$folder, 0777, true);
        }
        
        sleep(2);
        
        //  Log user in     
        session_regenerate_id();
        $_SESSION['valid'] = 1;
        $_SESSION['id'] = 1;
        $_SESSION['username'] = $_SESSION['setupData']['siteUser'];
        $_SESSION['name'] = 'Administrator User';
        $_SESSION['changePassword'] = 0;
        
        return 'success';
    }
    
    //  Finalize step 5 is to input the default settings into the database
    public function systemSettings()
    {
        //  Get information to create the database connection
        $dbHost = $_SESSION['setupData']['dbServer'];
        $dbUser = $_SESSION['setupData']['dbUser'];
        $dbPass = $_SESSION['setupData']['dbPass'];
        $charset = 'utf8';
        $dsn = 'mysql:host='.$dbHost.';charset='.$charset;
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        //  Try to connect to the database and create PDO object.
        try
        {
            $db = new PDO($dsn, $dbUser, $dbPass, $opt);
        }
        catch(PDOException $e)
        {
            echo 'Error Connecting to Database Server';
        }
        
        $qry = 'INSERT INTO `_settings` (`setting`, `value`) VALUES 
                    ("email_user", '.$_SESSION['setupData']['emUser'].'), 
                    ("email_pass", '.$_SESSION['setupData']['emPass'].'), 
                    ("email_host", '.$_SESSION['setupData']['emHost'].'), 
                    ("email_port", '.$_SESSION['setupData']['emPort'].'),
                    ("email_from", '.$_SESSION['setupData']['emAddr'].'), 
                    ("email_name", "Tech Bench"),
                    ("allow_company_forms", 1),
                    ("allow_upload_links", 1)';
    }
}
