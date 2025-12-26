<?php

namespace App\Jobs;

use App\Models\InventoryUploadPrecount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use League\Csv\Reader;

class ProcessPrecountInventoryImport implements ShouldQueue
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

            // Add timestamps
            $mapped['created_at'] = now();
            $mapped['updated_at'] = now();

            $batch[] = $mapped;

            // Insert in chunks of 1000
            if (count($batch) >= 1000) {
                InventoryUploadPrecount::insert($batch);
                $batch = [];
            }
        }

        // Insert remaining records
        if (!empty($batch)) {
            InventoryUploadPrecount::insert($batch);
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
