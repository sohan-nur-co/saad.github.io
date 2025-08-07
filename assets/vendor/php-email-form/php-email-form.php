<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHP_Email_Form {
    public $to = '';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $smtp = null; // SMTP সেটিংস অ্যারে
    public $ajax = false;

    private $messages = [];

    public function add_message($message, $label = '', $max_length = 1000) {
        $message = trim(strip_tags($message));
        if (strlen($message) > $max_length) {
            $message = substr($message, 0, $max_length) . '...';
        }
        $this->messages[] = ($label ? $label . ': ' : '') . $message;
    }

    public function send() {
        if (!$this->to) {
            return 'Error: To address is missing.';
        }
        if (!$this->from_email || !filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
            return 'Error: Invalid from email.';
        }
        if (!$this->from_name) {
            $this->from_name = 'Website Visitor';
        }

        $body = implode("\n", $this->messages);

        // PHPMailer ব্যবহার করার জন্য autoload দরকার
        require_once __DIR__ . '/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            if ($this->smtp) {
                // SMTP সেটআপ
                $mail->isSMTP();
                $mail->Host = $this->smtp['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $this->smtp['username'];
                $mail->Password = $this->smtp['password'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = $this->smtp['port'];
            }

            $mail->setFrom($this->from_email, $this->from_name);
            $mail->addAddress($this->to);
            $mail->Subject = $this->subject;
            $mail->Body = $body;

            $mail->send();

            return 'Message sent successfully!';
        } catch (Exception $e) {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}
?>
