<?php

namespace App\Repositories;

class BaseRepository implements BaseInterface
{

    public function saveObject(array $data, string $pkey)
    {
        //print_r($data);
        if (empty($data[$pkey])) {
            foreach ($data as $key => $value) {
                $this->resource->{$key} = $value;
            }
            $result = $this->resource->save();
            $id = !$result ? false : $this->resource->$pkey;
        } else {
            $result = $this->resource->where($pkey, $data[$pkey])->update($data);
            $id = !$result ? false : $data[$pkey];
        }
        return $id;
    }

    public function insertObject(array $data)
    {
        return $this->resource->insert($data);
    }
}
