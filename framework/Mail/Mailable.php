<?php

namespace LaraCore\Framework\Mail;

abstract class Mailable
{
    protected string $toEmail = '';
    protected string $toName = '';
    protected string $fromEmail = '';
    protected string $fromName = '';
    protected string $mailSubject = '';
    protected string $htmlBody = '';
    protected ?string $textBody = null;
    protected ?string $viewName = null;
    protected array $viewData = [];
    protected array $attachments = [];

    abstract public function build(): self;

    public function to(string $email, string $name = ''): self
    {
        $this->toEmail = $email;
        $this->toName = $name;
        return $this;
    }

    public function from(string $email, string $name = ''): self
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mailSubject = $subject;
        return $this;
    }

    public function view(string $viewName, array $data = []): self
    {
        $this->viewName = $viewName;
        $this->viewData = $data;
        return $this;
    }

    public function html(string $html): self
    {
        $this->htmlBody = $html;
        return $this;
    }

    public function text(string $text): self
    {
        $this->textBody = $text;
        return $this;
    }

    public function attach(string $filePath, string $name = ''): self
    {
        $this->attachments[] = ['path' => $filePath, 'name' => $name];
        return $this;
    }

    public function send(): bool
    {
        $this->build();
        return (new Mailer())->send($this);
    }

    public function getToEmail(): string { return $this->toEmail; }
    public function getToName(): string { return $this->toName; }
    public function getFromEmail(): string { return $this->fromEmail; }
    public function getFromName(): string { return $this->fromName; }
    public function getSubject(): string { return $this->mailSubject; }
    public function getAttachments(): array { return $this->attachments; }

    public function getRenderedBody(): string
    {
        if ($this->viewName !== null) {
            return $this->renderView();
        }
        return $this->htmlBody;
    }

    public function getTextBody(): ?string { return $this->textBody; }

    private function renderView(): string
    {
        $viewPath = ROOT . DS . 'resources' . DS . 'views' . DS
            . str_replace('.', DS, $this->viewName) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Mail view [{$this->viewName}] not found.");
        }

        extract($this->viewData);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
}
