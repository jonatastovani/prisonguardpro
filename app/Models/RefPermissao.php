<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPermissao extends Model
{
    use HasFactory;

    protected $table = "ref_permissao";

    public function config()
    {
        return $this->hasOne(RefPermissaoConfig::class, 'permissao_id');
    }

}
