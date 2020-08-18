@if(!$content['agent_role'])
<p>---------------------------------------</p>
<br/>
@endif

@if($content['agent_id'])
<p>エージェントID：{{$content['agent_id']}}</p>
<p>エージェント会社名：{{$content['agent_name']}}</p>
<p>システム管理ID：{{$content['agent_account_code']}}</p>
@endif

@if($content['agent_role'])
    <p>{{$content['agent_name']}}</p>
    <p>{{$content['principal_name']}}様</p>
    <br/>
    <p>いつもお世話になっております。</p>
    <p>ハイナビズ株式会社グローバル人材事業部でございます。</p>

    <br/>
    <p>求職者簡単履歴をご登録いただき、誠にありがとうございます。</p>
    <p>以下の内容をお受けいたしました。</p>
    <p>---------------------------------------</p>
@endif

<p>ユーザーID：{{$content['userCodeId']}}</p>
<p>名前：{{$content['name']}}</p>
<p>性別：{{$content['sex']}}</p>
<p>年齢：{{$content['age']}}</p>
<p>国籍：{{$content['nationality']}}</p>
@if($content['address'] == 1)
    <p>{{$content['zhusuo'][0]}}<p>
    <p>{{$content['zhusuo'][1]}}<p>
    <p>{{$content['zhusuo'][2]}}<p>
    <p>{{$content['zhusuo'][3]}}<p>
@else
    <p>{{$content['zhusuo'][0]}}<p>
@endif

@if($content['employment_status'] == 1)
    <p>{{$content['jiuzhi'][0]}}<p>
    <p>{{$content['jiuzhi'][1]}}<p>
@else
    <p>{{$content['jiuzhi'][0]}}<p>
    <p>{{$content['jiuzhi'][1]}}<p>
@endif
{{$content['zhusuo']}}
{{$content['jiuzhi']}}
<p>最終学歴：{{$content['final_education']}}</p>
<p>文系・理系：{{$content['science_arts']}}</p>
<p>大学名：{{$content['university']}}</p>
<p>学科専攻：{{$content['major']}}</p>
{{--<p>面接対策指導を受けてみますか？：{{$content['interview']}}</p>--}}
<p>日本語レベル：{{$content['jp_level']}}</p>
<p>英語レベル：{{$content['en_level']}}</p>
<p>TOEIC：{{$content['toeic']}}</p>
<p>Eメールアドレス：{{$content['email']}}</p>
<p>携帯電話：{{$content['cell_phone']}}</p>
<p>WeChat ID：{{$content['wechat_id']}}</p>
<p>Line ID：{{$content['line_id']}}</p>
<p>Skype ID：{{$content['skype_id']}}</p>
{{--<p>どのようにして「findjapanjob.com」を知りましたか？：{{$content['know_way']}}</p>--}}
<p>ITスキル：{{$content['it_skill']}}</p>
<p>希望業種：{{$content['desired_fileds']}}</p>
<p>希望職種：{{$content['desired_job_type']}}</p>
<p>希望勤務地：{{$content['desired_place']}}</p>
<p style='white-space: pre-line;'>自己PRとその他希望条件等、自由にお書きください。:</p>
<p style='white-space: pre-line;'>{{$content['pr_other']}}</p>
@if($content['agent_type'])
<p style='white-space: pre-line;'>推薦文:</p>
<p style='white-space: pre-line;'>{{$content['recommendation']}}</p>
@endif
<br/>
<p>---------------------------------------</p>

<br/>
<p>ハイナビズ株式会社グローバル人材事業部</p>
<p>電話：03-6161-6202（平日9:00～18:00）</p>
<p>〒105-0004 東京都港区新橋5-12-11 天翔新橋5丁目ビル702</p>

<p><a href='https://www.findjapan.com'>https://www.findjapanjob.com</a></p>
<p>hr@findjapanjob.com</p>