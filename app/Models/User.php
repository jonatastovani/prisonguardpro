<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function permissoes()
    {
        return $this->hasMany(UserPermissao::class, 'user_id');
    }

    // Inserir a ação do usuário no log de alterações do Activitylog, assim como o IP
    public function getActivityDescriptionForEvent(string $eventName): string
    {
        return "realizou a ação: $eventName";
    }

    public function getActivityCauser()
    {
        return $this;
    }

    public function getActivityProperties(string $eventName): array
    {
        return [
            'ip' => Request::ip(),
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    // /**
    //  * The name of the "usuario" column.
    //  *
    //  * @var string
    //  */
    // public function getAuthIdentifierName()
    // {
    //     return 'usuario';
    // }

    // /**
    //  * Get the password for the user.
    //  *
    //  * @var string
    //  */
    // public function getAuthPassword()
    // {
    //     return $this->senha;
    // }

}
