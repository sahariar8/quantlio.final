<?php

namespace App\Services;

class JsonErrorService
{
    public function replaceMissingValues($raw_data) {
        $wiped_data = str_replace(" ,", "\"\",", $raw_data);
        $wiped_data = str_replace(":,", ":\"\",", $wiped_data);
        $wiped_data = str_replace(":\n", ":\"\"\n", $wiped_data);
        $wiped_data = str_replace("\"\"\"\"", "\"\"", $wiped_data);


        return $wiped_data;
    }

    public function check($file)
    {
        $wiped_data = str_replace(": \n", ":\"\"\n", $file);
        return $wiped_data;
    }
        
    
}