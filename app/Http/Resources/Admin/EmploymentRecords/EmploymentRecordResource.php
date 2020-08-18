<?php

namespace App\Http\Resources\Admin\EmploymentRecords;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Parent_;

class EmploymentRecordResource extends JsonResource
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

        $contact = [];
        $data['resume']['cell_phone'] AND $contact[] = $data['resume']['cell_phone'];
        $data['resume']['email'] AND $contact[] = $data['resume']['email'];
        $data['resume']['line_id'] AND $contact[] = "Line: " . $data['resume']['line_id'];
        $data['resume']['skype_id'] AND $contact[] = "SKype: " . $data['resume']['skype_id'];
        $data['resume']['wechat_id'] AND $contact[] = "WeChat: " . $data['resume']['wechat_id'];
        $data['contact'] = implode("<br>", $contact);
        $jobs = [];
        foreach ($data['job_ids'] as $job_id) {
            $jobs[] = "<a lay-href='".route("job.input", ['id' => $job_id]) . "' style='color:#1E9FFF;border: 1px #ccc solid; background-color: #eee; padding: 2px 5px' lay-text='応募求人詳細' >J{$job_id}</a>&nbsp;<i lay-event='delJob-{$job_id}' title='削除' class='layui-icon layui-icon-delete'></i>";
        }
        $data['jobs'] = "<a class='layui-btn layui-btn-normal layui-btn-xs' lay-event='addJob'>+応募求人</a><br>" . implode("、&nbsp;&nbsp;&nbsp;", $jobs);
        $companies = [];
        $companyCity = config('code.resume.country_city');

        if ($this->companies) {
            foreach ($this->companies as $company) {
                $address = ($companyCity[$company->address_id] ?? '') . ' '. $company->address;
                $companies[] = "<a lay-time='5000' lay-click-tips='会社名：{$company->company_name}<br>email：{$company->email}<br>会社住所：{$address}' style='color:#1E9FFF;border: 1px #ccc solid; background-color: #eee; padding: 2px 5px' lay-text='企業詳細' >{$company->company_name}</a><i lay-event='delCompany-{$company->id}' title='削除' class='layui-icon layui-icon-delete'></i>";
            }
        }
        $data['company_text'] = "<a class='layui-btn layui-btn-normal layui-btn-xs' lay-event='addCompany'>+企業</a><br>" . implode("、&nbsp;&nbsp;&nbsp;", $companies);

        $data['created'] = date("Y-m-d\<\B\R>H:i:s", strtotime($data['created_at']));
        $data['updated'] = date("Y-m-d\<\B\R>H:i:s", strtotime($data['updated_at']));

        $comment = "<a class='layui-btn layui-btn-normal layui-btn-xs' lay-event='comment_list'>+コメント</a>";
        if ($this->comment) {
            $comment = "<a lay-event='comment_list' lay-text='コメント一覧'>{$this->comment->content}</a>";
        }
        $data['last_comment'] = $comment;
        return $data;
    }
}
