@extends('layouts.app_user')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            {{-- 対象年月を表示 --}}
            <div class="row pull-left">
                <label for="targetYearMonth" class="col-md-2 col-form-label text-md-right">{{ __('対象年月 ') }}</label>
                <div class="col-md-6">
                    <div class="col-xs-3">
                        <select id="targetYearMonth" class="form-control col-md-10 col-form-label text-md-left" name="targetYearMonth">
                            @foreach ($targetYearMonths as $key => $value)
                            <option value="{{ $key }}"<?php if ($key == $dateToday) echo ' selected'; ?>>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-6"></div>
                <div class="col-md-6" id="userName"></div>
            </div>

            {{-- 勤務表Chart --}}
            <div class="col-md-10">
            <canvas id="WorkScheduleChart"></canvas>
            <div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
const userId = {{ $user['id'] }};
let targetDate = '{{ $dateToday }}';
const isFluctuation = {{ config('const.workingtimeType.fluctuation.id') }};
let WorkScheduleChartObj = '';

{{-- 日付計算用関数 --}}
var timeMath = {
    {{-- 加算 --}}
    sum : function() {
        var result, times, second, i,
            len = arguments.length;

        if (len === 0) return;

        for (i = 0; i < len; i++) {
            if (!arguments[i] || !arguments[i].match(/^[0-9]+:[0-9]{2}:[0-9]{2}$/)) continue;

            times = arguments[i].split(':');
            second = this.toSecond(times[0], times[1], times[2]);

            if ((!second && second !== 0)) continue;

            if (i === 0) {
                result = second;
            } else {
                result += second;
            }
        }

        return this.toTimeFormat(result);
    },

    {{-- 減算 --}}
    sub : function() {
        var result, times, second, i,
            len = arguments.length;

        if (len === 0) return;

        for (i = 0; i < len; i++) {
            if (!arguments[i] || !arguments[i].match(/^[0-9]+:[0-9]{2}:[0-9]{2}$/)) continue;

            times = arguments[i].split(':');
            second = this.toSecond(times[0], times[1], times[2]);

            if (!second) continue;

            if (i === 0) {
                result = second;
            } else {
                result -= second;
            }
        }

        return this.toTimeFormat(result);
    },

    {{-- 乗算 --}}
    multiply : function() {
        var result, times, second, i,
            len = arguments.length;

        if (len === 0) return;

        for (i = 0; i < len; i++) {
            if (!arguments[i] || !arguments[i].match(/^[0-9]+:[0-9]{2}:[0-9]{2}$/)) continue;

            times = arguments[i].split(':');
            second = this.toSecond(times[0], times[1], times[2]);

            if (!second) continue;

            if (i === 0) {
                result = second;
            } else {
                result *= second;
            }
        }

        return this.toTimeFormat(result);
    },

    {{-- 除算 --}}
    division : function() {
        var result, times, second, i,
            len = arguments.length;

        if (len === 0) return;

        for (i = 0; i < len; i++) {
            if (!arguments[i] || !arguments[i].match(/^[0-9]+:[0-9]{2}:[0-9]{2}$/)) continue;

            times = arguments[i].split(':');
            second = this.toSecond(times[0], times[1], times[2]);

            if (!second) continue;

            if (i === 0) {
                result = second;
            } else {
                result /= second;
            }
        }

        return this.toTimeFormat(result);
    },

    {{-- 時間を秒に変換 --}}
    toSecond : function(hour, minute, second) {
        if ((!hour && hour !== 0) || (!minute && minute !== 0) || (!second && second !== 0) ||
            hour === null || minute === null || second === null ||
            typeof hour === 'boolean' ||
            typeof minute === 'boolean' ||
            typeof second === 'boolean' ||
            typeof Number(hour) === 'NaN' ||
            typeof Number(minute) === 'NaN' ||
            typeof Number(second) === 'NaN') return;

        return (Number(hour) * 60 * 60) + (Number(minute) * 60) + Number(second);
    },

    {{-- 秒を時間（hh:mm:ss）のフォーマットに変換 --}}
    toTimeFormat : function(fullSecond) {
        var hour, minute, second;

        if ((!fullSecond && fullSecond !== 0) || !String(fullSecond).match(/^[\-0-9][0-9]*?$/)) return;

        var paddingZero = function(n) {
            return (n < 10)  ? '0' + n : n;
        };

        hour   = Math.floor(Math.abs(fullSecond) / 3600);
        minute = Math.floor(Math.abs(fullSecond) % 3600 / 60);
        second = Math.floor(Math.abs(fullSecond) % 60);

        minute = paddingZero(minute);
        second = paddingZero(second);

        return ((fullSecond < 0) ? '-' : '') + hour + ':' + minute + ':' + second;
    }
};

{{-- データ取得用API --}}
function getWorkScheduleChart(targetDate) {

    $.ajax({
        url: "{{ route('api.user.workschedule.getWorkScheduleChartAPI') }}",
        dataType: 'json',
        data: {
            userId: userId,
            targetDate: targetDate
        },
    }).done(function(data, textStatus, jqXHR){
        {{-- 初期値 --}}
        let date_array = [];
        let worktime_array = [];
        let worktime_min_array = [];
        let worktime_max_array = [];
        let sumWorkTimeForChart = 0;
        let dateForChart = 0;
        let workingtimeType = data.user.workingtime_type; // 勤務体系
        let worktimeDay = data.user.worktime_day; // 一日の勤務時間（変動時）
        let maxworktimeMonth = data.user.maxworktime_month; // 一月の勤務時間上限（変動時）
        let workingtimeMin = data.user.workingtime_min; // 一月の勤務時間下限（固定時）
        let workingtimeMax = data.user.workingtime_max; // 一月の勤務時間上限（固定時）

        $('#userName').text('対象名：' + data.user.name);

        {{-- 勤務時間の計算 --}}
        Object.keys(data.workSchedules).forEach(function (key) {
            {{-- チャートの横軸を計算 --}}
            dateForChart++;
            date_array.push(dateForChart);

            {{-- 初期値 --}}
            let worktimeByDay = 0;
            let canCulcurate = true;

            {{-- 初期値 --}}
            let starttime = '00:00:00';
            let starttime_hh = data.workSchedules[key].starttime_hh;
            let starttime_mm = data.workSchedules[key].starttime_mm;
            let endtime = '00:00:00';
            let endtime_hh = data.workSchedules[key].endtime_hh;
            let endtime_mm = data.workSchedules[key].endtime_mm;
            let breaktime = data.workSchedules[key].breaktime;
            let breaktimeMidnight = data.workSchedules[key].breaktime_midnight;

            {{-- 開始時間取得 --}}
            if (starttime_hh == null || starttime_mm == null) {
                canCulcurate = false;
            } else {
                starttime = starttime_hh + ':' + starttime_mm + ':00';
            }

            {{-- 終了時間取得 --}}
            if (endtime_hh == null || endtime_mm == null) {
                canCulcurate = false;
            } else {
                endtime = endtime_hh + ':' + endtime_mm + ':00';
            }

            {{-- 休憩時間取得 --}}
            if (breaktime === null) {
                breaktime = '00:00:00';
            } else {
                elem = String(breaktime);
                breakInt = elem.split(".")[0];
                breakDecimal = elem.split(".")[1];
                breakDecimal = (typeof breakDecimal === "undefined") ? 0 : breakDecimal;
                breakDecimal = (breakDecimal + '00').slice(0,2);
                breakMinute = 60 * parseInt(breakDecimal) / 100;
                breaktime = ('00' + breakInt).slice(-2) + ':' + ('00' + breakMinute).slice(-2) + ':00';
            }

            {{-- 深夜休憩時間取得 --}}
            if (breaktimeMidnight === null) {
                breaktimeMidnight = '00:00:00';
            } else {
                elem = String(breaktimeMidnight);
                breakMidnightInt = elem.split(".")[0];
                breakMidnightDecimal = elem.split(".")[1];
                breakMidnightDecimal = (typeof breakMidnightDecimal === "undefined") ? 0 : breakMidnightDecimal;
                breakMidnightDecimal = (breakMidnightDecimal + '00').slice(0,2);
                breakMidnightMinute = 60 * parseInt(breakMidnightDecimal) / 100;
                breaktimeMidnight = ('00' + breakMidnightInt).slice(-2) + ':' + ('00' + breakMidnightMinute).slice(-2) + ':00';
            }

            if (canCulcurate) {
                worktimeByDayWithoutBreak = timeMath.sub(endtime, starttime);
                worktimeByDay = timeMath.sub(worktimeByDayWithoutBreak, breaktime);
                worktimeByDay = timeMath.sub(worktimeByDay, breaktimeMidnight);
                worktimeByDayForDisplay =  worktimeByDay.split(":")[0] + '.' + ((100 * parseInt(worktimeByDay.split(":")[1]) / 60) + '00').slice(0,2);
            } else {
                worktimeByDayForDisplay = '0';
            }

            {{-- 勤務時間の加算チャート表示用 --}}
            sumWorkTimeForChart = parseFloat(sumWorkTimeForChart) + parseFloat(worktimeByDayForDisplay);
            worktime_array.push(sumWorkTimeForChart);
        });

        {{-- 総勤務時間の計算 --}}
        if (workingtimeType == isFluctuation) {
            thismonthWorkingTimeMin = parseInt(data.workingDay) * parseInt(worktimeDay);
            thismonthWorkingTimeMax = parseInt(thismonthWorkingTimeMin) + parseInt(maxworktimeMonth);
        } else {
            thismonthWorkingTimeMin = parseInt(workingtimeMin);
            thismonthWorkingTimeMax = parseInt(workingtimeMax);
        }

        {{-- チャート用表示 --}}
        let workingtimeMinADaySum = 0;
        for (var i = 0; i < dateForChart; i++) {
            let workingtimeMinADay = thismonthWorkingTimeMin / dateForChart;
            workingtimeMinADaySum = workingtimeMinADaySum + workingtimeMinADay;
            worktime_min_array.push(workingtimeMinADaySum);
        }

        let workingtimeMaxADaySum = 0;
        for (var i = 0; i < dateForChart; i++) {
            let workingtimeMaxADay = thismonthWorkingTimeMax / dateForChart;
            workingtimeMaxADaySum = workingtimeMaxADaySum + workingtimeMaxADay;
            worktime_max_array.push(workingtimeMaxADaySum);
        }

        {{-- サマリー表示 --}}
        {{-- チャートインスタンスを破棄する --}}
        if (WorkScheduleChartObj) {
            WorkScheduleChartObj.destroy();
        }

        {{-- チャートインスタンスを作成する --}}
        var ctx = document.getElementById('WorkScheduleChart').getContext('2d');
        WorkScheduleChartObj = new Chart(ctx, {
            {{-- The type of chart we want to create --}}
            type: 'line',
            {{-- The data for our dataset --}}
            data: {
                labels: date_array,
                datasets: [{
                    label: '勤怠情報',
                    fill: false,
                    borderColor: 'rgba(153, 255, 51, 0.6)',
                    data: worktime_array
                }, {
                    label: '基本勤務時間MIN',
                    fill: false,
                    borderColor: 'rgba(0, 0, 255, 0.5)',
                    data: worktime_min_array
                }, {
                    label: '基本勤務時間MAX',
                    fill: false,
                    {{-- backgroundColor: 'rgba(255, 99, 132, 0.5)', --}}
                    borderColor: 'rgba(255, 99, 132, 0.5)',
                    data: worktime_max_array
                }]
            },
            {{-- Configuration options go here --}}
            options: {
                title: {
                    display: true,
                    fontSize: 18,
                    text: data.thisMonthForDisplay
                },
                scales: {                               // 軸設定
                    yAxes: [{                           // y軸設定
                        display: true,                  // 表示設定
                        scaleLabel: {                   // 軸ラベル設定
                           display: true,               // 表示設定
                           labelString: '勤務時間 (h)',  // ラベル
                           fontSize: 18                 // フォントサイズ
                        },
                        ticks: {                        // 最大値最小値設定
                            fontSize: 18,               // フォントサイズ
                            stepSize: 20                // 軸間隔
                        },
                    }],
                    xAxes: [{                           // x軸設定
                        display: true,                  // 表示設定
                        barPercentage: 0.4,             // 棒グラフ幅
                        categoryPercentage: 0.4,        // 棒グラフ幅
                        scaleLabel: {                   // 軸ラベル設定
                           display: true,               // 表示設定
                           labelString: '日付',          // ラベル
                           fontSize: 18                 // フォントサイズ
                        },
                        ticks: {
                            fontSize: 18                // フォントサイズ
                        },
                    }],
                },
                layout: {                               // レイアウト
                    padding: {                          // 余白設定
                        left: 100,
                        right: 50,
                        top: 0,
                        bottom: 0
                    }
                }
            }
        });
    }).fail(function(jqXHR, textStatus, errorThrown){
        swal("エラー", "開発者に問い合わせお願い致します", "warning");
    });
}

{{-- 対象年月が変更された場合に実行 --}}
$(function() {
    $('#targetYearMonth').change(function() {
        targetYearMonth = $("#targetYearMonth").val();
        getWorkScheduleChart(targetYearMonth);
    });
});

$(document).ready(function() {
    {{-- チャート表示 --}}
    getWorkScheduleChart(targetDate);
});
@endsection
