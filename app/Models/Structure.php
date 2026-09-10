<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Structure extends Model
{
    use BelongsToClient, SoftDeletes;

    protected $fillable = [
        'client_id',
        'parent_id',
        'structure_type_id',
        'location_id',
        'name',
        'second_node_name',
        'code',
        'max_occupancy',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_active' => 'boolean',
            'max_occupancy' => 'integer',
        ];
    }

    public function structureType(): BelongsTo
    {
        return $this->belongsTo(StructureType::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(StructureMember::class);
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(StructurePet::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function authorizations(): HasMany
    {
        return $this->hasMany(VisitorPreAuthorization::class);
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(AccessLog::class);
    }

    public function correspondence(): HasMany
    {
        return $this->hasMany(Correspondence::class);
    }

    public function getFullPathAttribute(): string
    {
        // Si tiene second_node_name, mostrar "Padre - Subnodo"
        if ($this->second_node_name !== null && $this->parent !== null) {
            return $this->parent->name . ' - ' . $this->second_node_name;
        }

        // Si es nodo raíz, mostrar solo su nombre
        $parts = [$this->name];
        $node = $this->parent;

        while ($node !== null) {
            array_unshift($parts, $node->name);
            $node = $node->parent;
        }

        return implode(' › ', $parts);
    }
}
