<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Stock extends Model
{
    protected $fillable = [
        'warehouse_id',
        'stockable_type',
        'stockable_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Add stock
     */
    public function addStock(int $quantity, string $referenceType = null, int $referenceId = null, string $notes = null): StockMovement
    {
        $before = $this->quantity;
        $this->increment('quantity', $quantity);

        return $this->movements()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'quantity_before' => $before,
            'quantity_after' => $this->quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Reduce stock
     */
    public function reduceStock(int $quantity, string $referenceType = null, int $referenceId = null, string $notes = null): ?StockMovement
    {
        if ($this->quantity < $quantity) {
            return null; // Not enough stock
        }

        $before = $this->quantity;
        $this->decrement('quantity', $quantity);

        return $this->movements()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'quantity_before' => $before,
            'quantity_after' => $this->quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Adjust stock
     */
    public function adjust(int $newQuantity, string $notes = null): StockMovement
    {
        $before = $this->quantity;
        $difference = $newQuantity - $before;

        $this->update(['quantity' => $newQuantity]);

        return $this->movements()->create([
            'type' => 'adjustment',
            'quantity' => abs($difference),
            'quantity_before' => $before,
            'quantity_after' => $newQuantity,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Get or create stock for item in warehouse
     */
    public static function getOrCreate(int $warehouseId, Model $stockable): self
    {
        return self::firstOrCreate([
            'warehouse_id' => $warehouseId,
            'stockable_type' => get_class($stockable),
            'stockable_id' => $stockable->id,
        ], [
            'quantity' => 0,
        ]);
    }
}
