<?php

namespace App\Models;

use App\Common\CommonsFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pessoa extends Model
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

    public function cidade_nasc() {
        return $this->belongsTo(RefCidade::class);
    }

    public function genero() {
        return $this->belongsTo(RefGenero::class);
    }
    
    public function escolaridade() {
        return $this->belongsTo(RefEscolaridade::class);
    }
    
    public function estado_civil() {
        return $this->belongsTo(RefEstadoCivil::class);
    }
    
    public function documentos() {
        return $this->hasMany(PessoaDocumento::class,'pessoa_id');
    }
    
}
