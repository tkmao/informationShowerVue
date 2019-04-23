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
                            <option value="{{ $key }}"<?php if ($key == $targetYearMonth) echo ' selected'; ?>>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <form method="GET" action="/monthlyanalyze/csv">
                <input id="targetYearMonthCSV" name="targetYearMonth" type="hidden" value="{{ $targetYearMonth }}">
                <button type="submit" class="btn btn-success pull-right">CSV Download</button>
            </form>

            <p id="employee"></p>
            <p id="basicworkingtime"></p>
            <p id="basicworkingday"></p>
            <p id="shortagecount"></p>
            <p id="overtimecount"></p>

            <div class="p-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#usertab" class="nav-link active" data-toggle="tab">個人単位</a>
                    </li>
                    <li class="nav-item">
                        <a href="#projecttab" class="nav-link" data-toggle="tab">プロジェクト単位</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="usertab">
                        <table class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr class="table-info">
                                    <th width="10%">ユーザ名</th>
                                    <th width="10%">勤務時間グラフ</th>
                                    <th width="10%">勤務時間</th>
                                    <th width="10%">基本勤務時間</th>
                                    <th width="10%">当月不足時間</th>
                                    <th width="10%">当月超過時間</th>
                                    <th width="10%">出勤日数</th>
                                    <th width="10%">超過日数</th>
                                    <th width="10%">欠勤日数</th>
                                </tr>
                            </thead>
                            <tbody id="workschedulelist-table">
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="projecttab">
                            <table class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr class="table-info">
                                    <th width="10%">プロジェクト名</th>
                                    <th width="10%">総勤務時間</th>
                                    <th width="10%">参加人数</th>
                                    <th width="10%">全体割合</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="10%">プロジェクトA</a></td>
                                    <td width="10%">200h</td>
                                    <td width="10%">4 人</td>
                                    <td width="10%">30%</td>
                                </tr>
                                <tr>
                                <td width="90%" colspan="4">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr class="table-warning">
                                            <th width="10%">個人名</th>
                                            <th width="10%">勤務時間</th>
                                            <th width="10%">割合</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">加茂剛</a></td>
                                            <td width="10%">200h</td>
                                            <td width="10%">30%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛2</a></td>
                                            <td width="10%">150h</td>
                                            <td width="10%">50%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛3</a></td>
                                            <td width="10%">100h</td>
                                            <td width="10%">20%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛4</a></td>
                                            <td width="10%">100h</td>
                                            <td width="10%">20%</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                                </tr>
                                <tr>
                                    <td width="10%">プロジェクトB</a></td>
                                    <td width="10%">150h</td>
                                    <td width="10%">3 人</td>
                                    <td width="10%">50%</td>
                                </tr>
                                <tr>
                                <td width="90%" colspan="4">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr class="table-warning">
                                            <th width="10%">個人名</th>
                                            <th width="10%">勤務時間</th>
                                            <th width="10%">割合</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">加茂剛</a></td>
                                            <td width="10%">200h</td>
                                            <td width="10%">30%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛2</a></td>
                                            <td width="10%">150h</td>
                                            <td width="10%">50%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛3</a></td>
                                            <td width="10%">100h</td>
                                            <td width="10%">20%</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                                </tr>
                                <tr>
                                    <td width="10%">プロジェクトC</a></td>
                                    <td width="10%">100h</td>
                                    <td width="10%">2 人</td>
                                    <td width="10%">20%</td>
                                </tr>
                                <tr>
                                <td width="90%" colspan="4">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr class="table-warning">
                                            <th width="10%">個人名</th>
                                            <th width="10%">勤務時間</th>
                                            <th width="10%">割合</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="10%">加茂剛</a></td>
                                            <td width="10%">200h</td>
                                            <td width="10%">30%</td>
                                        </tr>
                                        <tr>
                                            <td width="10%">加茂剛2</a></td>
                                            <td width="10%">150h</td>
                                            <td width="10%">50%</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
{{-- 変数初期化 --}}
const worktimeADay = 8;
const isFluctuation = {{ config('const.workingtimeType.fluctuation.id') }};
let targetYearMonth = "{{ $targetYearMonth }}";
let undefineProjectId = {{ config('const.undefineProjectId') }};
let employee = 0;
let workingDayAWeek = 0;
let shortageCount = 0;
let overtimeCount = 0;

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
function getMonthlyReportSummary(targetYearMonth) {

    $.ajax({
        url: "{{ route('api.user.monthlyanalyze.getMonthlyReportSummaryAPI') }}",
        dataType: 'json',
        data: {
            targetYearMonth: targetYearMonth
        },
    }).done(function(data, textStatus, jqXHR){
        {{-- 初期化 --}}
        shortageCount = 0;
        overtimeCount = 0;

        {{-- 全社員数 --}}
        employee = Object.keys(data).length;

        {{-- 勤務表の情報を再登録 --}}
        $('#workschedulelist-table').empty();

        {{-- データ表示 --}}
        Object.keys(data).forEach(function (key) {
            {{-- 初期化 --}}
            workingDayAWeek = 0;
            dayoffCount = 0;

            {{-- データ設定 --}}
            worktimeByWeek = '00:00:00'; // 今週の合計勤務時間
            workDayByWeek = data[key].workingDay; // 今週の勤務日数

            {{-- 当月の下限・上限勤務時間 --}}
            if (data[key].workingtime_type == isFluctuation) {
                basicWorktimeMin = data[key].workingDay * data[key].worktime_day;            // 当月の下限勤務時間
                basicWorktimeMax = basicWorktimeMin + parseInt(data[key].maxworktime_month); // 当月の上限勤務時間
            } else {
                basicWorktimeMin = data[key].workingtime_min; // 当月の下限勤務時間
                basicWorktimeMax = data[key].workingtime_max; // 当月の上限勤務時間
            }

            {{-- 勤務時間計算 --}}
            Object.keys(data[key].workSchedule.workSchedules).forEach(function(index) {
                {{-- セレクタ設定 --}}
                workSchedule = data[key].workSchedule.workSchedules[index];

                {{-- 欠勤日数計算 --}}
                if (workSchedule.is_holiday === 0 && workSchedule.starttime === null) {
                    dayoffCount++;
                }

                {{-- 開始時間 --}}
                if (workSchedule.starttime_hh === null || workSchedule.starttime_mm === null) {
                    starttime = '00:00:00';
                } else {
                    starttime = String(workSchedule.starttime_hh) + ':' + String(workSchedule.starttime_mm) + ':00';
                    workingDayAWeek++;
                }

                {{-- 終了時間 --}}
                if (workSchedule.endtime_hh === null || workSchedule.endtime_mm === null) {
                    endtime = '00:00:00';
                } else {
                    endtime = String(workSchedule.endtime_hh) + ':'  + String(workSchedule.endtime_mm) + ':00';
                }

                {{-- 休憩時間 --}}
                if (workSchedule.breaktime === null) {
                    breaktime = '00:00:00';
                } else {
                    elem = String(workSchedule.breaktime);
                    breakInt = elem.split(".")[0];
                    breakDecimal = elem.split(".")[1];
                    breakDecimal = (typeof breakDecimal === "undefined") ? 0 : breakDecimal;
                    breakDecimal = (breakDecimal + '00').slice(0,2);
                    breakMinute = 60 * parseInt(breakDecimal) / 100;

                    breaktime = ('00' + breakInt).slice(-2) + ':' + ('00' + breakMinute).slice(-2) + ':00';
                }

                {{-- 深夜休憩時間 --}}
                if (workSchedule.breaktime_midnight === null) {
                    breaktime_midnight = '00:00:00';
                } else {
                    elem = String(workSchedule.breaktime_midnight);
                    breakMidnightInt = elem.split(".")[0];
                    breakMidnightDecimal = elem.split(".")[1];
                    breakMidnightDecimal = (typeof breakMidnightDecimal === "undefined") ? 0 : breakMidnightDecimal;
                    breakMidnightDecimal = (breakMidnightDecimal + '00').slice(0,2);
                    breakMidnightMinute = 60 * parseInt(breakMidnightDecimal) / 100;

                    breaktime_midnight = ('00' + breakMidnightInt).slice(-2) + ':' + ('00' + breakMidnightMinute).slice(-2) + ':00';
                }

                {{-- 一日の勤務時間の計算 --}}
                worktimeByDayWithoutBreak = timeMath.sub(endtime, starttime);
                worktimeByDay = timeMath.sub(worktimeByDayWithoutBreak, breaktime);
                worktimeByDay = timeMath.sub(worktimeByDay, breaktime_midnight);
                {{-- 勤務時間の計算 --}}
                worktimeByWeek = timeMath.sum(worktimeByWeek, worktimeByDay);
            });

            {{-- 表示用勤務時間表示 --}}
            worktimeByWeekForDisplay =  worktimeByWeek.split(":")[0] + '.' + ((100 * parseInt(worktimeByWeek.split(":")[1]) / 60) + '00').slice(0,2);
            {{-- console.log('勤務時間表示用：', worktimeByWeekForDisplay); --}}
            {{-- 勤務時間表示 --}}
            worktimeByWeek = parseFloat(worktimeByWeekForDisplay);
            {{-- 出勤日数表示 --}}
            {{-- console.log('出勤日数：', workingDayAWeek + "日"); --}}
            {{-- 欠勤日数表示 --}}
            {{-- console.log('欠勤日数：', dayoffCount + "日"); --}}
            {{-- 超過日数表示 --}}
            if (workingDayAWeek > workDayByWeek) {
                holidayWork  = workingDayAWeek - workDayByWeek;
            } else {
                holidayWork  = 0;
            }

            {{-- 不足・超過時間表示 --}}
            {{-- console.log('勤務時間：', worktimeByWeek); --}}
            {{-- console.log('基本勤務時間MIN：', basicWorktimeMin); --}}
            {{-- console.log('基本勤務時間MAX：', basicWorktimeMax); --}}
            if (basicWorktimeMin > worktimeByWeek) {
                {{-- 不足時間表示 --}}
                shortagetime = basicWorktimeMin - worktimeByWeek;
                overtime = 0;
                {{-- console.log('不足時間：', shortagetime); --}}
                {{-- console.log('超過時間：', overtime); --}}
                {{-- 不足時間の人数カウント --}}
                shortageCount++;
            } else if (basicWorktimeMax < worktimeByWeek) {
                {{-- 超過時間表示 --}}
                shortagetime = 0;
                overtime = worktimeByWeek - basicWorktimeMax;
                {{-- console.log('不足時間：', shortagetime); --}}
                {{-- console.log('超過時間：', overtime); --}}
                {{-- 超過時間の人数カウント --}}
                overtimeCount++;
            } else {
                shortagetime = 0;
                overtime = 0;
                {{-- console.log('不足時間：', shortagetime); --}}
                {{-- console.log('超過時間：', overtime); --}}
            }

            {{-- trタグ作成 --}}
            var trid = data[key].user_id;

            $('#workschedulelist-table').append('<tr id="' + (trid) + '"></tr>');

            {{-- tdタグ作成 --}}
            $('#' + trid).append('<td width="10%"><a href="/workschedule/show/' + (data[key].user_id) + '/' + (targetYearMonth) + '">' + data[key].user_name + '</a></td>'); // ユーザ
            $('#' + trid).append('<td width="10%"><a href="/chart/' + (data[key].user_id) + '/' + (targetYearMonth) + '">グラフ</a></td>'); // 勤務時間グラフ
            $('#' + trid).append('<td width="10%">' + worktimeByWeekForDisplay + ' h' + '</td>'); // 勤務時間
            $('#' + trid).append('<td width="10%">' + (basicWorktimeMin) + ' 〜 ' + (basicWorktimeMax) + ' h</td>'); // 基本勤務時間
            if (shortagetime > 0) {
                $('#' + trid).append('<td width="10%"><font color="blue">' + (shortagetime) + ' h' + '</font></td>'); // 不足時間
                $('#' + trid).append('<td width="10%">' + (overtime) + ' h' + '</td>'); // 超過時間
            } else if (overtime > 0) {
                $('#' + trid).append('<td width="10%">' + (shortagetime) + ' h' + '</td>'); // 不足時間
                $('#' + trid).append('<td width="10%"><font color="red">' + (overtime) + ' h' + '</font></td>'); // 超過時間
            } else {
                $('#' + trid).append('<td width="10%">' + (shortagetime) + ' h' + '</td>'); // 当月不足時間
                $('#' + trid).append('<td width="10%">' + (overtime) + ' h' + '</td>'); // 当月超過時間
            }
            $('#' + trid).append('<td width="10%">' + (workingDayAWeek) + ' 日' + '</td>'); // 出勤日数
            $('#' + trid).append('<td width="10%">' + (dayoffCount) + ' 日' + '</td>'); // 欠勤日数
            $('#' + trid).append('<td width="10%">' + (holidayWork) + ' 日' + '</td>'); // 超過日数
        });

        {{-- サマリー表示 --}}
        $('#employee').text('社員人数：' + employee + ' 人');
        $('#basicworkingtime').text('基本勤務時間：' + basicWorktimeMin + ' 時間 〜 ' + basicWorktimeMax + ' 時間');
        $('#basicworkingday').text('基本出勤日数：' + workDayByWeek + ' 人');
        $('#shortagecount').text('不足時間人数：' + shortageCount + ' 人');
        $('#overtimecount').text('超過時間人数：' + overtimeCount + ' 人');

    }).fail(function(jqXHR, textStatus, errorThrown){
        console.log('test2');
    });
}

{{-- 対象年月が変更された場合に実行 --}}
$(function() {
    $('#targetYearMonth').change(function() {
        targetYearMonth = $("#targetYearMonth").val();
        $("#targetYearMonthCSV").val(targetYearMonth);
        getMonthlyReportSummary(targetYearMonth);
    });
});

$(document).ready(function() {
    {{-- 描画終了後にデータ取得 --}}
    getMonthlyReportSummary(targetYearMonth);
});

@endsection
