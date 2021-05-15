<?php


namespace App\Service\Export;


interface ExportDataInterface
{
    public function export(): string;

    public function nameFile(): string;
}
