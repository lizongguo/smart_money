<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class FoundAudit extends BaseModel
{
    protected $table = 'found_audit';
    protected $primaryKey = 'id';

    public function getOne($id)
    {
        $found = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$found) {
            return false;
        }
        return $found;
    }

    public function saveFinancial($data)
    {
        if ($data['content_type']==1) {
            $data['content'] = '';
        } else {
            $data['path'] = '';
        }
        if ($data['id']) {
            $id = $data['id'];
            $update = [
                'fund_id' => $data['fund_id'],
                'name' => $data['name'],
                'path' => $data['path'],
                'content_type' => $data['content_type'],
                'content' => $data['content'],
            ];
            return $this->where('id', $id)->update($update);
        } else {
            $insert = [
                'fund_id' => $data['fund_id'],
                'name' => $data['name'],
                'path' => $data['path'],
                'content_type' => $data['content_type'],
                'content' => $data['content'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->insert($insert);
        }
    }
}
