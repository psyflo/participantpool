<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailing extends Model
{
    use HasFactory;

    /**
     * Mailing state constants
     */
    const CREATED = null;
    const STOPPED = 0;
    const RUNNING = 1;
    const FINISHED = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mailings';

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
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'description', 'state', 'content'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['count'];

    /**
     * Get the owner of this mailing.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the study of this mailing.
     */
    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    /**
     * Get participants of this mailing.
     */
    public function participants()
    {
        return $this->hasMany(MailingParticipant::class, 'mailing_id', 'id');
    }

    /**
     * Get state of mailing.
     */
    public function getStateAttribute($value)
    {
        return $value;
    }

    /**
     * Get count of participants.
     */
    public function getCountAttribute()
    {
        return $this->participants->count();
    }
}
