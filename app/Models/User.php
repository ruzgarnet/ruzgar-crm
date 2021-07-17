<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Staff relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Role relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check this role has ability
     *
     * @param string $key
     * @return boolean
     */
    public function permission(string $key)
    {
        $key = str_replace(
            ['admin.', '.put', '.post'],
            ['', '', ''],
            $key
        );

        return (Ability::where('key', $key)->count() < 1 || $this->role->abilities->where('key', $key)->count() > 0) ? true : false;
    }

    /**
     * Check user has group in roles
     *
     * @param array $group
     * @return boolean
     */
    public function permissionGroup(array $routes)
    {
        $can = 0;
        foreach ($routes as $route) {
            if ($this->permission($route)) {
                $can++;
            }
        }
        return (bool)$can;
    }
}
