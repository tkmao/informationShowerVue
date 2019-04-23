<?php

namespace App\Validations;

// use App\Services\AdminServiceInterface;
use App\Validations\Supports\CustomReplaceMessages;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Validation\Validator;

/**
 * CostumValidator
 */
class CustomValidator extends Validator
{
    /** カスタムバリデーション用の置換メッセージ */
    use CustomReplaceMessages;

    /**
     * The validation rules that imply the field is required.
     *
     * @var array
     */
    protected $addImplicitRules = [];

    /**
     * コンストラクタ
     *
     * @param  \Illuminate\Contracts\Translation\Translator  $translator
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     *
     * @return void
     */
    public function __construct($translator, $data, $rules, $messages, $customAttributes)
    {
        $this->addImplicitExtensions($this->addImplicitRules);

        // 親のバリデータへそのまま移譲
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }

    /**
     * 値が半角数字か確認
     * 半角数字ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateHalfSizeNum($attribute, $value)
    {
        return preg_match(config('customvalidation.halfSizeNum'), $value);
    }

    /**
     * 値が半角英数字か確認
     * 半角英数字ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateHalfSizeAlphaNum($attribute, $value)
    {
        return preg_match(config('customvalidation.halfSizeAlphaNum'), $value);
    }

    /**
     * 値が半角英字か確認
     * 半角英字ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateHalfSizeAlpha($attribute, $value)
    {
        return preg_match(config('customvalidation.halfSizeAlpha'), $value);
    }

    /**
     * 電話番号形式(ハイフン含む)か確認
     * 電話番号形式ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateTelWithHyphen($attribute, $value)
    {
        return preg_match(config('customvalidation.telWithHyphen'), $value);
    }

    /**
     * 値が半角英数字記号か確認
     * 半角英数字記号ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateHalfSizeAlphaNumSymbol($attribute, $value)
    {
        return preg_match(config('customvalidation.halfSizeAlphaNumSymbol'), $value);
    }

    /**
     * 値が半角カナ英数字記号か確認
     * 半角カナ英数字記号ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateHalfSizeKanaAlphaNumSymbol($attribute, $value)
    {
        $value = str_replace(" ", "", $value);

        return preg_match(config('customvalidation.halfSizeKanaAlphaNumSymbol'), $value);
    }

    /**
     * 指定した文字数以下の値が入力されていること
     * 入力した値が指定した文字数を超える場合falseでバリデーションエラーとする
     *
     * @param    string  $attribute   項目名
     * @param    mixd    $value       バリデーションする値
     * @param    array   $parameters  バリデーションパラメータ配列
     * @param    class   $validator   Illuminate\Validation\Validator
     *
     * @return 　　bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateMaxDigit($attribute, $value, $parameters, $validator)
    {
        $this->requireParameterCount(1, $parameters, 'max_digit');
        $strLen = mb_strlen($value);
        $maxDigit = $parameters[0];

        return $strLen <= $maxDigit;
    }

    /**
     * 指定した文字数以上の値が入力されていること
     * 入力した値が指定した文字数を超える場合falseでバリデーションエラーとする
     *
     * @param    string  $attribute   項目名
     * @param    mixd    $value       バリデーションする値
     * @param    array   $parameters  バリデーションパラメータ配列
     * @param    class   $validator   Illuminate\Validation\Validator
     *
     * @return 　　bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateMinDigit($attribute, $value, $parameters, $validator)
    {
        $this->requireParameterCount(1, $parameters, 'min_digit');
        $strLen = mb_strlen($value);
        $minDigit = $parameters[0];

        return $strLen >= $minDigit;
    }

    /**
     * 指定した文字のバイト数が以下の値が入力されていること
     * 入力した値が指定したバイト数を超える場合falseでバリデーションエラーとする
     *
     * @param    string  $attribute   項目名
     * @param    mixd    $value       バリデーションする値
     * @param    array   $parameters  バリデーションパラメータ配列
     * @param    class   $validator   Illuminate\Validation\Validator
     *
     * @return 　　bool　　　　　　　　　　　　　　バリデーション結果
     */
    protected function validateMaxByte($attribute, $value, $parameters, $validator)
    {
        $this->requireParameterCount(1, $parameters, 'max_byte');
        $strLen = strlen($value);
        $maxByte = $parameters[0];

        return $strLen <= $maxByte;
    }


    /**
     * 値がINT型の範囲か確認
     * INT型の範囲ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateIntType($attribute, $value)
    {
        $min = config('customvalidation.intMinValue');
        $max = config('customvalidation.intMaxValue');

        return ($value >= $min && $value <= $max);
    }

    /**
     * 値がINT型の範囲か確認
     * INT型の範囲ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateIntUnsignedType($attribute, $value)
    {
        $min = config('customvalidation.intUnsignedMinValue');
        $max = config('customvalidation.intUnsignedMaxValue');

        return ($value >= $min && $value <= $max);
    }

    /**
     * 値がTINYINT型の範囲か確認
     * TINYINT型の範囲ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateTinyintType($attribute, $value)
    {
        $min = config('customvalidation.tinyintMinValue');
        $max = config('customvalidation.tinyintMaxValue');

        return ($value >= $min && $value <= $max);
    }

    /**
     * 値がTINYINT型の範囲か確認
     * TINYINT型の範囲ではない場合falseでバリデーションエラーとする
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateTinyintUnsignedType($attribute, $value)
    {
        $min = config('customvalidation.tinyintUnsignedMinValue');
        $max = config('customvalidation.tinyintUnsignedMaxValue');

        return ($value >= $min && $value <= $max);
    }

    /**
     * admin user が最後の一人か確認
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateAdminsDeleteable($attribute, $value)
    {
        $adminsCount = \DB::table('admins')->count();

        return $adminsCount > 1;
    }

    /**
     * helpfulness 子topicが存在しない
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateHelpfulnessesDeletable($attribute, $value)
    {
        $topics = \App\Repositories\Models\Helpfulness::find($value)->topic()->get();
        return count($topics) < 1;
    }

    /**
     * companyに属するユーザーが存在しないことを確認
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     *
     * @return     bool                 バリデーション結果
     */
    protected function validateCompaniesDeletable($attribute, $value)
    {
        $users = \App\Repositories\Models\Company::find($value)->user()->get();

        return count($users) < 1;
    }

    /**
     * 応募枠が確定ツアー数以上であることを確認
     *
     * @param      string  $attribute   項目名
     * @param      mixd    $value       バリデーションする値
     * @return     bool                 バリデーション結果
     */
    protected function validateCheckMorethanFixedtour($attribute, $value)
    {
        $values = explode("-", $value);
        $id = $values[0];
        $members = $values[1];

        // applicant から tourid があるもの
        $fixedTours = \App\Repositories\Models\Applicant::with(['tour'])
        ->where('recruitment_number_id', $id)
        ->whereHas('tour', function ($query) {
            $query->whereNotNull('release_at');
        })
        ->count();

        return $fixedTours <= $members;
    }
}
