<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name', 'address', 'phone', 'type', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    public function preAuthorizations()
    {
        return $this->hasMany(PreAuthorization::class);
    }

    public function guardLogs()
    {
        return $this->hasMany(GuardLog::class);
    }

    public function correspondence()
    {
        return $this->hasMany(Correspondence::class);
    }
}
