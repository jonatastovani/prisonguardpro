<?php

namespace App\Models;


use App\Common\CommonsFunctions;
use App\Common\FuncoesPresos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PresoDocumentoProvisorio extends Model
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
    
    protected $fillable = ['numero', 'digito'];

    public static function boot()
    {
        parent::boot();

        // Registrando o evento saving
        static::saving(function ($model) {
            $model->numero = mb_strtoupper($model->numero, 'UTF-8');
            if ($model->digito) {
                $model->digito = mb_strtoupper($model->digito, 'UTF-8');
            }
        });
    }

    public function documento()
    {
        return $this->belongsTo(RefDocumentoConfig::class);
    }

}

