<?php

namespace App\Jobs;

use App\Models\InventoryUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use League\Csv\Reader;

class ProcessInventoryImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $filePath,
        protected array $mapping
    ) {}

    public function handle(): bool
    {
        $csv = Reader::from($this->filePath, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $batch = [];

        foreach ($records as $record) {
            $mapped = $this->mapRecord($record, $this->mapping);

            $uoms = "GR,LB,FLOZ,ST";
            $uom_array = explode(',', $uoms);
            if (in_array($mapped['uom'], $uom_array)) {
                $mapped['by_weight'] = 1;
            } else {
                $mapped['by_weight'] = 0;
            }

            $batch[] = $mapped;

            // Insert in chunks of 1000
            if (count($batch) >= 1000) {
                InventoryUpload::insert($batch);
                $batch = [];
            }
        }

        // Insert remaining records
        if (!empty($batch)) {
            InventoryUpload::insert($batch);
        }
        return true;
    }

    protected function mapRecord(array $csvRecord, array $mapping): array
    {
        $mapped = [];

        foreach ($mapping as $csvColumn => $dbColumn) {
            // Skip columns mapped to null (ignored columns)
            if ($dbColumn === null) {
                continue;
            }

            // Get value from CSV, default to null if not present
            $value = $csvRecord[$csvColumn] ?? null;

            // Trim whitespace
            if (is_string($value)) {
                $value = Str::limit(trim($value),250);
                // Convert empty strings to null
                $value = $value === '' ? null : $value;
            }

            $mapped[$dbColumn] = $value;
        }

        return $mapped;
    }
}
