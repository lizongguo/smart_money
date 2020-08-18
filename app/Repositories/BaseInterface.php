<?php
namespace App\Repositories;

interface BaseInterface
{
    public function saveObject(array $data, string $key);
    public function insertObject(array $data);
}
