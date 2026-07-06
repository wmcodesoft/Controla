<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['visitor_id', 'user_id', 'resident_id', 'plate', 'brand', 'model', 'color', 'type', 'photo_path'];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
}
