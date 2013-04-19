<?php
class MF_FeedNotification{
	
	public function __construct(){
		
	}
	
	public static function sendNotification( $user, $data, $notification_type_id ){
		
		$encoded_data = json_encode( $data );
		
		$notification_type = new NotificationType();
		
		if( $notification_type->select( $notification_type_id ) ){
			//create notification
			$notification = new Notification();
			$db = MF_Database::getDatabase();
			$sql = "SELECT * FROM `notifications` WHERE `users_id`={$user->id} AND `notification_types_id`={$notification_type->id} AND `serialized_data`='".$db->escape($encoded_data)."'";
			if( !$notification->selectFromSQL( $sql ) ){
				$notification->notification_types_id = $notification_type->id;
				$notification->users_id = $user->id;
				$notification->serialized_data = $encoded_data;
				$push_setting = $notification_type->getParent('SettingType', 'PushSettingType');
				$notification->push_status = $user->getSettingValue( $push_setting->key, true )? Notification::STATUS_PENDING : null;
				$notification->save();
				
				// send email notification
				$email_setting = $notification_type->getParent('SettingType', 'EmailSettingType');
				if( $user->getSettingValue( $email_setting->key, true ) ){
					$mail_sender = new MF_MailSender( $email_setting->key.'.html' );
					$mail_sender->sendMail($user, 'no-reply@appsaway.com', $notification_type->title, $data);
				}
			}
		}else{
			MF_Error::dieError( "Notification type: {$notification_type_id} Not found", 500 );
		}
		
	}

	public static function sendFeed( $user, $data, $feed_type_id, $exclude_user_id = null ){
		$encoded_data = json_encode( $data );
		$feed_type = new FeedType();
		
		if( $feed_type->select( $feed_type_id ) ){
			$feed = new Feed();
			$db = MF_Database::getDatabase();
			$sql = "SELECT * FROM `feeds` WHERE `users_id`={$user->id} AND `feed_types_id`={$feed_type->id} AND `serialized_data`='".$db->escape($encoded_data)."'";
			if( $feed->selectFromSQL( $sql ) ){
				$feed->created_at = date( 'Y-m-d H:i:s' );
				$feed->save();
			}else{
				$feed->users_id = $user->id;
				$feed->feed_types_id = $feed_type->id;
				$feed->serialized_data = $encoded_data;
				$feed->excluded_user_id = $exclude_user_id;
				$feed->save();
			}
		}
		
	}
	
}
