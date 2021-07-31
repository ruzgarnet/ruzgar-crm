<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    /**
     * All fields fillable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Ability Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }

    /**
     * User Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Attach abilities
     *
     * @param array|string $abilities
     * @return void
     */
    public function attachAbilities($abilities)
    {
        if (!is_array($abilities)) {
            $abilities = [$abilities];
        }

        if ($this->abilities()->count() > 0) {
            DB::table('ability_role')->where('role_id', $this->id)->delete();
        }

        foreach ($abilities as $ability) {
            if ($ability = Ability::where('key', $ability)->first()) {
                DB::table('ability_role')->insert([
                    'role_id' => $this->id,
                    'ability_id' => $ability->id
                ]);
            }
        }
    }
}
