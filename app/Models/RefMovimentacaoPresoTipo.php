<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use Database\Seeders\RefTurnoTipoPermissaoSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefMovimentacaoPresoTipo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    public function getCreatedAtAttribute($value)
    {
        return CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo($value);
    }

    public function getDeletedAtAttribute($value)
    {
        return CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo($value);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function motivosTransito()
    {
        return $this->hasOne(RefMovimentacaoPresoTipoTransitoConfig::class, 'tipo_mov_id');
    }

}
