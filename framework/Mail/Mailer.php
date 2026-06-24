<?php

namespace LaraCore\Framework\Mail;

use LaraCore\Framework\Configuration;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $phpMailer;

    public function __construct()
    {
        $config = Configuration::get('mail');

        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host     = $config['host'];
        $this->phpMailer->Port     = (int) $config['port'];
        $this->phpMailer->isHTML($config['html']);
        $this->phpMailer->CharSet  = PHPMailer::CHARSET_UTF8;

        $encryption = $config['smtp_secure'];
        if ($encryption && $encryption !== 'null') {
            $this->phpMailer->SMTPSecure = $encryption;
            $this->phpMailer->SMTPAuth   = $config['smtp_auth'];
            $this->phpMailer->Username   = $config['username'];
            $this->phpMailer->Password   = $config['password'];
        } else {
            $this->phpMailer->SMTPAuth = false;
        }

        $this->phpMailer->setFrom($config['from_address'], $config['from_name']);
    }

    public function send(Mailable $mailable): bool
    {
        try {
            $fromEmail = $mailable->getFromEmail();
            if ($fromEmail) {
                $this->phpMailer->setFrom($fromEmail, $mailable->getFromName());
            }

            $this->phpMailer->addAddress($mailable->getToEmail(), $mailable->getToName());
            $this->phpMailer->Subject = $mailable->getSubject();
            $this->phpMailer->Body    = $mailable->getRenderedBody();

            $textBody = $mailable->getTextBody();
            if ($textBody !== null) {
                $this->phpMailer->AltBody = $textBody;
            }

            foreach ($mailable->getAttachments() as $attachment) {
                $this->phpMailer->addAttachment($attachment['path'], $attachment['name']);
            }

            return $this->phpMailer->send();
        } catch (Exception $e) {
            throw new \RuntimeException('Mail could not be sent: ' . $this->phpMailer->ErrorInfo);
        }
    }
}
