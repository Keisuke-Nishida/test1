<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\Models\UserAgreeDataService;
use Illuminate\Http\Request;

/**
 * API用同意履歴コントローラー
 * Class UserAgreeDataApiController
 * @package App\Http\Controllers\Api
 */
class UserAgreeDataApiController extends BaseApiController
{
    /**
     * コンストラクター
     * UserAgreeDataApiController constructor.
     */
    public function __construct(
        UserAgreeDataService $userAgreeDataService
    ) {
        $this->mainApiService = $userAgreeDataService;
    }

    /**
     * save
     * 利用規約同意時の利用規約同意データの登録と更新
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        \DB::beginTransaction();
        try {
            $this->mainApiService->saveUserAgreeData($request->user_id);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $status = 2;
            $response = [
                'message'    => Message::getMessage(Message::ERROR_015, ["同意情報"]),
                'error_data' => $e->getMessage()
            ];
            return $this->error($status, $response);
        }

        $response = ["message" => "success"];
        return $this->success($response);
    }
}
