<?phpclass FollowerTrigger extends MF_ActionTriggerAbstract{		public function __construct( Follower $model ){		parent::__construct( $model );	}		public function afterInsert(){		$user1 = $this->model->getParent( 'User', 'user1' );		$user2 = $this->model->getParent( 'User', 'user2' );				$data = array(			'user' => array( 'text'=>$user2->getFullName(), 'id'=>$user1->id ),		);		MF_FeedNotification::sendFeed($user1, $data, 1);				$data = array(			'user' => array( 'text'=>$user1->getFullName(), 'id'=>$user2->id ),		);				MF_FeedNotification::sendNotification($user2, $data, 1);			}	}