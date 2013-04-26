<?php
class MF_FeedNotification{
	
	public function __construct(){
		
	}
	
	public static function sendNotification( User $user, $data, $notification_type_id ){
		$notification_type = new NotificationType();
		
		if( $notification_type->select( $notification_type_id ) ){
			//create notification
			$notification = new Notification();
			$db = MF_Database::getDatabase();
			$sql = "SELECT * FROM `notifications` WHERE `users_id`={$user->id} AND `notification_types_id`=$notification_type_id";
			if( !$notification->selectFromSQL( $sql ) ){
				$notification->notification_types_id = $notification_type->id;
				$notification->users_id = $user->id;
				$notification->load($data);
				$push_setting = $notification_type->getParent('SettingType', 'PushSettingType');
				$notification->push_status = $user->getSettingValue( $push_setting->key, true )? Notification::STATUS_PENDING : null;
				$notification->save();
				
				// send email notification
				$email_setting = $notification_type->getParent('SettingType', 'EmailSettingType');
				if( $user->getSettingValue( $email_setting->key, true ) ){
					$mentioned_user = $notification->getParent( 'User', 'MentionedUser' );
					$mail_data = array(
						'user' => $mentioned_user->getFullName()
					);
					if( isset($data['shares_id']) ){
						$share = $notification->getParent( 'Share' );
						$application = $share->getParent( 'Application' );
						$mail_data['application'] = $application->application_name;
					}
					$mail_sender = new MF_MailSender( $email_setting->key.'.html' );
					$mail_sender->sendMail($user, 'no-reply@appsaway.com', $notification_type->title, $mail_data);
				}
			}
		}else{
			MF_Error::dieError( "Notification type: {$notification_type_id} Not found", 500 );
		}
		
	}

	public static function sendFeed( $user_id, $data, $feed_type_id ){
		$feed_type = new FeedType();
		
		if( $feed_type->select( $feed_type_id ) ){
			$feed = new Feed();
			$db = MF_Database::getDatabase();
			$sql = "SELECT * FROM `feeds` WHERE `users_id`={$user_id} AND `feed_types_id`=$feed_type_id";
			foreach( $data as $k => $d ){
				$sql .= "  AND `$k`='$d'";
			}
			if( $feed->selectFromSQL( $sql ) ){
				$feed->created_at = date( 'Y-m-d H:i:s' );
				$feed->save();
			}else{
				$feed->users_id = $user_id;
				$feed->feed_types_id = $feed_type->id;
				$feed->load($data);
				$feed->save();
			}
		}else{
			MF_Error::dieError( "$feed_type_id is not a valid feed type;" );
		}
		
	}
	
}
