<?php

namespace SimpleUser\Service;

use Psr\Log\LoggerInterface;
use Twig_Environment as Environment;
use \Swift_Message as Message;

class MailerBuilderService
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Environment
     */
    protected $templateEngine;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $pathToTemplate;

    /**
     * @var array
     */
    protected $templateVars;

    /**
     * @var string
     */
    protected $emailFrom;

    /**
     * @var array
     */
    protected $emailTo;

    /**
     * @var array
     */
    protected $attachments;

    /**
     * @param \Swift_Mailer $mailer
     * @param LoggerInterface $logger
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger, Environment $twig) {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->templateEngine = $twig;
        $this->message = new Message();
    }

    /**
     * @return $this
     */
    public function restart()
    {
        $this->message = new Message();

        return $this;
    }

    /**
     * @return $this
     */
    public function send()
    {
        if ($this->mailer->send($this->message) === 0) {
            $this->logger->emergency(sprintf('We did not send email to about %s!!!', $this->subject));
        } else {
            $this->restart();
        }

        return $this;
    }

    /**
     * @param string $subject
     * @return MailerBuilderService
     */
    public function setSubject(string $subject): MailerBuilderService
    {
        $this->subject = $subject;
        $this->message->setSubject($this->subject);

        return $this;
    }


    /**
     * @param string $pathToTemplate
     * @param array $vars
     * @return MailerBuilderService
     */
    public function setTemplate(string $pathToTemplate, array $vars): MailerBuilderService
    {
        $this->pathToTemplate = $pathToTemplate;
        $this->templateVars = $vars;

        try {
            $this->message->setBody(
                $this->templateEngine->render(
                    $this->pathToTemplate,
                    $this->templateVars
                ),
                'text/html'
            );
        } catch (\Twig_Error $e) {
            $this->logger->emergency('Got error when build email message: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * @param string $emailFrom
     * @return MailerBuilderService
     */
    public function setEmailFrom(string $emailFrom): MailerBuilderService
    {
        $this->emailFrom = $emailFrom;
        $this->message->setFrom($this->emailFrom);

        return $this;
    }

    /**
     * @param array $emailTo
     * @return MailerBuilderService
     */
    public function setEmailTo(array $emailTo): MailerBuilderService
    {
        $this->emailTo = $emailTo;
        $this->message->setTo($emailTo);

        return $this;
    }


    /**
     * @param array $attachments
     * @return MailerBuilderService
     */
    public function setAttachments(array $attachments): MailerBuilderService
    {
        $this->attachments = $attachments;

        foreach ($attachments as $attachment) {
            $this->message->attach(\Swift_Attachment::fromPath($attachment));
        }

        return $this;
    }

}