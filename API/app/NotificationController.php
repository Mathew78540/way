<?php

class NotificationController extends Controller{

	public $uses = array('Notification');

	function __construct() {
		parent::__construct();
	}

	public function add($f3){
		if(!empty($notification = $f3->get('POST'))) {
			if($this->Notification->validate($notification)){
				if($this->Notification->add($notification)){
					echo $this->Notification->encode('notification', array('code' => '201', 'message' => 'notification added'));
				}else{
					$this->send_error(array('code' => '400', 'message' => 'database error'));
				}
			}else{
				$this->send_error(array('code' => '400', 'message' => 'bad request'));
			}

		}else{
			$this->send_error(array('code' => '400', 'message' => 'bad request'));
		}
	}

	public function get($f3){
		if($f3->get('PARAMS.facebook_id')){
			$notifications = $this->Notification->getNotification($f3->get('PARAMS.facebook_id'));
			if(!empty($notifications)){
				echo $notifications;
			}else{
				$this->send_error(array('code' => '204', 'message' => 'notification not found'));
			}
		}
	}



}

 ?>