<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // relación de 1:n para categorias y establecimientos
    public function establecimientos()
    {
        return $this->HasMany(Establecimiento::class);
    }
}
