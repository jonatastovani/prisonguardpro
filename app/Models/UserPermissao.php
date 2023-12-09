<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermissao extends Model
{
    use HasFactory;

    protected $table = 'user_permissao';

    public function user() {

        return $this->belongsTo(User::class, 'id_user_created');
        
    }

    public function permissao() {
        
        return $this->belongsTo(RefPermissao::class, 'permissao_id');
        
    }

}
