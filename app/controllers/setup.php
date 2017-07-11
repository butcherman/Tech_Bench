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
        is_writable(__DIR__.'/../../_files') ? $data['files'] = $valid : $data['files'] = $inval;
        
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
    
    //  Bring up display that shows progress of setup process
    public function finish()
    {
        $this->view('setup.createSite', $_SESSION['setupData']);
        $this->template('standard');
        $this->render();
    }
    
    //  cycle through each step and run the designated function
    public function create($step)
    {
        sleep(3);
        echo 'success';
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
}
