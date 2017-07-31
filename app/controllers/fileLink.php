<?php
/*
|   File-Link Controller allows the visitors to upload files for users to access.
|   This is generally helpful when a user needs access to a file that is too large to email
*/

class FileLink extends Controller
{
    //  Landing page 
    public function index($linkHash)
    {
        $model = $this->model('fileLinks');
        
        $linkID = $model->getLinkID($linkHash);
        
        if(!$linkID)
        {
            $this->view('error.badLink');
        }
        else if($model->isLinkExpired($linkID->link_id))
        {
            $this->view('links.expiredLink');
        }
        else
        {
            $linkID = $linkID->link_id;
            $linkFiles = $model->getLinkFiles($linkID);
            
            $data['linkID'] = $linkID;
            $data['files'] = '';
            foreach($linkFiles as $file)
            {
                if(is_numeric($file->added_by))
                {
                    $data['files'] .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'" title="Click to Download">'.$file->file_name.'</a></td><td>'.date('M j, Y', strtotime($file->added_on)).'</td></tr>';
                }
            }
            
            $this->view('links.visitor.validLink', $data);
        }
        
        $this->template('standard');
        $this->render();
    }
    
    public function submitNewFIle($linkID)
    {
        print_r($_POST);
    }
}
