<?php

namespace App\Validations\Supports;

/**
 * カスタムバリデーションメッセージの置換処理
 */
trait CustomReplaceMessages
{
    /**
     * max_digitのメッセージ置換処理
     *
     * @param    string  $message     メッセージ
     * @param    string  $attribute   メッセージ置換対象のattribute
     * @param    string  $rule        バリデーションルール名
     * @param    array   $parameters  バリデーションパラメータ配列
     *
     * @return 　string   置換後のメッセージ
     */
    public function replaceMaxDigit($message, $attribute, $rule, $parameters)
    {
        return $this->maxReplace($parameters[0], $message);
    }

    /**
     * min_digitのメッセージ置換処理
     *
     * @param    string  $message     メッセージ
     * @param    string  $attribute   メッセージ置換対象のattribute
     * @param    string  $rule        バリデーションルール名
     * @param    array   $parameters  バリデーションパラメータ配列
     *
     * @return 　string   置換後のメッセージ
     */
    public function replaceMinDigit($message, $attribute, $rule, $parameters)
    {
        return $this->minReplace($parameters[0], $message);
    }

    /**
     * max_byteのメッセージ置換処理
     *
     * @param    string  $message     メッセージ
     * @param    string  $attribute   メッセージ置換対象のattribute
     * @param    string  $rule        バリデーションルール名
     * @param    array   $parameters  バリデーションパラメータ配列
     *
     * @return 　string   置換後のメッセージ
     */
    public function replaceMaxByte($message, $attribute, $rule, $parameters)
    {
        return $this->maxReplace($parameters[0], $message);
    }


    /**
     * :maxを置換する
     * @param string $targetAttribute The target attribute
     * @param string $message The message
     * @return string The replaced message.
     */
    private function maxReplace($targetAttribute, $message)
    {
        $replaceStr = $this->getDisplayableAttribute($targetAttribute);

        return str_replace(':max', $replaceStr, $message);
    }

    /**
     * :minを置換する
     * @param string $targetAttribute The target attribute
     * @param string $message The message
     * @return string The replaced message.
     */
    private function minReplace($targetAttribute, $message)
    {
        $replaceStr = $this->getDisplayableAttribute($targetAttribute);

        return str_replace(':min', $replaceStr, $message);
    }
}
