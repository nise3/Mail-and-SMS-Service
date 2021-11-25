<?php


namespace App\Services;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 *
 */
class MailService
{

    private array $to;
    private string $form;
    private string $recipientName;
    private string $replyTo;
    private string $subject;
    private string $messageBody;
    private string $template;
    private array $cc;
    private array $bcc;
    private array $attachments;

    public const RECIPIENT_NAME = 'Nise3';

    /** FILE_EXTENSION_ALLOWABLE KEY */
    public const ALLOWABLE_PDF = "pdf";
    public const ALLOWABLE_DOC = "doc";
    public const ALLOWABLE_DOCX = "docx";
    public const ALLOWABLE_CSV = "csv";
    public const ALLOWABLE_XLS = "xls";
    public const ALLOWABLE_XLSX = "xlsx";
    public const ALLOWABLE_TEXT = "text";
    public const ALLOWABLE_TXT = "txt";
    public const ALLOWABLE_JPEG = "jpeg";
    public const ALLOWABLE_JPG = "jpg";
    public const ALLOWABLE_JPE = "jpe";
    public const ALLOWABLE_PNG = "png";
    const FILE_EXTENSION_ALLOWABLE = [
        self::ALLOWABLE_PDF,
        self::ALLOWABLE_DOC,
        self::ALLOWABLE_DOCX,
        self::ALLOWABLE_CSV,
        self::ALLOWABLE_XLS,
        self::ALLOWABLE_XLSX,
        self::ALLOWABLE_TEXT,
        self::ALLOWABLE_TXT,
        self::ALLOWABLE_JPEG,
        self::ALLOWABLE_JPE,
        self::ALLOWABLE_JPG,
        self::ALLOWABLE_PNG
    ];

    /** MIMETYPE */
    const MAIL_MIMETYPE = [
        self::ALLOWABLE_PDF => 'application/pdf',
        self::ALLOWABLE_DOC => 'application/msword',
        self::ALLOWABLE_DOCX => 'application/msword',
        self::ALLOWABLE_XLS => 'application/vnd.ms-excel',
        self::ALLOWABLE_XLSX => 'application/vnd.ms-excel',
        self::ALLOWABLE_CSV => 'text/comma-separated-values',
        self::ALLOWABLE_JPEG => 'image/jpeg',
        self::ALLOWABLE_JPE => 'image/jpeg',
        self::ALLOWABLE_JPG => 'image/jpeg',
        self::ALLOWABLE_PNG => 'image/png',
        self::ALLOWABLE_TEXT => 'text/plain',
        self::ALLOWABLE_TXT => 'text/plain'
    ];


    /**
     * @param string $form
     * @param array $to
     * @param string $subject
     * @param string $messageBody
     * @param string $recipientName
     * @param string $replyTo
     * @param array $cc
     * @param array $bcc
     */
    public function __construct(string $form, array $to, string $subject, string $messageBody, string $recipientName = "", string $replyTo = "", array $cc = [], array $bcc = [])
    {
        $this->to = $to;
        $this->form = $form;
        $this->recipientName = $recipientName ?? self::RECIPIENT_NAME;
        $this->replyTo = $replyTo;
        $this->subject = $subject;
        $this->messageBody = $messageBody;
        $this->cc = $cc;
        $this->bcc = $bcc;
    }

    /**
     * @param array $to
     */
    public function setTo(array $to): void
    {
        $this->to = $to;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
    }

    /**
     * @param string|null $recipientName
     */
    public function setRecipientName(?string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @param string|null $replyTo
     */
    public function setReplyTo(?string $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @param string $messageBody
     */
    public function setMessageBody(string $messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    /**
     * @param array|null $cc
     */
    public function setCc(?array $cc): void
    {
        $this->cc = $cc;
    }

    /**
     * @param array|null $bcc
     */
    public function setBcc(?array $bcc): void
    {
        $this->bcc = $bcc;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $attachmentFile = [];
        foreach ($attachments as $attachment) {
            $ext = pathinfo($attachment, PATHINFO_EXTENSION);
            if ($ext && in_array($ext, self::FILE_EXTENSION_ALLOWABLE)) {
                $attachmentFile[] = $attachment;
            }
        }

        $this->attachments = $attachmentFile;

    }

    public function sendMail()
    {
        try {
            $config = [
                'to' => $this->to,
                'from' => $this->form,
                'subject' => $this->subject,
                'messageBody' => $this->messageBody,
                'name'=>$this->recipientName
            ];

            if (!empty($this->replyTo)) {
                $config['replyTo'] = $this->replyTo;
            }
            if (!empty($this->template)) {
                $config['template'] = $this->template;
            }
            if (!empty($this->cc)) {
                $config['cc'] = $this->cc;
            }
            if (!empty($this->bcc)) {
                $config['bcc'] = $this->bcc;
            }
            if (!empty($this->attachment)) {
                $config['attachment'] = $this->attachment;
            }

            $mailSend = new SendMail($config);
            Mail::send($mailSend);

            if (Mail::failures()) {
                Log::debug('Email Send to ' . implode(', ', $this->to) . " is fail.");
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            Log::debug($e->getTraceAsString());
        }
    }
}
