<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'participants';

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
    protected $fillable = ['name', 'firstname', 'email'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['birthdate' => 'datetime:Y-m-d', 'last_contact' => 'datetime:Y-m-d'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age', 'studies', 'mailings', 'last_contact'];

    /**
     * Get the study participations for this participant.
     */
    public function participations()
    {
        return $this->hasMany(Participation::class, 'participant_id', 'id');
    }

    /**
     * Get the studies for this participant.
     */
    public function studies()
    {
        return $this->belongsToMany(Study::class, 'participants_studies', 'participant_id', 'study_id', 'id', 'id');
    }

    /**
     * Get the mailings for this participant.
     */
    public function mailings()
    {
        return $this->belongsToMany(Mailing::class, 'mailings_participants', 'participant_id', 'mailing_id', 'id', 'id')->withPivot('mail_sent');
    }

    /**
     * Calculate age of participant.
     */
    public function getAgeAttribute()
    {
        if ($this->birthdate !== null)
        {
            /*
             * Return years between current date and birthdate
             */
            return $this->birthdate->diff(new DateTime())->format('%y');
        }
        
        return null;
    }

    /**
     * Get studies of participant.
     */
    public function getStudiesAttribute()
    {
        if ($this->studies()->get()->count() > 0)
        {
            //return $this->studies()->get()->pluck('id');
            return $this->studies()->get()->pluck('name');
        }

        return [];
    }

    /**
     * Get mailings of participant.
     */
    public function getMailingsAttribute()
    {
        if ($this->mailings()->get()->count() > 0)
        {
            //return $this->mailings()->get()->pluck('id');
            return $this->mailings()->get()->pluck('name');
        }

        return [];
    }

    /**
     * Get last contact of participant.
     */
    public function getLastContactAttribute()
    {
        if ($this->mailings()->get()->count() > 0)
        {
            $lastContact = MailingParticipant::where('participant_id', '=', $this->id)->orderBy('mail_sent', 'desc')->first();

            if ($lastContact !== null && $lastContact->mail_sent !== null)
            {
                return $lastContact->mail_sent->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Get participant email has been verified.
     */
    public function getEmailVerifiedAttribute($value)
    {
        return $value !== null ? 'Y' : 'N';
    }
}
