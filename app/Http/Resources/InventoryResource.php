<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CurrencyHelper;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tag' => $this->tag,
            'tag_printed' => (bool) $this->tag_printed,
            'part' => $this->part,
            'bin' => $this->bin,
            'warehouse' => $this->warehouse,
            'lot_number' => $this->lot_number,
            'serial_number' => $this->serial_number,
            'count' => (float) $this->count,
            'by_weight' => (bool) $this->by_weight,
            'uom' => $this->uom,
            'expected_qty' => (float) $this->expected_qty,
            'standard_cost' => $this->when(
                $request->user()?->user_type === 2,
                (float) $this->standard_cost
            ),
            'cost_counted' => $this->when(
                $request->user()?->user_type === 2,
                (float) $this->cost_counted
            ),
            'cost_expected' => $this->when(
                $request->user()?->user_type === 2,
                (float) $this->cost_expected
            ),
            'plus_minus' => $this->when(
                $request->user()?->user_type === 2,
                (float) $this->plus_minus
            ),
            'top_eighty' => (bool) $this->top_eighty,
            'counted' => (bool) $this->counted,
            'date_counted' => $this->date_counted?->format('Y-m-d'),
            'time_counted' => $this->time_counted?->format('H:i:s'),
            'note' => $this->note,
            'user' => $this->when($this->user, function() {
                return [
                    'id' => $this->counter?->id,
                    'name' => $this->counter ? 
                        $this->counter->first_name . ' ' . $this->counter->last_name : null,
                    'initials' => $this->counter?->initials,
                ];
            }),
        ];
    }
}
