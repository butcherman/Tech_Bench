<?php
/*
|   Setup controller begins the application setup process to create the database and initial configuration paramaters
*/
class Setup extends Controller
{
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
}
