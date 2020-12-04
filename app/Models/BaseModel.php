<?php

namespace App\Models;

use App\Lib\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ベースModel
 * Class BaseModel
 * @package App
 */
class BaseModel extends Model
{

    // 主キーID
    protected $guarded = ['id'];

    // ソフトデリート定義
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * 独自アクセサ(attribute)
     * @var array
     */
    protected $appends = ['image_url', 'file_url'];

    /**
     * コンストラクタ
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // 親のアクセサと子のアクセサ配列をマージ
        $this->appends = array_merge($this->child_appends(), $this->appends);
    }

    /**
     * 子クラス独自アクセサ(オーバーライドして使用する)
     * @return array
     */
    public function child_appends()
    {
        return [];
    }

    /**
     * 画像URL取得
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return Util::getImageUrl($this->image_file_name);
    }

    /**
     * ファイルURL取得
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        return Util::getFileUrl($this->file_name);
    }

}
