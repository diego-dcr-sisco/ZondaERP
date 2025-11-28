<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Tenancy\TenantScoped;

class Question extends Model
{
    use HasFactory, TenantScoped;
    protected $table = 'question';
    protected $fillable = [
        'id',
        'question',
        'question_option_id'
    ];

    public function option() {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }
}
