<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\HolidayDeleteRequest;
use App\Http\Requests\User\HolidayEditRequest;
use App\Http\Requests\User\HolidayGetRequest;
use App\Http\Requests\User\HolidayStoreRequest;
use App\Services\User\HolidayServiceInterface;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /** @var HolidayServiceInterface */
    protected $holidayServiceInterface;

    /**
     * @param App\Services\HolidayServiceInterface  $holidayServiceInterface  The holiday service interface
     */
    public function __construct(
        HolidayServiceInterface $holidayServiceInterface
    ) {
        $this->holidayServiceInterface = $holidayServiceInterface;
    }

    /**
     * 祝日一覧表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        try {
            // 祝日一覧取得
            $holidays = $this->holidayServiceInterface->getAllHoliday();

            return view('user.holiday.show', compact('holidays'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 祝日情報登録処理
     *
     * @param HolidayStoreRequest $request
     * @return void
     */
    public function store(HolidayStoreRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->holidayServiceInterface->store($requestArray);

            return redirect()->route('user.holiday.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 祝日編集処理
     *
     * @param HolidayEditRequest $request
     * @return void
     */
    public function edit(HolidayEditRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->holidayServiceInterface->edit($requestArray);

            return redirect()->route('user.holiday.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 祝日削除処理
     *
     * @param HolidayDeleteRequest $request
     * @return void
     */
    public function delete(HolidayDeleteRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            $this->holidayServiceInterface->delete($requestArray['holidayId']);

            return redirect()->route('user.holiday.show');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 祝日情報取得処理
     *
     * @param HolidayGetRequest $request
     * @return void
     */
    public function getHolidayAPI(HolidayGetRequest $request)
    {
        try {
            $requestArray = $request->makeAllRequestArray();
            // 祝日
            $getHoliday = $this->holidayServiceInterface->getHoliday($requestArray['holidayId']);

            return response()->json($getHoliday);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
