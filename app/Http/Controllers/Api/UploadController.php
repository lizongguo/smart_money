<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) bravesoft Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Libraries\BraveUpload;
use App\Http\Controllers\Valid;
use App\Models\Attachment;

class UploadController extends BaseController
{
    protected $attachment = null;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->attachment = new Attachment;
    }
    
    /**
     * 文件上传插件
     * @param Request $request
     * @param type $object
     * @return type
     */
    public function save(Request $request, $object)
    {
        set_time_limit(0);
        $config = config('code.upload');
        //默认fileinput
        $uploadConfig = $config['fileinput'];
        //特殊配置，特殊处理
        if(isset($config[$object])) {
            $uploadConfig = $config[$object];
        }
        $upload = new BraveUpload;
        $upload->upload($uploadConfig);
        $post = $_POST;

        if (!$post['attachment']) {
            $post['attachment'] = $post['attachment_1'];
            $post['attachment_info'] = $post['attachment_1_info'];
            unset($post['attachment_1'], $post['attachment_1_info']);
        }

        $valid = new Valid();
        $config = array(
            'attachment' => array(
                array('isNotNull', 'file_tmp_null'),
            ),
            'attachment_info' => array(
                array('isUploadFile', 'file_is_not_find'),
                array('isUploadFileFail', 'file_tmp_null'),
                array('isValidExt', 'file_ext_invalid'),
                array('isValidSize', 'file_size_invalid'),
                array('fileMoveSucc', 'file_move_failure'),
                array('imageValidPixel', 'image_pixel_failure'),
            )
        );
        if($valid->valid($config, $post) == false) {
            $error = $valid->getError();
            $this->back['status'] = 400;
            $this->back['code'] = 2;
            $error = $valid->langs($error);
            $this->back['msg'] = isset($error['attachment_info']) ? $error['attachment_info'] : $error['attachment'];
            return $this->dataToJsonText($this->back);
        }
        $data = [
            'object' => $object,
            'object_id' => (int)0,
            'file_path' => $post['attachment'],
            'thumb_path' => isset($post['attachment_info']['save']) ? $post['attachment_info']['save'] : '',
            'size' => (int)$post['attachment_info']['size'],
            'file_name' => $post['attachment_info']['name'],
            'face_back' => isset($post['faceBack']) ? $post['faceBack'] : '',
        ];
        $id = $this->attachment->saveItem($data);

        if($id === false) { //保存失败
            $error = $valid->getError();
            $this->back['status'] = 500;
            $this->back['code'] = 2;
            $this->back['msg'] = 'ファイルのアップロードに失敗しました。';
            return $this->dataToJsonText($this->back);
        }
        //save success
        $this->back['msg'] = 'アップロード成功。';
        $data['id'] = (int)$id;
        $data['name'] = $data['file_name'];
        $data['src'] = asset($data['file_path']);
        $this->back['code'] = 0;
        $this->back['data'] = $data;
        return $this->dataToJsonText($this->back);
    }


    public function savefile(Request $request, $object)
    {
        set_time_limit(0);
        $config = config('code.upload');
        //默认fileinput
        $uploadConfig = $config['fileinput'];
        //特殊配置，特殊处理
        if(isset($config[$object])) {
            $uploadConfig = $config[$object];
        }
        $upload = new BraveUpload;
        $upload->upload($uploadConfig);
        $post = $_POST;
        if (!$post['attachment']) {
            $post['attachment'] = $post['attachment_1'];
            $post['attachment_info'] = $post['attachment_1_info'];
            unset($post['attachment_1'], $post['attachment_1_info']);
        }

        $valid = new Valid();
        $config = array(
            'attachment' => array(
                array('isNotNull', 'file_wrong'),
            ),
            'attachment_info' => array(
                array('isUploadFile', 'file_wrong'),
                array('isUploadFileFail', 'file_wrong'),
                array('isValidExt', 'file_wrong'),
                array('isValidSize', 'file_wrong'),
                array('fileMoveSucc', 'file_wrong'),
            )
        );
        if ($object=='projectFile') {
            $config = array(
                'attachment' => array(
                    array('isNotNull', 'file_word_wrong'),
                ),
                'attachment_info' => array(
                    array('isUploadFile', 'file_word_wrong'),
                    array('isUploadFileFail', 'file_word_wrong'),
                    array('isValidExt', 'file_word_wrong'),
                    array('isValidSize', 'file_word_wrong'),
                    array('fileMoveSucc', 'file_word_wrong'),
                )
            );
        }
        if($valid->valid($config, $post) == false) {
            $error = $valid->getError();
            $this->back['status'] = 400;
            $this->back['code'] = 2;
            $error = $valid->langs($error);
            $this->back['msg'] = isset($error['attachment_info']) ? $error['attachment_info'] : $error['attachment'];
            return $this->dataToJsonText($this->back);
        }
        $data = [
            'object' => $object,
            'object_id' => (int)0,
            'file_path' => $post['attachment'],
            'thumb_path' => isset($post['attachment_info']['thumbnail']) ? $post['attachment_info']['thumbnail'] : '',
            'size' => (int)$post['attachment_info']['size'],
            'file_name' => $post['attachment_info']['name'],
            'face_back' => isset($post['faceBack']) ? $post['faceBack'] : '',
        ];
        $id = $this->attachment->saveItem($data);

        if($id === false) { //保存失败
            $error = $valid->getError();
            $this->back['status'] = 500;
            $this->back['code'] = 2;
            $this->back['msg'] = '上传文件失败';
            return $this->dataToJsonText($this->back);
        }
        //save success
        $this->back['msg'] = '上传成功';
        $data['id'] = (int)$id;
        $data['name'] = $data['file_name'];
        $data['src'] = asset($data['file_path']);
        $this->back['code'] = 0;
        $this->back['data'] = $data;
        return $this->dataToJsonText($this->back);
    }

    public function delete(Request $request, $id)
    {
        return $this->back;
    }
    
    public function deleteFile(Request $request)
    {
        $files = $request->all();
        if (isset($files['imgpath'])) {
            $upload = str_replace(asset(''), "/", $files['imgpath']);
            file_exists(public_path() . $upload) ? unlink(public_path() . $upload) : null;
            $thumb = preg_replace("#^(.*/)([^/]*)$#i", '$1thumb_$2', $upload);
            file_exists(public_path() . $thumb) ? unlink(public_path() . $thumb) : null;
        }
        if (isset($files['filepath'])) {
            unlink(str_replace(asset(), public_path(). DIRECTORY_SEPARATOR, $files['filepath']));
        }
        return $this->back;
    }
    
}
