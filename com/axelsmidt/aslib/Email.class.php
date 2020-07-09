<?php

namespace com\axelsmidt\aslib;

class Email {

    protected $to;
    protected $subject;
    protected $message;
    protected $headers;

    /**
     *
     */
    public function __construct($to, $subject, $message) {
        // Compose the email.
        $this->to = $to;
        $this->subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $this->message = utf8_decode($message);

        $this->headers = 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
        $this->headers = 'From: no-reply@liksom-ski-vm.no' . "\r\n";
        $this->headers .= 'Reply-To: no-reply@liksom-ski-vm.no' . "\r\n";
    }

    /**
     *
     */
    public function send() {
        // Send the email.
        if (!mail($this->to, $this->subject, $this->message, $this->headers)) {
            $error = 'Sending av flg. mail feilet:' . "\r\n";
            $error .= 'To: ' . $this->to . "\r\n";
            $error .= $this->message . "\r\n\r\n";
            $error .= $this->headers . "\r\n";
            $error .= date('d.m.Y H:i') . "\r\n";
            $error .= '** End of message **' . "\r\n\r\n";

            global $errorlogpath; // From config.inc.php.
            error_log($error, 3, $errorlogpath);
            return false;
        } else {
            return true;
        }
    }

}
