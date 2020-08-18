<?php

function smarty_function_brave_html_file($params)
{
    $value = '';
    $upload = '';
    $path = '';
    $html = '';
    $display = 'style="display:none"';
    $disable = 'disabled="disabled"';
    $showName = '';
    $showValue = '';
    $viewDisplay = '';
    $viewDisable = '';
    $editDisplay = '';
    $editDisable = '';
    $file = '';
    $save = '';

    if (!isset($params['name'])) {
        return $html;
    }
    else {
        $name = $params['name'];
        $id = preg_replace('/[\[\]]+/', '_', $name);
    }

    if (isset($params['path'])) {
        $path = $params['path'];
    }

    if (isset($params['value']) && !is_array($params['value'])) {
        $value = $params['value'];
    }

    if (isset($params['show_name'])) {
        $showName = $params['show_name'];
    }

    if (isset($params['show_value'])) {
        $showValue = $params['show_value'];
    }

    if (strlen($value)) {
        $save = $value;
        $file = "{$path}{$value}";
        $editDisplay = $display;
        $editDisable = $disable;
    }
    else {
        $viewDisplay = $display;
        $viewDisable = $disable;
    }

    $html.= '<div id="' . $id . '_view" class="bave_file_view" ' . $viewDisplay . '>';

    if (strlen($showValue)) {
        $html.= '[ <a href="' . $file . '" target="_blank">' . $showValue . '</a> ]';
        $html.= '<input type="hidden" id="' . $id . '_save_name" name="' . $showName . '" value="' . $showValue . '" ' . $viewDisable . ' />';
    }
    else {
        $html.= '[ <a href="' . $file . '" target="_blank">' . $save . '</a> ]';
    }

    $html.= '[ <a href="javascript:void(0);" onclick="disableFile(\'' . $id . '\')">←修改</a> ]';
    $html.= '<input type="hidden" id="' . $id . '_save" name="' . $name . '" value="' . $save . '" ' . $viewDisable . ' />';
    $html.= '</div>';

    $html.= '<div id="' . $id . '_edit" class="bave_file_edit" ' . $editDisplay . '>';
    $html.= '<label id="' . $id . '_realBtn" class="btn btn-primary">
        <input type="file" id="' . $id . '_upload" onChange="changeFile(\'' . $id . '\')" name="' . $name . '" ' . $editDisable . ' class="mFileInput" style="left:-9999px;position:absolute;" />
        <span>选择文件</span></label> <span id="' . $id . '_realBtn_ShowName"></span>';

    if (strlen($showName)) {
        $html.= ' 文件名：<input type="text" id="' . $id . '_upload_name"  name="' . $showName . '" ' . $editDisable . ' />';
    }

    $html.= '</div>';

    $html.= '<script type="text/javascript">';
    $html.= 'function disableFile(id) {';
    $html.= '$("#" + id + "_view").hide();';
    $html.= '$("#" + id + "_edit").show();';
    $html.= '$("#" + id + "_save").attr("disabled", true);';
    $html.= '$("#" + id + "_save_name").attr("disabled", true);';
    $html.= '$("#" + id + "_upload").attr("disabled", false);';
    $html.= '$("#" + id + "_upload_name").attr("disabled", false);';
    $html.= '}';
    $html.= 'function changeFile(id) {';
    $html .= "var obj = document.getElementById(id + '_upload');
var len = obj.files.length;
var temp = [];
for (var i = 0; i < len; i++) {
temp.push(obj.files[i].name);
}
console.log(temp);
$('#'+id+'_realBtn_ShowName').html(temp.join(','));
";
    $html.= '}';
    
    
    
    $html.= '</script>';

    return $html;
}

function smarty_function_brave_html_image($params) {
    $value = '';
    $upload = '';
    $path = '';
    $html = '';
    $display = 'style="display:none"';
    $disable = 'disabled="disabled"';
    $showName = '';
    $showValue = '';
    $save = '';
    $image = '';
    $editDisplay = '';
    $editDisable = '';
    $viewDisplay = '';
    $viewDisable = '';

    if (!isset($params['name'])) {
        return $html;
    } else {
        $name = $params['name'];
        $id = preg_replace('/[\[\]]+/', '_', $name);
    }

    if (isset($params['path'])) {
        $path = $params['path'];
    }

    if (isset($params['value']) && !is_array($params['value'])) {
        $value = $params['value'];
    }

    if (isset($params['show_name'])) {
        $showName = $params['show_name'];
    }

    if (isset($params['show_value'])) {
        $showValue = $params['show_value'];
    }

    $options = array();
    if (isset($params['width'])) {
        $options[] = 'width="' . $params['width'] . '"';
    }

    if (isset($params['height'])) {
        $options[] = 'height="' . $params['height'] . '"';
    }

    if (strlen($value)) {
        $save = $value;
        $image = "{$path}{$value}";
        $editDisplay = $display;
        $editDisable = $disable;
    } else {
        $viewDisplay = $display;
        $viewDisable = $disable;
    }

    $html.= '<div id="' . $id . '_view" class="bave_imge_view" ' . $viewDisplay . '>';
    $html.= '<a href="' . $image . '" target="_blank"><img style="max-width:140px; max-height:140px;" ' . join(' ', $options) . ' src="' . asset($image) . '" /></a> ';
    $html.= '<input type="hidden" id="' . $id . '_save" name="' . $name . '" value="' . $save . '" ' . $viewDisable . ' /><span>';

    if (strlen($showName)) {
        $html.= '[ ' . $showValue . ' ]';
        $html.= '<input type="hidden" id="' . $id . '_save_name" name="' . $showName . '" value="' . $showValue . '" ' . $viewDisable . ' />';
    }

    $html.= '<a href="javascript:void(0);" onclick="disableImage(\'' . $id . '\')">[ ←修改 ]</a></span>';
    $html.= '</div>';

    $html.= '<div id="' . $id . '_edit" class="bave_imge_edit" ' . $editDisplay . '>';
    $html.= '<label id="' . $id . '_realBtn" class="btn btn-primary">
        <input type="file" id="' . $id . '_upload" onChange="changeFile(\'' . $id . '\')" name="' . $name . '" ' . $editDisable . ' class="mFileInput" style="left:-9999px;position:absolute;" />
        <span>选择图片</span></label> <span id="' . $id . '_realBtn_ShowName"></span>';

    if (strlen($showName)) {
        $html.= ' 图片名：<input type="text" id="' . $id . '_upload_name" name="' . $showName . '"' . $editDisable . ' />';
    }

    $html.= '</div>';

    $html.= '<script type="text/javascript">';
    $html.= 'function disableImage(id) {';
    $html.= '$("#" + id + "_view").hide();';
    $html.= '$("#" + id + "_edit").show();';
    $html.= '$("#" + id + "_save").attr("disabled", true);';
    $html.= '$("#" + id + "_save_name").attr("disabled", true);';
    $html.= '$("#" + id + "_upload").attr("disabled", false);';
    $html.= '$("#" + id + "_upload_name").attr("disabled", false);';
    $html.= '}';
    $html.= 'function changeFile (id) {';
    $html.= "var obj = document.getElementById(id + '_upload');
    var len = obj.files.length;
    var temp = [];
    for (var i = 0; i < len; i++) {
    temp.push(obj.files[i].name);
    }
    console.log(temp);
    $('#'+id+'_realBtn_ShowName').html(temp.join(','));
    ";
    $html.= '}';
    $html.= '</script>';

    return $html;
}

?>
