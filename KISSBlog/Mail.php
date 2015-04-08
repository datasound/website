<?php

namespace KISSBlog;

class Mail {

	public function __construct($to, $subject, $message, $replyTo, $from, $xmailer = 'KISSBlog/PHP') {
		$this->xmailer = $xmailer;
		$this->to = $to;
		$this->subject = $subject;
		$this->from = $from;
		$this->message = $message;
		$this->replyTo = $replyTo;
	}

	public function setTo($address) {
		$this->to = $address;
	}

	public function setFrom($address) {
		$this->from = $address;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	public function setReplyTo($replyTo) {
		$this->replyTo = $replyTo;
	}

	public function send() {

		if (isset($this->to) && isset($this->from) && isset($this->subject) && isset($this->message) && isset($this->replyTo) && isset($this->xmailer)) {
			$headers = 'From: ' . $this->from . "\r\n" .
			'Reply-To: ' . $this->replyTo . "\r\n" .
			'X-Mailer: ' . $this->xmailer . phpversion();
			return mail($this->to, $this->subject, $this->message, $headers);
		}
		return false;
	}

	private $to;
	private $subject;
	private $message;
	private $from;
	private $replyTo;
	private $xmailer;
}
