<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role'];

    /**
     * Get role of user.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        /*
         * Return admin for superadmin
         */
        if ($this->id === 1)
        {
            return 'admin';
        }

        /*
         * For everyone else check assigned role
         */
        if (BouncerFacade::is($this)->a('admin'))
        {
            return 'admin';
        }
        else if (BouncerFacade::is($this)->a('manager'))
        {
            return 'manager';
        }
        else
        {
            return 'disabled';
        }
    }

    /**
     * Is user an administrator.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Is user a manager.
     *
     * @return boolean
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Update role of user.
     *
     * @param string $role
     * 
     * @return void
     */
    public function updateRole(string $role)
    {
        /*
         * Update manager role
         */
        if (BouncerFacade::is($this)->a('manager'))
        {
            if ($role !== 'manager')
            {
                /*
                 * Remove manager role
                 */
                BouncerFacade::retract('manager')->from($this);
            }
        }
        else
        {
            if($role === 'manager')
            {
                /*
                 * Assign manager role
                 */
                BouncerFacade::assign('manager')->to($this);
            }
        }

        /*
         * Update admin role
         */
        if (BouncerFacade::is($this)->a('admin'))
        {
            if ($role !== 'admin')
            {
                /*
                 * Remove admin role
                 */
                BouncerFacade::retract('admin')->from($this);
            }
        }
        else
        {
            if($role === 'admin')
            {
                /*
                 * Assign admin role
                 */
                BouncerFacade::assign('admin')->to($this);
            }
        }
    }
}
