<?php

namespace App\Traits;

trait UsesLocationTables
{
    protected function getTableName(string $type = 'inventory'): string
    {
        $location = session('location', 'Kentwood');
        
        return match([$location, $type]) {
            ['Kentwood', 'inventory'] => 'inventory',
            ['Kentwood', 'precount'] => 'inventory_precount',
            ['Kentwood', 'upload'] => 'inventory_upload',
            ['Kentwood', 'precount_upload'] => 'inventory_precount_upload',
            ['Kentwood', 'notag'] => 'no_tag_parts',
            ['Houston', 'inventory'] => 'inventory_houston',
            ['Houston', 'precount'] => 'inventory_precount_houston',
            ['Houston', 'notag'] => 'no_tag_parts_houston',
            default => 'inventory'
        };
    }
}
