<?php

declare(strict_types=1);

class ReporRepository 
{
    private mysqli $db;


    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function addReport(Report $report)
    {
        
    }

}