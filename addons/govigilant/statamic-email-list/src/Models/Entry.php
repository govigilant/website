<?php

namespace Vigilant\EmailList\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $list
 * @property string $email
 * @property array $data
 * @property bool $confirmed
 * @property bool $mail_sent
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Entry extends Model
{
    protected $table = 'email_list_entries';

    protected $guarded = [];

    protected $casts = [
        'data' => 'json',
        'confirmed' => 'bool',
        'mail_sent' => 'bool',
    ];
}
