<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class RequestBase extends FormRequest
{
    /**
     * バリデーションルール定義
     */
    private $ruleBases;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // ルール定義ファイルを取得
        if (file_exists($filename = app_path() . '/Validations/CommonRules.php')) {
            $this->ruleBases = require $filename;
        }
        // TODO:エラー処理
    }

    /**
     * ルール定義を取得
     *
     * @param      array  $targetRules  取得対象のルール[ (string)ルール名, (string)required|nullable]
     *
     * @return     array  The rules.
     */
    public function getRules($targetRules)
    {
        $rules = [];
        foreach ($targetRules as $ruleName => $options) {
            if (array_key_exists($ruleName, $this->ruleBases)) {
                if ($ruleName == 'email') {
                    $rules[$ruleName] = $this->ruleBases[$ruleName] . '|' . $options;
                } else {
                    $rules[$ruleName] = $options . '|' . $this->ruleBases[$ruleName];
                }
            } else {
                $rules[$ruleName] = $options;
            }
        }
        return $rules;
    }

    /**
     * requestインスタンスのパラメータをruleを元に配列化する（パラメータとして存在しないリクエストもnullで作成）
     *
     * @return     array
     */
    public function makeAllRequestArray()
    {
        $keys = array_keys($this->rules());
        foreach ($keys as $i => $key) {
            if (strpos($key, '*') === false) continue;
            unset($keys[$i]);
        }
        $requestArray = $this->only($keys);

        return $requestArray;
    }
}
