<?php


namespace App\Service\ExportData;


interface DataExportInterface
{
    public function export(): string;

    public function nameFile(): string;
}
