<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Services\Tcpdf\TcpdfService;
use Illuminate\Support\Facades\Storage;

class Experience extends BaseModel
{
    use SoftDeletes;
    public $isDeleted = 0;
    protected $table = 'experiences';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'video_url',
        'name',
        'name_kana',
        'birthday',
        'sex',
        'nationality_id',
        'nationality',
        'address',
        'address_id',
        'address_other',
        'address_kana',
        'postal_code',
        'cell_phone',
        'emergency_contact',
        'emergency_address_id',
        'emergency_address_other',
        'emergency_address_kana',
        'emergency_postal_code',
        'emergency_cell_phone',
        'nearest_station',
        'email',
        'residence_in_japan_year',
        'residence_in_japan_month',
        'photo',
        'visa_type',
        'visa_other',
        'visa_term',
        'academic_background',
        'is_qualification_and_license',
        'qualification_and_license',
        'language_skill',
        'it_skill_os',
        'it_skill_office',
        'it_skill_graphic',
        'it_skill_language',
        'it_skill_db',
        'it_skill_framework',
        'commuting_hours',
        'family_members_num',
        'is_spouse',
        'is_spouse_support',
        'desired_place',
        'desired_place_ids',
        'pr_other',
        'other_expected_types',
        'is_experience',
        'job_summary',
        'experiences',
        'pdf_url',
        'video_url',
        'jp_level', //日本语
        'en_level', //英语
    ];

    public function getAcademicBackgroundAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setAcademicBackgroundAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['academic_background'] = $setter;
    }

    public function getQualificationAndLicenseAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setQualificationAndLicenseAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['qualification_and_license'] = $setter;
    }

    public function getLanguageSkillAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setLanguageSkillAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['language_skill'] = $setter;
    }

    public function getItSkillOsAttribute($value)
    {
        if (!empty($value)) {
            $temperatures = explode(',', $value);
            return $temperatures;
        } else {
            return [];
        }
    }

    public function setItSkillOsAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $temperature = implode(',', $value);
        } elseif (is_string($value)) {
            $temperature = $value;
        } else {
            $temperature = '';
        }
        $this->attributes['it_skill_os'] = $temperature;
    }

    public function getItSkillOfficeAttribute($value)
    {
        if (!empty($value)) {
            $temperatures = explode(',', $value);
            return $temperatures;
        } else {
            return [];
        }
    }

    public function setItSkillOfficeAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $temperature = implode(',', $value);
        } elseif (is_string($value)) {
            $temperature = $value;
        } else {
            $temperature = '';
        }
        $this->attributes['it_skill_office'] = $temperature;
    }


    public function getItSkillGraphicAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setItSkillGraphicAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['it_skill_graphic'] = $setter;
    }

    public function getItSkillDbAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setItSkillDbAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['it_skill_db'] = $setter;
    }

    public function getItSkillLanguageAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setItSkillLanguageAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['it_skill_language'] = $setter;
    }

    public function getItSkillFrameworkAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setItSkillFrameworkAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['it_skill_framework'] = $setter;
    }

    public function getDesiredPlaceAttribute($value)
    {
        if (!empty($value)) {
            $temperatures = explode(',', $value);
            return $temperatures;
        } else {
            return [];
        }
    }

    public function setDesiredPlaceAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $temperature = implode(',', $value);
        } elseif (is_string($value)) {
            $temperature = $value;
        } else {
            $temperature = '';
        }
        $this->attributes['desired_place'] = $temperature;
    }

    public function getExperiencesAttribute($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setExperiencesAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = json_encode($value);
        } else {
            $setter = '';
        }
        $this->attributes['experiences'] = $setter;
    }

    /**
     * 获取指定用户的详细简历
     * @param $userId
     * @return mixed
     */
    public static function getExperienceByUserId($userId)
    {
        $experience = self::where('user_id', (int)$userId)->first();
        return $experience;
    }

    public static function array2string(&$data)
    {
        if (is_array($data)) {
            foreach ($data as &$value) {
                if (!is_array($value)) {
                    $value = (string)$value;
                } else {
                    self::array2string($value);
                }
            }
        } else {
            $data = (string)$data;
        }
    }

    public function downloadPdf()
    {
        if ($this->id < 1) {
            return false;
        }

        $resumeInfo = config('code.resume');
        $companyInfo = config('code.company');
        try {
            $pdfObj = TcpdfService::getService();
            $updateDate = date('Y年m月d日', strtotime($this->updated_at));
            $title = "履歴書・職務経歴書 / 最終更新日：{$updateDate}現在";
            $pdfObj->setHeader($title);

            $logoImage = $pdfObj->checkimg('/images/logo.png');
            $photoImage = $pdfObj->checkimg($this->photo ? $this->photo : '/images/no_photo.png');
            $brithday = strtotime($this->birthday);
            if ($brithday > 0) {
                $brithday = date('Y年n月j日生', $brithday);
            } else {
                $brithday = '不明';
            }

            $sample = Resume::where('user_id', $this->user_id)->orderBy('resume_id', 'desc')->first();

            $employmentStr = '無';
            $lastEducation = '無';
            if ($sample) {
                //就業状況
                if ($sample->employment_status == 1) {
                    $employmentStr = "在学中&nbsp;&nbsp;[卒業見込み{$sample->employment_status_extra}年]";
                }else{
                    if ($sample->employment_status_extra == -1) {
                        $address_extra = "1年未満";
                    } elseif ($sample->employment_status_extra > 10) {
                        $address_extra = "10年以上";
                    } else {
                        $address_extra = $sample->employment_status_extra . "年";
                    }
                    $employmentStr = "就業中&nbsp;&nbsp;[仕事経験{$address_extra}]";
                }
                //最終学歴 早稲田大学大学院 情報工学専攻（理系）/ 2020年3月卒業見込
                $lastEducation = "{$sample->university}". ($sample->final_education > 0 ? '（' . $resumeInfo['final_education'][$sample->final_education].'）' : "")."&nbsp;{$sample->major}";
                if ($sample->science_arts > 0) {
                    $lastEducation .= "（{$resumeInfo['science_arts'][$sample->science_arts]}）";
                }
            }


            $html = <<<EOF
<h1 style="font-size: 10px; line-height: 25px;border-bottom: #eee 1px solid; padding: 10px 10px; font-weight: normal;">
				<b style="color: #000;font-size: 14px;">履歴書・職務経歴書</b> / 最終更新日：{$updateDate}現在
            </h1>
            <table style="padding-top:20px">
				<tr>
					<td style="width: 25%;">
						<img style="width: 160px; border: #ddd 1px solid;" src="{$photoImage}" />
					</td>
					<td style="width: 75%;text-align: left;">
                        <h4 style="font-weight: normal; margin-bottom: 25px;">
                            <b style="font-size: 16px; color: #000;font-weight: normal">{$this->name}</b>（{$this->name_kana}）/ {$this->sex}性 / {$this->age}({$brithday})
                        </h4>
                        <p style="padding: 8px 0; color: #888;">
                            国籍：<font style="color: #000;font-weight: normal" >{$this->nationality_str}</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            現住所：<font style="color: #000;font-weight: normal" >{$this->address_str}</font>
                        </p>
                        <p style="padding: 8px 0; color: #888;">
                            就業状況：<font style="color: #000;font-weight: normal" >{$employmentStr}</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            日本滞在年数：<font style="color: #000;font-weight: normal" >{$this->japan_year}</font>
                        </p>
                        <p style="padding: 8px 0; color: #888;">
                            最終学歴：<font style="color: #000;font-weight: normal" >{$lastEducation}</font>
                        </p>
                        <p style="padding: 8px 0; color: #888;">
                            配偶者：<font style="color: #000;font-weight: normal" >{$this->is_spouse_str}</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            希望勤務地：<font style="color: #000;font-weight: normal" >{$this->desired_places}</font>
                        </p>
					</td>
				</tr>
			</table>
			<br/><br/>
EOF;
            $pdfObj->writeHTMLCell(0, 0, 160, 10, '<img style="height: 35px;" src="' . $logoImage . '"/>');
            $pdfObj->writeHTML($html);

            $post = '<p style="padding: 0 0;line-height:25px;color: #000;font-weight: bold;">';
            if ($this->address == 1) {
                $post .= '〒' . $this->postal_code . '<br/>';
                $post .= $this->address_str . '<br/>';
            }
            $content = $post . $this->address_other . '<br>' . $this->address_kana . '</p>';

            $pdfObj->addItemTable('現住所', $content, 25);

            $content = '<p style="padding: 0 0;line-height:25px;color: #000;font-weight: bold;">〒' . $this->emergency_postal_code . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;電話：' . $this->emergency_cell_phone
                . '<br/>' . $this->emergency_contact_address
                . '<br/>' . $this->emergency_address_other
                . '<br/>' . $this->emergency_address_kana . '</p>';
            if ($this->emergency_contact) {
                $content = '<p style="padding: 0 0;line-height:16px;color: #000;font-weight: bold;">同上</p>';
            }
            $pdfObj->addItemTable('緊急連絡先', $content, 25);
            $pdfObj->addItemTable('最寄駅',
                '<p style="padding: 0 0;line-height:16px;color: #000;font-weight: bold;">' . $this->nearest_station . '</p>', 25);
            $pdfObj->addItemTable('連絡先',
                '<p style="padding: 0 0;line-height:25px;color: #000;font-weight: bold;">電話：' . $this->cell_phone . '<br/>E-Mail：' . $this->email . '</p>', 25);
            $pdfObj->addItemTable('在留カード',
                '<p style="padding: 0 0;line-height:25px;color: #000;font-weight: bold;">ビザ種類：' . $this->visa_type_str . '<br/>ビザ有効期限：' . $this->visa_term . '</p>',
                25);


            //学歴
            if (count($this->academic_background) > 0) {
                $pdfObj->addSubTitle('学歴');
                foreach ($this->academic_background as $item) {
                    $title = date('Y年m月', strtotime($item['enrol_date_start'])) . '〜' . date('Y年m月',
                            strtotime($item['enrol_date_end'])); // . '(見込)';
                    $content = "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">［{$item['country']}］{$item['school_name']}（{$resumeInfo['final_education'][$item['final_education']]}） {$item['department_name']} {$item['subject_name']}</p>";
                    $pdfObj->addItemTable($title, $content, 30);
                }
            }

            //職歴
            if ($this->is_experience) {
                $pdfObj->addSubTitle('職歴');
                foreach ($this->experiences as $item) {
                    $title = date('Y年m月', strtotime($item['start_date'])) . '〜' . ($item['is_now'] ? "現在" : date('Y年m月',
                            strtotime($item['end_date'])));
                    $content = "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">［{$item['country']}］{$item['corporate_name']} {$item['post_name']} （{$item['employ_type']}）</p>";
                    $pdfObj->addItemTable($title, $content, 30);
                }
            }


            //資格・免許
            if ($this->is_qualification_and_license) {
                $pdfObj->addSubTitle('資格・免許');
                foreach ($this->qualification_and_license as $item) {
                    if (!empty($item['name']) && isset($resumeInfo['license_names'][$item['name']])) {
                        $title = date('Y年m月', strtotime($item['certificate_date']));
                        $name = $resumeInfo['license_names'][$item['name']] != 'その他' ? $resumeInfo['license_names'][$item['name']] . "&nbsp;&nbsp;" . ($item['name'] == 1 ? $resumeInfo['license_jlpt'][$item['level']] : $item['point'] . '点') : $item['certificate_other'];
                        $content = "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$name}</p>";
                        $pdfObj->addItemTable($title, $content, 30);
                    }
                }
            }

            $pdfObj->addSubTitle('語学スキル');
            $pdfObj->addItemTable('日本語',
                "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$resumeInfo['jp_level'][$this->jp_level]}</p>",
                30);
            $pdfObj->addItemTable('英語',
                "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$resumeInfo['en_level'][$this->en_level]}</p>",
                30);
            foreach ($this->language_skill as $item) {
                $title = $item['other_text'];
                if (empty($title)) {
                    continue;
                }

                $content = "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$resumeInfo['other_level'][$item['other_level']]}</p>";
                $pdfObj->addItemTable($title, $content, 30);
            }

            $pdfObj->addSubTitle('PC/ITスキル');

            if (!empty($this->skill_os)) {
                $pdfObj->addItemTable('OS',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$this->skill_os}</p>", 30);
            }
            if (!empty($this->skill_office)) {
                $pdfObj->addItemTable('Office',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\"><b>{$this->skill_office}</b></p>",
                    30);
            }

            $names = [];
            foreach ($this->it_skill_graphic as $item) {
                if (empty($item['name'])) {
                    continue;
                }
                $year = '';
                if ($item['year'] && $item['year'] != '1') {
                    $year = $resumeInfo['residence_in_japan_year'][$item['year']];
                }
                if ($item['month'] && $item['month'] != '1') {
                    $year .= $resumeInfo['residence_in_japan_month'][$item['month']];
                }

                $names[] = ($item['name'] != 'その他' ? $item['name'] : $item['other']) . "（{$year}）";
            }
            if (count($names)){
                $pdfObj->addItemTable('デザイン(2D/3D)',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\"><b>" . implode("&nbsp;、&nbsp;",
                        $names) . "</b></p>", 30);
            }


            $names = [];
            foreach ($this->it_skill_language as $item) {
                if (empty($item['name'])) {
                    continue;
                }
                $year = '';
                if ($item['year'] && $item['year'] != '1') {
                    $year = $resumeInfo['residence_in_japan_year'][$item['year']];
                }
                if ($item['month'] && $item['month'] != '1') {
                    $year .= $resumeInfo['residence_in_japan_month'][$item['month']];
                }

                $names[] = ($item['name'] != 'その他' ? $item['name'] : $item['other']) . "（{$year}）";
            }
            if (count($names)) {
                $pdfObj->addItemTable('開発言語',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . implode("&nbsp;、&nbsp;",
                        $names) . "</p>", 30);
            }


            $names = [];
            foreach ($this->it_skill_db as $item) {
                if (empty($item['name'])) {
                    continue;
                }
                $year = '';
                if ($item['year'] && $item['year'] != '1') {
                    $year = $resumeInfo['residence_in_japan_year'][$item['year']];
                }
                if ($item['month'] && $item['month'] != '1') {
                    $year .= $resumeInfo['residence_in_japan_month'][$item['month']];
                }

                $names[] = ($item['name'] != 'その他' ? $item['name'] : $item['other']) . "（{$year}）";
            }
            if (count($names)) {
                $pdfObj->addItemTable('DB',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . implode("&nbsp;、&nbsp;",
                        $names) . "</p>", 30);
            }

            $names = [];
            foreach ($this->it_skill_framework as $item) {
                if (empty($item['name'])) {
                    continue;
                }
                $year = '';
                if ($item['year'] && $item['year'] != '1') {
                    $year = $resumeInfo['residence_in_japan_year'][$item['year']];
                }
                if ($item['month'] && $item['month'] != '1') {
                    $year .= $resumeInfo['residence_in_japan_month'][$item['month']];
                }

                $names[] = ($item['name'] != 'その他' ? $item['name'] : $item['other']) . "（{$year}）";
            }
            if (count($names)) {
                $pdfObj->addItemTable('フレームワーク',
                    "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . implode("&nbsp;、&nbsp;",
                        $names) . "</p>", 30);
            }


            $pdfObj->addSubTitle('志望の動機、自己PR、趣味、特技など', 5, 3);
            $pdfObj->addContnet($this->pr_other);

            $pdfObj->addSubTitle('本人希望記入欄（特に待遇・職種・勤務時間・その他についての希望などがあれば記入）', 5, 3);
            $pdfObj->addContnet($this->other_expected_types);


            if ($this->is_experience) {
                if (!empty($this->job_summary)) {
                    $pdfObj->addSubTitle('職務要約', 5, 3);
                    $pdfObj->addContnet($this->job_summary);
                }

                $nums = count($this->experiences);
                $pdfObj->addSubTitle('職務経歴詳細［' . $nums . '社］', 5, 3);
                foreach ($this->experiences as $key => $item) {
                    $endTime = $item['is_now'] ? time() : strtotime($item['end_date']);
                    $startTime = strtotime($item['start_date']);
                    $title = date('Y年m月', $startTime) . '〜' . ($item['is_now'] ? "现在" : date('Y年m月', $endTime));
                    $months = (($endTime - $startTime) / 86400) / 30;
                    $yearStr = '';
                    $year = floor($months / 12);
                    if ($year >= 1) {
                        $yearStr .= $year . "年";
                        $months = $months % 12;
                    }
                    if ($months > 0) {
                        $yearStr .= $months . "ヶ月";
                    }
                    if (!empty($yearStr)) {
                        $yearStr = "（{$yearStr}）";
                    }
                    $pdfObj->addItemTable('期間',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$title}{$yearStr}</p>", 30);
                    $pdfObj->addItemTable('会社名', "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">
［{$item['country']}］{$item['corporate_name']}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;従業員数：" . ($companyInfo['member_total'][$item['employees_num']] ?? '') ."</p>", 30);
                    $pdfObj->addItemTable('雇用形態',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$item['employ_type']}</p>", 30);
                    $pdfObj->addItemTable('部署/役職',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">{$item['post_name']}</p>", 30);
                    $pdfObj->addItemTable('業種',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . ($item['industry_name'] != 'その他' ? $item['industry_name'] : $item['industry_other']) . "</p>",
                        30);
                    $pdfObj->addItemTable('職種',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . ($item['occupation'] != 'その他' ? $item['occupation'] : $item['occupation_other']) . "</p>",
                        30);
                    $pdfObj->addItemTable('年収',
                        "<p style=\"padding: 0 0;line-height:16px;color: #000;font-weight:bold;\">" . ($item['annual_income']) . "</p>",
                        30);
                    $pdfObj->addItemTable('担当業務',
                        "<p style=\"padding: 0 0;line-height:18px;color: #000;font-weight:bold;\">" . (nl2br($item['undertake_business'])) . "</p>",
                        30);
                    ($nums > $key + 1) and $pdfObj->Ln(8);
                }
            }


            $pdfUrl = $this->savePdf($pdfObj);
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            return false;
        }
        return $pdfUrl;
    }

    /**
     * 保存pdf并返回pdf路径
     * @return string
     */
    protected function savePdf($pdfObj)
    {
        $saveTo = DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . str_pad($this->id,
                6, 0, 0) . DIRECTORY_SEPARATOR;
        Storage::disk('public_path')->makeDirectory($saveTo);
        $pdfUrl = $saveTo . date('YmdHis') . ".pdf";
        $pdfName = public_path() . $pdfUrl;
//        $pdfObj->Output($pdfName, 'I');
        $pdfObj->Output($pdfName, 'F');
        self::where('id', $this->id)->update(['pdf_url' => $pdfUrl]);
        return $pdfUrl;
    }

    public static function dealItemData($v)
    {
        $resumeInfo = config("code.resume");
        $v->sex = $resumeInfo['sex'][$v['sex']] ?? '';
        if ($v['birthday'] && $v['birthday'] != "0000-00-00") {
            $age_str = floor((time() - strtotime($v['birthday'])) / (3600 * 24 * 365)) . "歳";
        } else {
            $age_str = "不明";
        }
        $v->age = $age_str;
        $v->nationality_str = $resumeInfo['nationality'][$v['nationality_id']] != "その他" ? $resumeInfo['nationality'][$v['nationality_id']] : $v['nationality'];
        $v->address_str = $resumeInfo['address2'][$v['address']] . ($v['address'] == 1 && $v['address_id'] ? "[" . ($resumeInfo['country_city'][$v['address_id']]) . "]" : "");
        $v->visa_type_str = $resumeInfo['visa_type'][$v['visa_type']] == "その他" ? $v['visa_other'] : $resumeInfo['visa_type'][$v['visa_type']];

        //緊急連絡先
        $address_extra = '';
        if ($v['emergency_contact'] == 1) {
            $address_extra = "同上";
        } else {
            $address_extra = $resumeInfo['address2'][1] . ($v['emergency_address_id'] ? "[" . ($resumeInfo['country_city'][$v['emergency_address_id']]) . "]" : "");;
        }
        $v->emergency_contact_address = $address_extra;

        //residence_in_japan_year
        $v->japan_year = $resumeInfo['residence_in_japan_year'][$v['residence_in_japan_year']];
        if ($v['residence_in_japan_year'] > 1 && $v['residence_in_japan_month'] > 1) {
            $v->japan_year .= "-" . $resumeInfo['residence_in_japan_month'][$v['residence_in_japan_month']];
        }

        $v->have_license = $resumeInfo['have_license'][$v['is_qualification_and_license']];

        $v->jp_level_str = $resumeInfo['jp_level'][$v['jp_level']];
        $v->en_level_str = $resumeInfo['en_level'][$v['en_level']];

        //其他语言
        $language_skill = [];
        foreach ($v['language_skill'] as $skill) {
            if ($skill['other_text']) {
                $language_skill[] = $skill['other_text'] . ":" . $resumeInfo['other_level'][$skill['other_level']];
            }
        }
        $v->language_skill_str = implode("，", $language_skill);


        $still_os = [];
        foreach ($v['it_skill_os'] as $item) {
            if (isset($resumeInfo['it_skill_os'][$item])) {
                $still_os[] = $resumeInfo['it_skill_os'][$item] ?? '';
            }
        }
        $v->skill_os = implode(" ", $still_os);

        $still_office = [];
        foreach ($v['it_skill_office'] as $item) {
            if (isset($resumeInfo['it_skill_office'][$item])) {
                $still_office[] = $resumeInfo['it_skill_office'][$item];
            }
        }
        $v->skill_office = implode(" ", $still_office);

        $skill_graphic = [];
        foreach ($v['it_skill_graphic'] as $item) {
            if ($item['name']) {
                $skill_graphic[] = $item['name'] != 'その他' ? $item['name'] : $item['other'];
            }
        }
        $v->skill_graphic = implode(" ", $skill_graphic);

        $skill_language = [];
        foreach ($v['it_skill_language'] as $item) {
            if ($item['name']) {
                $skill_language[] = $item['name'] != 'その他' ? $item['name'] : $item['other'];
            }
        }
        $v->skill_language = implode(" ", $skill_language);

        $skill_db = [];
        foreach ($v['it_skill_db'] as $item) {
            if ($item['name']) {
                $skill_db[] = $item['name'] != 'その他' ? $item['name'] : $item['other'];
            }
        }
        $v->skill_db = implode(" ", $skill_db);

        $skill_framework = [];
        foreach ($v['it_skill_framework'] as $item) {
            if ($item['name']) {
                $skill_framework[] = $item['name'] != 'その他' ? $item['name'] : $item['other'];
            }
        }
        $v->skill_framework = implode(" ", $skill_framework);

        $v->commuting_hours_str = $resumeInfo['commuting_hours'][$v['commuting_hours']] ?? '';
        $v->family_members_num_str = $resumeInfo['family_members_num'][$v['family_members_num']] ?? '';

        $v->is_spouse_str = $resumeInfo['is_spouse'][$v['is_spouse']] ?? '';
        $v->is_spouse_support_str = $resumeInfo['is_spouse_support'][$v['is_spouse_support']] ?? '';

        $desired_place = [];
        foreach ($v['desired_place'] as $val) {
            $resumeInfo['desired_place'][$val] and $desired_place[] = $resumeInfo['desired_place'][$val];
        }
        if ($resumeInfo['country_city'][$v['desired_place_ids']]) {
            if (isset($resumeInfo['country_city'][$v['desired_place_ids']])) {
                $desired_place[] = $resumeInfo['country_city'][$v['desired_place_ids']];
            }
        }
        $v->desired_places = implode("、", $desired_place);

        $v->is_spouse_str = $resumeInfo['is_experience'][$v['is_experience']];
        return $v;
    }

}
