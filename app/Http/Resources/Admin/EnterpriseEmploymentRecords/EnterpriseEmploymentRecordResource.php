<?php

namespace App\Http\Resources\Admin\EnterpriseEmploymentRecords;

use App\Models\EnterpriseEmploymentRecord;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterpriseEmploymentRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $memo = "<a class='layui-btn layui-btn-normal layui-btn-xs' lay-event='memo_list'>+メモ</a>";
        if ($this->memo) {
            $memo = "<a lay-event='memo_list' lay-text='メモ一覧'>{$this->memo->memo}</a>";
        }
        $data['last_memo'] = $memo;

        $comment = '';
        if ($this->comment) {
            $comment = "<a lay-event='comment_list' lay-text='コメント一覧'>{$this->comment->content}</a>";
        }
        $data['last_comment'] = $comment;

        $company_name = '';
        $fileds = '';
        if ($this->company) {
            $company_name = $this->company->company_name;
            $fileds = $this->company->fileds != 'その他' ? $this->company->fileds : $this->company->fileds_other;
        }
        $data['company_name'] = $company_name;
        $data['fileds'] = $fileds;

        $data['status_text'] = EnterpriseEmploymentRecord::STATUS_TEXT[$this->status] ?? '';

        $data['created'] = date("Y-m-d\<\B\R>H:i:s", strtotime($data['created_at']));
        $data['updated'] = date("Y-m-d\<\B\R>H:i:s", strtotime($data['updated_at']));

        return $data;
    }
}
