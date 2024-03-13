<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqType extends Model
{
    use HasFactory;

    protected $table = 'faq_types';

    protected $guarded = [];

    public function faqs()
    {
        return $this->hasMany(FAQ::class, 'faq_type_id', 'id');
    }
}
