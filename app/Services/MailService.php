<?php


namespace App\Services;

use App\Helpers\Classes\FileHandler;
use App\Mail\SendMail;
use App\Models\MailLog;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 *
 */
class MailService
{
    public const RECIPIENT_NAME = 'Nise';
    public const CACHE_KEY_TEMPORARY_ATTACHMENT_FILEPATH = "CACHE_KEY_TEMPORARY_ATTACHMENT_FILEPATH";
    public const TEMPORARY_ATTACHMENT_FILEPATH = "email-temporary-file";

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

    public function __construct()
    {

    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function sendMail(array $mailData): bool
    {
        $config = [
            'to' => $mailData['to'],
            'from' => $mailData['from'],
            'subject' => $mailData['subject'],
            'messageBody' => $mailData['message_body'],
            'name' => !empty($mailData['recipient_name']) ? $mailData['recipient_name'] : self::RECIPIENT_NAME
        ];

        if (!empty($mailData['reply_to'])) {
            $config['replyTo'] = $mailData['reply_to'];
        }
        if (!empty($mailData['cc'])) {
            $config['cc'] = $mailData['cc'];
        }
        if (!empty($mailData['bcc'])) {
            $config['bcc'] = $mailData['bcc'];
        }
        if (!empty($mailData['attachment'])) {
            /** Store all attachment to the temporary storage before mail  send */
            $config['attachment'] = FileHandler::storeFileContent($mailData['attachment'], self::TEMPORARY_ATTACHMENT_FILEPATH);
            Cache::put(self::CACHE_KEY_TEMPORARY_ATTACHMENT_FILEPATH, $config['attachment']);
        }

        $mailSend = new SendMail($config);

        Mail::send($mailSend);
        throw_if((bool)count(Mail::failures()), Exception::class, 'Email Send to ' . implode(', ', $mailData['to']) . " is fail.");

        $mailLog = app(MailLog::class);
        $mailLog->mail_log = $config;
        $mailLog->save();

        /** Delete all attachment from the storage after mail successfully send */
        if (!empty($mailData['attachment'])) {
            FileHandler::deleteFile(Cache::get(self::CACHE_KEY_TEMPORARY_ATTACHMENT_FILEPATH));
        }

        return true;

    }
}
