<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public const COMMON_GUARDED_FIELDS_SIMPLE = ['id', 'created_at', 'updated_at'];

    public const SELF_EXCHANGE = 'mailSms';

    /** Saga Status */
    public const SAGA_STATUS_CREATE_PENDING = 1;
    public const SAGA_STATUS_UPDATE_PENDING = 2;
    public const SAGA_STATUS_DESTROY_PENDING = 3;
    public const SAGA_STATUS_COMMIT = 4;
    public const SAGA_STATUS_ROLLBACK = 5;

    /** SAGA events Publisher & Consumer */
    public const SAGA_CORE_SERVICE = 'core_service';
    public const SAGA_INSTITUTE_SERVICE = 'institute_service';
    public const SAGA_ORGANIZATION_SERVICE = 'organization_service';
    public const SAGA_YOUTH_SERVICE = 'youth_service';
    public const SAGA_CMS_SERVICE = 'cms_service';
    public const SAGA_MAIL_SMS_SERVICE = 'mail_sms_service';

    public const DATABASE_CONNECTION_ERROR_CODE = 2002;
}
