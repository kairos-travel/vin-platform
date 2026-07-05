<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function anonymize(): void
    {
        DB::transaction(function () {
            $this->update([
                'first_name' => 'Удалён',
                'last_name' => null,
                'phone' => null
            ]);

            if ($user = $this->user) {
                $user->update([
                    'name' => '',
                    'email' => "deleted-{$user->id}-" . Str::uuid() . '@anonymized.invalid',
                    'password' => Str::password(32),
                ]);

                $user->delete();
            }

            $this->delete();
        });
    }
}
