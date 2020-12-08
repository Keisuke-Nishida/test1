<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * API用Baseコントローラー
 * Class BaseApiController
 * @package App\Http\Controllers\Api
 */
class BaseApiController extends Controller
{

    protected $mainApiService;

    /**
     * コンストラクター
     * BaseApiController constructor.
     */
    public function __construct()
    {
    }

    /**
     * 正常時レスポンス
     * @param array $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($response = [])
    {
        $rtn['status'] = 1;
        foreach ($response as $key => $value) {
            $rtn[$key] = $value;
        }
        return response()->json($rtn);
    }

    /**
     * エラー時レスポンス
     * @param $status
     * @param array $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($status, $response = [])
    {
        $rtn['status'] = $status;
        foreach ($response as $key => $value) {
            $rtn[$key] = $value;
        }
        \Log::error('status:' . $status . ", message:" . json_encode($response));
        return response()->json($rtn);
    }
}
