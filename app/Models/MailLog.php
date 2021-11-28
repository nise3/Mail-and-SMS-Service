<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;

/**
 * Class MailLog
 * @property int id
 * @property json mail_log
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class MailLog extends Model
{
    protected $casts = [
        'mail_log' => 'array',
    ];
}
