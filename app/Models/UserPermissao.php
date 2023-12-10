<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermissao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_permissao';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function user() {

        return $this->belongsTo(User::class, 'id_user_created');
        
    }

    public function permissao() {
        
        return $this->belongsTo(RefPermissao::class, 'permissao_id');
        
    }

}
