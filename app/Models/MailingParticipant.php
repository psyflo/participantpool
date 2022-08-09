<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingParticipant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mailings_participants';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mailing_id', 'participant_id', 'mail_sent'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['mail_sent' => 'datetime'];

    /**
     * Get the mailing this delivery belongs to.
     */
    public function mailing()
    {
        return $this->belongsTo(Mailing::class, 'mailing_id', 'id');
    }

    /**
     * Get the participant this delivery belongs to.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id', 'id');
    }
}
