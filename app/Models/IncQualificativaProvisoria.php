<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IncQualificativaProvisoria extends Model
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
        $logOptions = new LogOptions();
        return $logOptions->logAll()
            ->dontSubmitEmptyLogs()
            ->useLogName(strtolower(class_basename($this)));
    }

    protected $fillable = ['mae', 'pai'];

    public static function boot()
    {
        parent::boot();

        // Registrando o evento saving
        static::saving(function ($model) {
            // Convertendo os valores dos campos para maiúsculas
            if ($model->mae) {
                $model->mae = mb_strtoupper($model->mae, 'UTF-8');
            }
            if ($model->pai) {
                $model->pai = mb_strtoupper($model->pai, 'UTF-8');
            }
        });
    }

    public function passagem()
    {
        return $this->belongsTo(IncEntradaPreso::class);
    }
    public function cidade_nasc()
    {
        return $this->belongsTo(RefCidade::class)->withDefault(false);
    }
    public function genero()
    {
        return $this->belongsTo(RefGenero::class)->withDefault(false);
    }
    public function escolaridade()
    {
        return $this->belongsTo(RefEscolaridade::class)->withDefault(false);
    }
    public function estado_civil()
    {
        return $this->belongsTo(RefEstadoCivil::class)->withDefault(false);
    }
    public function cutis()
    {
        return $this->belongsTo(RefCutis::class)->withDefault(false);
    }
    public function cabelo_tipo()
    {
        return $this->belongsTo(RefCabeloTipo::class)->withDefault(false);
    }
    public function cabelo_cor()
    {
        return $this->belongsTo(RefCabeloCor::class)->withDefault(false);
    }
    public function olho_cor()
    {
        return $this->belongsTo(RefCabeloCor::class)->withDefault(false);
    }
    public function olho_tipo()
    {
        return $this->belongsTo(RefOlhoTipo::class)->withDefault(false);
    }
    public function crenca()
    {
        return $this->belongsTo(RefCrenca::class)->withDefault(false);
    }
}
