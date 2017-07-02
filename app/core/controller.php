<?php
/*
|   Controller Class builds the page template and all necessary methos for basic page operation
*/
class Controller
{
    private $view, $viewData, $template;
    
    //  Create a model object
    public function model($model)
    {
        require_once __DIR__.'/../models/'.$model.'.php';
        return new $model;
    }
    
    //  Assign a html template for the page
    public function template($template)
    {
        $this->template = $template;

    }
    
    //  Assign the HTML data that goes within the template (or alone in the event of an ajax call)
    public function view($view, $data = [])
    {
        $this->view = $view;
        $this->viewData = $data;
    }
    
    //  Assign the HTML data for an email
    public function emailView($view, $data = [])
    {
        ob_start();
            include __DIR__.'/../views/email/'.$view.'.php';
        return ob_get_clean();
    }
    
    //  Echo out the template and the view
    public function render($content = '')
    {
        if(!empty($content))
        {
            echo $content;
        }
        else if(!empty($this->template))
        {
            ob_start();
                $data = $this->viewData;
                if(!empty($this->view))
                {
                    include __DIR__.'/../views/standard/'.$this->view.'.php';
                }
            $content = ob_get_clean();
            
            include __DIR__.'/../views/templates/'.$this->template.'.php';
        }
        else if(!empty($this->view))
        {
            $data = $this->viewData;
            include __DIR__.'/../views/standard/'.$this->view.'.php';
        }
    }
}
