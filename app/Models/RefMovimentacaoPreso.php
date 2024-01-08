<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefMovimentacaoPreso extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'ref_movimentacao_preso';
        
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
        return LogOptions::defaultsTo();
    }

    public function tipo() {
        
        return $this->hasOne(RefMovimentacaoPresoTipo::class, 'tipo_id');
        
    }

    public function motivo() {
        
        return $this->hasOne(RefMovimentacaoPresoMotivo::class, 'motivo_id');
        
    }

}
