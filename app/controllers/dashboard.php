<?php
/*
|   Dashboard controller is the initial landing page for registered users
*/
class Dashboard extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('tech');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        $this->template('techUser');
    }
    
    public function index()
    {
        $model = $this->model('dashboardModel');
        
        $custFavs = $model->getCustFavs($_SESSION['id']);
        $tipFavs = $model->getTechTipFavs($_SESSION['id']);
        $sysAlerts = $model->getSystemAlerts();
        $techAlerts = $model->getUserAlerts($_SESSION['id']);
        
        $data['sysAlerts'] = '';
        foreach($sysAlerts as $alert)
        {
            $data['sysAlerts'] .= '<div class="alert '.$alert->alert_level.'">'.$alert->description.'</div>';
        }
        
        $data['userAlerts'] = '';
        foreach($techAlerts as $alert)
        {
            $data['userAlerts'] .= '<div role="alert" class="alert '.$alert->alert_level.' fade in" data-id="'.$alert->user_alert_id.'"><a href="#" class="close" data-dismiss="alert" aria-label="close" data-tooltip="tooltip" title="Dismiss">&times;</a>'.$alert->description.'</div>';
        }
        
        $i = 0;
        $p = 4;
        $data['customerFavs'] = '<div class="row dashboard">';
        if(!empty($custFavs))
        {
            foreach($custFavs as $fav)
            {
                if($i % $p == 0 && $i != 0)
                {
                    $data['customerFavs'] .= '</div><div class="row dashboard">';
                }
                $data['customerFavs'] .= '<div class="col-md-3 col-xs-12"><a href="/customer/id/'.$fav->cust_id.'/'.str_replace(' ', '-', $fav->name).'" class="bookmark"><div class="well bookmark text-center">'.$fav->name.'</div></a></div>';

                $i++;
            }
        }
        else
        {
            $data['customerFavs'] .= '<div class="col-sm-12"><h3 class="text-center">No Bookmarks</h3></div>';
        }  
        $data['customerFavs'] .= '</div>';

        $i = 0;
        $p = 4;
        $data['techTipFavs'] = '<div class="row dashboard">';
        if(!empty($tipFavs))
        {
            foreach($tipFavs as $fav)
            {
                if($i % $p == 0 && $i != 0)
                {
                    $data['techTipFavs'] .= '</div><div class="row dashboard">';
                }
                $data['techTipFavs'] .= '<div class="col-md-3 col-xs-12"><a href="/tips/id/'.$fav->tip_id.'/'.str_replace(' ', '-', $fav->title).'" class="bookmark"><div class="well bookmark text-center">'.$fav->title.'</div></a></div>';

                $i++;
            }
        }
        else
        {
            $data['techTipFavs'] .= '<div class="col-sm-12"><h3 class="text-center">No Bookmarks</h3></div>';
        }  
        $data['techTipFavs'] .= '</div>';
        
        $this->view('tech.dashboard', $data);
        $this->render();
    }
    
    //  Load all user notifications
    public function loadNotifications()
    {
        $model = $this->model('dashboardModel');
        
        $notifications = $model->getNotifications($_SESSION['id']);
        
        if(!empty($notifications))
        {
            $note = '';
            foreach($notifications as $notify)
            {
                if(!$notify->viewed)
                {
                    $link = '<strong>'.$notify->description.'</strong>';
                    $date = '<strong>'.date('M j, Y', strtotime($notify->added_on)).'</strong>';
                }
                else
                {
                    $link = $notify->description;
                    $date = date('M j, Y', strtotime($notify->added_on));
                }

                $note .= '<tr><td><span class="glyphicon glyphicon-ok mark-notification" data-tooltip="tooltip" title="Mark Read" data-id="'.$notify->notification_id.'"></span> <span class="glyphicon glyphicon-remove delete-notification" data-tooltip="tooltip" title="Delete" data-id="'.$notify->notification_id.'"></span></td><td><a href="'.$notify->link.'" class="notification-link" data-id="'.$notify->notification_id.'">'.$link.'</a></td><td>'.$date.'</td></tr>';
            }
        }
        else
        {
            $note = '<tr><td colspan="3" class="text-center">No Notifications</td></tr>';
        }
        
        $this->render($note);
    }
    
    //  Ajax call to dismiss a user notification
    public function dismissUserAlert($alertID)
    {
        $model = $this->model('dashboardModel');
        $model->deleteUserNotification($alertID);
    }
    
    //  Ajax call to mark a notification as read
    public function markNotification($noteID)
    {
        $model = $this->model('dashboardModel');
        $model->markNotification($noteID);
    }
    
    //  Ajax call to delete a user notification
    public function deleteNotification($noteID)
    {
        $model = $this->model('dashboardModel');
        $model->deleteNotification($noteID);
    }
}
