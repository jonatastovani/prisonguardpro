<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use App\Common\FuncoesPresos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Preso extends Model
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

    public function getMatriculaAttribute($value)
    {
        return FuncoesPresos::retornaMatriculaFormatada($value);
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        return $logOptions->logAll()
            ->dontSubmitEmptyLogs()
            ->useLogName(strtolower(class_basename($this)));
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class,'pessoa_id');
    }

    public function cutis()
    {
        return $this->belongsTo(RefCutis::class,'cutis_id');
    }

    public function cabelo_tipo()
    {
        return $this->belongsTo(RefCabeloTipo::class,'cabelo_tipo_id');
    }

    public function cabelo_cor()
    {
        return $this->belongsTo(RefCabeloCor::class,'cabelo_cor_id');
    }

    public function olho_tipo()
    {
        return $this->belongsTo(RefOlhoTipo::class,'olho_tipo_id');
    }

    public function olho_cor()
    {
        return $this->belongsTo(RefOlhoCor::class,'olho_cor_id');
    }

    public function crenca()
    {
        return $this->belongsTo(RefCrenca::class,'crenca_id');
    }

}
