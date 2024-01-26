<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IncEntradaPreso extends Model
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

    protected $fillable = ['nome','nome_social','matricula','rg','mae'];

    public static function boot()
    {
        parent::boot();

        // Registrando o evento saving
        static::saving(function ($model) {
            // Convertendo o valor do campo 'nome' para maiúsculas
            $model->nome = strtoupper($model->nome);
            $model->nome_social = strtoupper($model->nome_social);
            $model->matricula = strtoupper($model->matricula);
            $model->rg = strtoupper($model->rg);
            $model->mae = strtoupper($model->mae);
        });
    }

    public function preso()
    {
        return $this->belongsTo(Preso::class)->withDefault(false);
    }

    public function entrada()
    {
        return $this->belongsTo(IncEntrada::class);
    }

    public function status()
    {
        return $this->belongsTo(RefStatus::class);
    }
}
