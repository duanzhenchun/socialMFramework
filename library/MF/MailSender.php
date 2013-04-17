<?php
class MF_MailSender{
	
	protected $template_file;
	
	public function __construct( $template_file ){
		$this->template_file = $template_file;
	}
	
	public function sendMail( $to, $from, $subject, $vals ){
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Cabeceras adicionales
		if( get_class( $to ) == 'User' ){
			$headers .= 'To: '.$to->name.' <'.$to->email.'>' . "\r\n";
			$to_mail = $to->email;
		}else{
			$headers .= 'To: <'.$to.'>' . "\r\n";
			$to_mail = $to;
		}
		if( get_class( $from ) == 'User' ){
			$headers .= 'From: '.$from->name.' <'.$from->email.'>' . "\r\n";
		}else{
			$headers .= 'From: <'.$from.'>' . "\r\n";
		}
		
		return mail($to_mail, $subject, $this->parseContent( $vals ), $headers);
	}
	
	protected function parseContent( $vals ){
		if( file_exists($this->template_file) ){
			$file_path = $this->template_file;
		}elseif(file_exists(MAIL_TEMPLATES_PATH.'/'.$this->template_file)){
			$file_path = MAIL_TEMPLATES_PATH.'/'.$this->template_file;
		}
		if( isset($file_path) ){
			$handler = fopen($file_path, "r");
			$content = fread($handler, filesize($file_path));
			fclose($handler);
			foreach( $vals as $k => $v ){
				if( is_string($v) ){
					$content = str_replace( '#{'.$k.'}', $v, $content);
				}else{
					$content = str_replace( '#{'.$k.'}', $v->text, $content);
				}
			}
			return $content;
		}else{
			MF_Error::showError("{$this->template_file} couldn't be found",'500');
		}
	}
	
}

?>