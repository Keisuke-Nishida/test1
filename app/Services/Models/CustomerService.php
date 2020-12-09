<?php

namespace App\Services\Models;

use App\Models\Customer;
use App\Lib\Constant;

/**
 * 得意先マスタサービス
 * Class CustomerService
 * @package App\Services\Models
 */
class CustomerService extends BaseService
{
    /**
     * コンストラクタ
     * CustomerService constructor.
     */
    public function __construct()
    {
        $this->model = new Customer();
    }

    /**
     * updateCoreSystemStatus
     * 対象ログインユーザーの得意先IDをもとに得意先マスタを検索
     * 基幹システム連携ステータスが 0 の場合は 1 に更新する
     *
     * @param  mixed $user
     * @return void
     */
    public function updateCoreSystemStatus($user)
    {
        $customer = $this->searchOne(["id" => $user->customer_id]);

        \DB::beginTransaction();
        try {
            if ($customer->core_system_status == Constant::STATUS_NON_LINKED) {
                $customer->core_system_status = Constant::STATUS_WAITING_FOR_LINKAGE;
                $customer->updated_by = $user->id;
                $customer->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('database save error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }
}
