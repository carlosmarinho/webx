<?php

# /app/Controller/HomeController.php

class EmailController extends AppController {

    public $name = 'Email';
    public $uses = array('Email');
    
    
    public function index(){
        $emails = $this->Email->find('all',array('limit'=>10, 'order'=>'id desc' ) );
        $this->set('emails', $emails );
    }
    
    public function ajax(){
        $this->autoLayout = false;
        //$this->autoRender = false;
        
        $emails = $this->Email->find('all',array('limit'=>10, 'order'=>'id desc' ) );
        $this->set('emails', $emails );
        
        
    }


}

?>