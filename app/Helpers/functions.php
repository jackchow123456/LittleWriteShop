<?php

if (extension_loaded('mbstring')) {
    mb_internal_encoding('UTF-8');

    function utf8_strlen($string)
    {
        return mb_strlen($string);
    }

    function utf8_substr($string, $offset, $length = null)
    {
        if ($length === null) {
            return mb_substr($string, $offset, utf8_strlen($string));
        } else {
            return mb_substr($string, $offset, $length);
        }
    }

    function utf8_strpos($string, $needle, $offset = 0)
    {
        return mb_strpos($string, $needle, $offset);
    }
}


function getSavePath($path)
{
    $path = str_replace('upload/admin/', '', $path);
    return 'upload/admin/' . $path;
}

function getDefaultShowImage()
{
    return url('');
}

// 获取店铺id
function getStoreId()
{
    return Admin::user()->store_id;
}

/**
 * 仓库类返回格式
 *
 * @param string $message
 * @param bool $success
 * @param array $data
 * @return array
 */
function returnMessage($message = "成功", $success = false, $data = [])
{
    return ['msg' => $message, 'success' => $success, 'data' => $data];
}

// 返回成功信息
function successMsg($data)
{
    $result = ['data' => $data, 'code' => 200, 'msg' => '请求成功'];
    return \Illuminate\Support\Facades\Response::json($result, 200);
}

// 返回失败信息
function failMsg($msg)
{
    $result = ['data' => null, 'code' => 400, 'msg' => '请求失败，错误信息：' . $msg];
    return \Illuminate\Support\Facades\Response::json($result, 400);
}