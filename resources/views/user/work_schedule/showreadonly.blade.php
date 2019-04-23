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

            <div class="row pull-left">
                <p>{!! nl2br(e($user['name'])) !!}</p>
            </div>

            <div class="row pull-left">
                <p>{{ $thisMonthForDisplay }}</p>
            </div>

            {{-- 勤務表の提出情報を表示 --}}
            @if ($isSubmited == 1)
            <div id="subbmited-success" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-6 alert alert-success alert-dissmissible">
                    <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <div>当月分の勤務表は提出済みです</div>
                </div>
            </div>
            @else
            <div id="subbmited-warning" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-6 alert alert-warning alert-dissmissible">
                    <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <div>当月分の勤務表は未提出です</div>
                </div>
            </div>
            @endif

            <div class="row pull-left">
                <form method="GET" action="/workschedule/show/{{ $user['id'] }}/{{ $lastMonthDate }}">
                    <button type="submit" class="btn btn-warning btn-lg pull-right">先月</button>
                </form>
                <form method="GET" action="/workschedule/show/{{ $user['id'] }}/{{ $nextMonthDate }}">
                    <button type="submit" class="btn btn-warning btn-lg pull-right">翌月</button>
                </form>
            </div>

            {{-- チャート表示 --}}
            <div class="row pull-left">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                <form method="GET" action="/chart/{{ $user['id'] }}/{{ $dateToday }}">
                    <button type="submit" class="btn btn-info pull-right">勤務時間グラフ表示</button>
                </form>
                </div>
            </div>

            <div class="row pull-right">
                <div class="pull-right">
                    <div id="workingdaybymonth"></div>
                    <div id="workingday"></div>
                    <div id="dayoff"></div>
                    <div id="thismonthworkingtime"></div>
                    <div id="worktime-sum-month"></div>
                    <div id="worktime-shortage"></div>
                    <div id="worktime-excess"></div>
                    <div id="paidHoliday"></div>
                </div>
            </div>

            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th width="4%">日付</th>
                        <th width="2%">全休</th>
                        <th width="5%">開始時間</th>
                        <th width="5%">終了時間</th>
                        <th width="5%">休憩時間(h)</th>
                        <th width="5%">深夜休憩時間(h)</th>
                        <th width="5%">勤務時間</th>
                        <th width="5%">PJ合計時間</th>
                        @for($i = 0; $i < count($workSchedulesJSON['workSchedules'][0]['projectWork']); $i++)
                        <th width="6%">
                        <select id="projectWorktime-{{ $i }}" class="form-control" name="projectIds[{{ $i }}]" disabled>
                            <option value="999" selected="selected"></option>
                            @foreach($projectsJSON as $key => $projectdata)
                            <option value="{{ $projectdata['project_id'] }}"<?php if ($projectdata['project_order'] === $i) echo ' selected'; ?>>{{ $projectdata['project_code'] . ' ' . $projectdata['project_name'] }}</option>
                            @endforeach
                        </select>
                        </th>
                        @endfor
                        <th width="22%" id="detail">内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workSchedulesJSON['workSchedules'] as $workSchedulekey => $workSchedule)
                    @if ( $workSchedule['is_holiday'] == 1)
                    <tr class="table-secondary">
                    @else
                    <tr>
                    @endif
                        <input name="workschedules[{{ $workSchedulekey }}][id]" type="hidden" value="{{ $workSchedule['work_schedule_id'] }}">
                        <input name="workschedules[{{ $workSchedulekey }}][week_number]" type="hidden" value="{{ $workSchedule['week_number'] }}">
                        <input name="workschedules[{{ $workSchedulekey }}][workdate]" type="hidden" value="{{ $workSchedule['workdate'] }}">
                        <td width="4%">{{ $workSchedule['workdate_fordisplay'] }}</td>
                        <td width="2%"><input id="is_paid_holiday-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][is_paid_holiday]" type="checkbox" value="1" <?php if ($workSchedule['is_paid_holiday'] === 1) echo 'checked="checked"'; ?> class="form-control is_paid_holiday" disabled="disabled"></td>
                        <td width="5%"><div style="display:flex;"><input id="starttime_hh-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][starttime_hh]" type="number" min="0" max="30" value="{{ $workSchedule['starttime_hh'] }}" class="form-control starttime_hh cul-worktime" style="width:60px;" readonly>：<input id="starttime_mm-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][starttime_mm]" type="number" min="0" max="45" step="15" value="{{ $workSchedule['starttime_mm'] }}" class="form-control starttime_mm cul-worktime" style="width:60px;" readonly></div></td>
                        <td width="5%"><div style="display:flex;"><input id="endtime_hh-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][endtime_hh]" type="number" min="0" max="30" value="{{ $workSchedule['endtime_hh'] }}" class="form-control endtime_hh cul-worktime" style="width:60px;" readonly>：<input id="endtime_mm-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][endtime_mm]" type="number" min="0" max="45" step="15" value="{{ $workSchedule['endtime_mm'] }}" class="form-control endtime_mm cul-worktime" style="width:60px;" readonly></div></td>
                        <td width="5%"><input id="breaktime-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][breaktime]" type="number" step="0.25" value="{{ $workSchedule['breaktime'] }}" class="form-control breaktime cul-worktime" style="width:70px;" readonly></td>
                        <td width="5%"><input id="breaktime_midnight-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][breaktime_midnight]" type="number" step="0.25" value="{{ $workSchedule['breaktime_midnight'] }}" class="form-control breaktime_midnight cul-worktime" style="width:70px;" readonly></td>
                        <td width="5%"><div id="subtime-{{ $workSchedulekey }}" class="subtime"></div></td>
                        <td width="5%"><div id="projectsumtimebyday-{{ $workSchedulekey }}" class="projectsumtimebyday"></div></td>
                        @foreach($workSchedule['projectWork'] as $projectworkkey => $projectwork)
                        <td width="6%"><input id="worktime-{{ $workSchedulekey }}-{{ $projectworkkey }}" name="workschedules[{{ $workSchedulekey }}][worktime][{{ $projectworkkey }}]" type="number" step="0.25" min="0" value="{{ $projectwork['worktime'] }}" class="form-control projecttimeaday-{{ $workSchedulekey }} worktime-{{ $projectworkkey }} cul-worktime" readonly></td>
                        @endforeach
                        <td width="22%" id="detail-body-{{ $workSchedulekey }}"><textarea id="detail-{{ $workSchedulekey }}" name="workschedules[{{ $workSchedulekey }}][detail]" rows="1" cols="40" class="form-control detail" readonly>{!! nl2br(e($workSchedule['detail'])) !!}</textarea></td>
                    </tr>
                    @endforeach
                    {{-- 合計数を出す --}}
                    <tr class="table-warning">
                        <td width="4%">合計</td>
                        <td width="2%">有給</td>
                        <td width="5%">開始時間</td>
                        <td width="5%">終了時間</td>
                        <td width="5%"><div id="sumbreaktime" class="sumbreaktime"></div></td>
                        <td width="5%"><div id="sumbreaktime_midnight" class="sumbreaktime_midnight"></div></td>
                        <td width="5%"><div id="sumworktime" class="sumworktime"></div></td>
                        <td width="5%"><div id="allprojectsumtime" class="allprojectsumtime"></div></td>
                        @foreach($workSchedule['projectWork'] as $projectworkkey => $projectwork)
                        <td width="6%"><div id="projectsumtime-{{ $projectworkkey }}" class="projectsumtime"></div></td>
                        @endforeach
                        <td width="22%" id="detail-footer">内容</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('customJS')
{{-- 変数初期化 --}}
let workScheduleCount = {{ count($workSchedulesJSON['workSchedules']) }};
let workingdaysByMonth = {{ $workSchedulesJSON['workingDay'] }};
const usertypeId = {{ $user['usertype_id'] }};
const isFluctuation = {{ config('const.workingtimeType.fluctuation.id') }};
const workingtimeType = {{ $user['workingtime_type'] }};
const worktimeDay = {{ $user['worktime_day'] }};
const maxworktimeMonth = {{ $user['maxworktime_month'] }};
const workingtimeMin = '{{ $user['workingtime_min'] }}';
const workingtimeMax = '{{ $user['workingtime_max'] }}';
const paidHoliday = {{ $user['paid_holiday'] }};
{{-- プロジェクトの件数 --}}
let projectCount = {{ count($workSchedulesJSON['workSchedules'][0]['projectWork']) }};
{{-- 月の日付 --}}
let MonthDayCount = {{ count($workSchedulesJSON['workSchedules']) }};
 

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

{{-- 勤務時間リアルタイム計算・表示関数 --}}
function culcurateSummary() {
    {{-- 変数初期化 --}}
    let worktimes = [];
    let starttime = 0;
    let starttime_hh = 0;
    let starttime_mm = 0;
    let endtime = 0;
    let endtime_hh = 0;
    let endtime_mm = 0;
    let worktimeByDayWithoutBreak = '00:00:00';
    let worktimeByDay = '00:00:00';
    let worktimeSumMonth = '00:00:00';
    let worktimeByDayForDisplay = '';
    let worktimeByDayForDisplayList = [];
    let breaktimeByDay = 0;
    let breaktimeSum = 0;
    let breaktimeMidnightByDay = 0;
    let breaktimeMidnightSum = 0;
    let breakInt = 0;
    let breakDecimal = 0;
    let breakMinute = 0;
    let breaktime = 0;
    let breakMidnightInt = 0;
    let breakMidnightDecimal = 0;
    let breakMidnightMinute = 0;
    let breaktimeMidnight = 0;
    let projecttimeSumMonth = [];
    let projecttimeSumADay = [];
    let projecttimeSumMonthForDisplay = 0;
    let workingDays = 0;
    let dayoff = 0;
    let thismonthWorkingTimeMin = 0;
    let thismonthWorkingTimeMax = 0;
    let worktimeShortageThisMonth = 0;
    let worktimeExcessThisMonth = 0;

    {{-- 開始時間取得 --}}
    $('.starttime_hh').each(function (index, element) {
        worktimes[index] = [];
        starttime_hh = $(element).val();
        if (starttime_hh == "") {
            starttime_hh = starttime_hh;
        } else {
            starttime_hh = ('00' + starttime_hh).slice(-2);
        }
        worktimes[index]['starttime'] = starttime_hh;
    });

    $('.starttime_mm').each(function (index, element) {
        starttime_mm = $(element).val();
        if (starttime_mm == "") {
            starttime_mm = starttime_mm;
        } else {
            starttime_mm = ('00' + starttime_mm).slice(-2);
        }
        if (worktimes[index]['starttime'] !== "") {
            worktimes[index]['starttime'] = worktimes[index]['starttime'] + ':' + starttime_mm + ':00';
        }
    });

    {{-- 終了時間取得 --}}
    $('.endtime_hh').each(function (index, element) {
        endtime_hh = $(element).val();
        if (endtime_hh == "") {
            endtime_hh = endtime_hh;
        } else {
            endtime_hh = ('00' + endtime_hh).slice(-2);
        }
        worktimes[index]['endtime'] = endtime_hh;
    });

    $('.endtime_mm').each(function (index, element) {
        endtime_mm = $(element).val();
        if (endtime_mm == "") {
            endtime_mm = endtime_mm;
        } else {
            endtime_mm = ('00' + endtime_mm).slice(-2);
        }
        if (worktimes[index]['endtime'] !== "") {
            worktimes[index]['endtime'] = worktimes[index]['endtime'] + ':' + endtime_mm + ':00';
        }
    });

    {{-- 合計休憩時間計算 --}}
    $('.breaktime').each(function (index, element) {
        breaktimeByDay = $(element).val();
        worktimes[index]['breaktime'] = breaktimeByDay;
        if (!breaktimeByDay) {
            breaktimeByDay = 0;
        }

        breaktimeSum = parseFloat(breaktimeSum) + parseFloat(breaktimeByDay);
    });

    {{-- 合計深夜休憩時間計算 --}}
    $('.breaktime_midnight').each(function (index, element) {
        breaktimeMidnightByDay = $(element).val();
        worktimes[index]['breaktime_midnight'] = breaktimeMidnightByDay;
        if (!breaktimeMidnightByDay) {
            breaktimeMidnightByDay = 0;
        }

        breaktimeMidnightSum = parseFloat(breaktimeMidnightSum) + parseFloat(breaktimeMidnightByDay);
    });

    {{-- 合計休憩時間表示 --}}
    $('#sumbreaktime').text(breaktimeSum);

    {{-- 合計休憩時間表示 --}}
    $('#sumbreaktime_midnight').text(breaktimeMidnightSum);

    {{-- 勤務時間・合計勤務時間計算 --}}
    $.each(worktimes, function(index, value) {
        {{-- 勤務時間を計算するために、休憩時間のフォーマットを計算できる形に変更 --}}
        if (value.breaktime.length > 0) {
            breakInt = value.breaktime.split(".")[0];
            breakDecimal = value.breaktime.split(".")[1];
            breakDecimal = (typeof breakDecimal === "undefined") ? 0 : breakDecimal;
            breakDecimal = (breakDecimal + '00').slice(0,2);
            breakMinute = 60 * parseInt(breakDecimal) / 100;

            breaktime = ('00' + breakInt).slice(-2) + ':' + ('00' + breakMinute).slice(-2) + ':00';
        } else {
            breaktime = '00:00:00';
        }

        {{-- 勤務時間を計算するために、休憩時間のフォーマットを計算できる形に変更 --}}
        if (value.breaktime_midnight.length > 0) {
            breakMidnightInt = value.breaktime_midnight.split(".")[0];
            breakMidnightDecimal = value.breaktime_midnight.split(".")[1];
            breakMidnightDecimal = (typeof breakMidnightDecimal === "undefined") ? 0 : breakMidnightDecimal;
            breakMidnightDecimal = (breakMidnightDecimal + '00').slice(0,2);
            breakMidnightMinute = 60 * parseInt(breakMidnightDecimal) / 100;

            breaktimeMidnight = ('00' + breakMidnightInt).slice(-2) + ':' + ('00' + breakMidnightMinute).slice(-2) + ':00';
        } else {
            breaktimeMidnight = '00:00:00';
        }

        worktimeByDayWithoutBreak = timeMath.sub(value.endtime, value.starttime);
        worktimeByDay = timeMath.sub(worktimeByDayWithoutBreak, breaktime);
        worktimeByDay = timeMath.sub(worktimeByDay, breaktimeMidnight);

        if (parseInt(worktimeByDay) > 0) {
            workingDays++;
        }

        worktimeSumMonth = timeMath.sum(worktimeSumMonth, worktimeByDay);

        if (typeof worktimeByDay !== "undefined") {
            worktimeByDayForDisplay =  worktimeByDay.split(":")[0] + '.' + ((100 * parseInt(worktimeByDay.split(":")[1]) / 60) + '00').slice(0,2);
        } else {
            worktimeByDayForDisplay = '0';
        }
        {{-- 一日合計勤務時間表示 --}}
        $('#subtime-' + index).text(worktimeByDayForDisplay);
        worktimeByDayForDisplayList[index] = worktimeByDayForDisplay;
    })

    {{-- 月合計勤務時間表示 --}}
    worktimeSumMonthForDisplay = worktimeSumMonth.split(":")[0] + '.' + ((100 * parseInt(worktimeSumMonth.split(":")[1]) / 60) + '00').slice(0,2);
    $('#sumworktime').text(worktimeSumMonthForDisplay);

    {{-- 1プロジェクト合計時間計算 --}}
    for (var i = 0; i < projectCount; i++) {
        projecttimeSumMonth[i] = 0;
        $('.worktime-' + i).each(function (index, element) {
            if (!$(element).val()) {
                projecttimeSumMonth[i] = parseFloat(projecttimeSumMonth[i]) + 0;
            } else {
                projecttimeSumMonth[i] = parseFloat(projecttimeSumMonth[i]) + parseFloat($(element).val());
            }
        });
        $('#projectsumtime-' + i).text(projecttimeSumMonth[i]);
    }

    {{-- 一日のプロジェクト合計時間 --}}
    for (var j = 0; j < workScheduleCount; j++) {
        projecttimeSumADay[j] = 0;
        $('.projecttimeaday-' + j).each(function (index, element) {
            if (!$(element).val()) {
                projecttimeSumADay[j] = parseFloat(projecttimeSumADay[j]) + 0;
            } else {
                projecttimeSumADay[j] = parseFloat(projecttimeSumADay[j]) + parseFloat($(element).val());
            }
        });
        $('#projectsumtimebyday-' + j).text(projecttimeSumADay[j]);

        if (worktimeByDayForDisplayList[j] < projecttimeSumADay[j]) {
            $('#projectsumtimebyday-' + j).css("color", "red");
            $('#projectsumtimebyday-' + j).css("font-weight","bold");
        } else if (worktimeByDayForDisplayList[j] > projecttimeSumADay[j]) {
            $('#projectsumtimebyday-' + j).css("color", "blue");
            $('#projectsumtimebyday-' + j).css("font-weight","bold");
        } else {
            $('#projectsumtimebyday-' + j).css("color", "black");
            $('#projectsumtimebyday-' + j).css("font-weight","normal");
        }
        {{-- 全プロジェクト合計時間計算 --}}
        projecttimeSumMonthForDisplay = parseFloat(projecttimeSumMonthForDisplay) + parseFloat(projecttimeSumADay[j]);
    }

    {{-- 全プロジェクト合計時間表示 --}}
    $('#allprojectsumtime').text(projecttimeSumMonthForDisplay);
    if (worktimeSumMonthForDisplay < projecttimeSumMonthForDisplay) {
        $('#allprojectsumtime').css("color", "red");
        $('#allprojectsumtime').css("font-weight","bold");
    } else if (worktimeSumMonthForDisplay > projecttimeSumMonthForDisplay) {
        $('#allprojectsumtime').css("color", "blue");
        $('#allprojectsumtime').css("font-weight","bold");
    } else {
        $('#allprojectsumtime').css("color", "black");
        $('#allprojectsumtime').css("font-weight","normal");
    }

    {{-- サマリー表示 --}}
    $('#workingdaybymonth').text('勤務日数：' + workingdaysByMonth + ' 日');
    $('#workingday').text('出勤日数：' + workingDays + ' 日');
    dayoff = parseInt(workingdaysByMonth) - parseInt(workingDays);
    $('#dayoff').text('欠勤日数：' + dayoff + ' 日');

    {{-- 総勤務時間の計算 --}}
    if (workingtimeType == isFluctuation) {
        thismonthWorkingTimeMin = parseInt(workingdaysByMonth) * parseInt(worktimeDay);
        thismonthWorkingTimeMax = parseInt(thismonthWorkingTimeMin) + parseInt(maxworktimeMonth);
        $('#thismonthworkingtime').text('今月の勤務時間数(1日' + worktimeDay + 'h)：下限 ' + thismonthWorkingTimeMin + 'h ～ 上限 ' + thismonthWorkingTimeMax + 'h');
    } else {
        thismonthWorkingTimeMin = parseInt(workingtimeMin);
        thismonthWorkingTimeMax = parseInt(workingtimeMax);
        $('#thismonthworkingtime').text('今月の勤務時間数(固定)：下限 ' + thismonthWorkingTimeMin + 'h ～ 上限 ' + thismonthWorkingTimeMax + 'h');
    }

    $('#worktime-sum-month').text('総勤務時間：' + worktimeSumMonthForDisplay + ' h');
    $('#paidHoliday').text('残有給日数：' + paidHoliday + ' 日');

    if (worktimeSumMonthForDisplay < thismonthWorkingTimeMin) {
        worktimeShortageThisMonth = worktimeSumMonthForDisplay - thismonthWorkingTimeMin;
        $('#worktime-shortage').text('不足時間：' + worktimeShortageThisMonth + ' h');
        $('#worktime-excess').text('超過時間：' + worktimeSumMonthForDisplay + ' h');
        $('#worktime-shortage').css("color", "blue");
        $('#worktime-shortage').css("font-weight","bold");
        $('#worktime-excess').css("color", "black");
        $('#worktime-excess').css("font-weight","normal");
    } else if (worktimeSumMonthForDisplay > thismonthWorkingTimeMax) {
        worktimeExcessThisMonth = worktimeSumMonthForDisplay - thismonthWorkingTimeMax;
        $('#worktime-shortage').text('不足時間：' + worktimeShortageThisMonth + ' h');
        $('#worktime-excess').text('超過時間：' + worktimeExcessThisMonth + ' h');
        $('#worktime-shortage').css("color", "black");
        $('#worktime-shortage').css("font-weight","normal");
        $('#worktime-excess').css("color", "red");
        $('#worktime-excess').css("font-weight","bold");
    } else {
        $('#worktime-shortage').text('不足時間：' + worktimeShortageThisMonth + ' h');
        $('#worktime-excess').text('超過時間：' + worktimeExcessThisMonth + ' h');
        $('#worktime-shortage').css("color", "black");
        $('#worktime-shortage').css("font-weight","normal");
        $('#worktime-excess').css("color", "black");
        $('#worktime-excess').css("font-weight","normal");
    }
}


{{-- プロジェクトコードの追加処理 --}}
$(document).on('change', '.projectWorktimeTitle', function () {
    var projectSelectedId = this.id;
    var projectSelectedIdSplit = projectSelectedId.split('-');
    var projectCountNow = parseInt(projectSelectedIdSplit[1]);
    var projectCountNext = parseInt(projectSelectedIdSplit[1]) + 1;

    if (projectCountNext >= projectCount) {
        {{-- ヘッダー追加 --}} {{-- ヘッダープロジェクトコードの全読み出し APIで読み出し --}}
        $.ajax({
            url: "{{ route('api.user.workschedule.getProjectAPI') }}",
            dataType: 'json',
        }).done(function(data, textStatus, jqXHR){
            {{-- ヘッダー追加 --}}
            {{-- select 追加 (内容の前に追加) --}}
            detailHeaderSelecter = $('#detail');
            detailHeaderSelecter.before('<th width="6%"><select id="projectWorktime-' + (projectCountNext) + '" class="form-control projectWorktimeTitle" name="projectIds[' + (projectCountNext) + ']"></select></th>');

            {{-- select option 追加 --}}
            projectWorktimeSelecter = $('#projectWorktime-' + projectCountNext);
            projectWorktimeSelecter.append('<option value="999" selected="selected"></option>');

            Object.keys(data).forEach(function (key) {
                projectWorktimeSelecter.append('<option value="' + (data[key].id) + '">' + (data[key].code) + ' ' + (data[key].name) + '</option>');
            })

            {{-- ボディー追加（日付分） --}} {{-- 日付件数取得、loopを回して --}}
            for (i = 0; i < MonthDayCount; i++) {
                worktimeId = 'detail-body-' + i;
                $('#' + worktimeId).before('<td width="6%"><input id="worktime-' + (i) + '-' + (projectCountNext) + '"  name="workschedules[' + (i) + '][worktime][' + (projectCountNext) + ']" type="number" step="0.25" min="0" value="0" class="form-control projecttimeaday-' + (i) + ' worktime-' + (projectCountNext) + ' cul-worktime"></td>');
            }

            {{-- フッダー追加 --}} {{-- 計算が行われるように設定 --}}
            $('#detail-footer').before('<td width="6%"><div id="projectsumtime-' + (projectCountNext) + '" class="projectsumtime"></div></td>');

            {{-- プロジェクト件数追加 --}}
            console.log('projectCount before', projectCount);
            projectCount++;
            console.log('projectCount after', projectCount);

            {{-- サマリー再計算 --}}
            culcurateSummary();
        }).fail(function(jqXHR, textStatus, errorThrown){
            swal("エラー", "開発者に問い合わせお願い致します", "warning");
        });
    } else {
        console.log('stay');
    }
});

{{-- 値が変更されたら時間再計算 --}}
$(document).on('change', '.cul-worktime', function () {
    culcurateSummary();
});

$(document).ready(function() {
    {{-- 初期表示時にサマリー計算 --}}
    culcurateSummary();

    {{-- 有給フラグが変更されたら時間再計算 --}}
    $('.is_paid_holiday').change(function () {
        paidHolidayId = this.id;
        paidHolidayIdSplit = paidHolidayId.split('-');

        if ($(this).prop('checked') == true) {
            $('#starttime_hh-' + paidHolidayIdSplit[1]).val(null);
            $('#starttime_mm-' + paidHolidayIdSplit[1]).val(null);
            $('#endtime_hh-' + paidHolidayIdSplit[1]).val(null);
            $('#endtime_mm-' + paidHolidayIdSplit[1]).val(null);
            $('#breaktime-' + paidHolidayIdSplit[1]).val(null);
            $('#breaktime_midnight-' + paidHolidayIdSplit[1]).val(null);

            $('#starttime_hh-' + paidHolidayIdSplit[1]).prop('readonly', true);
            $('#starttime_mm-' + paidHolidayIdSplit[1]).prop('readonly', true);
            $('#endtime_hh-' + paidHolidayIdSplit[1]).prop('readonly', true);
            $('#endtime_mm-' + paidHolidayIdSplit[1]).prop('readonly', true);
            $('#breaktime-' + paidHolidayIdSplit[1]).prop('readonly', true);
            $('#breaktime_midnight-' + paidHolidayIdSplit[1]).prop('readonly', true);

            for (var i = 0; i < projectCount; i++) {
                $('#worktime-' + paidHolidayIdSplit[1] + '-' + i).val(null);
                $('#worktime-' + paidHolidayIdSplit[1] + '-' + i).prop('readonly', true);
            }

            $('#detail-' + paidHolidayIdSplit[1]).prop('readonly', true);
            culcurateSummary();
        } else {
            $('#starttime_hh-' + paidHolidayIdSplit[1]).prop('readonly', false);
            $('#starttime_mm-' + paidHolidayIdSplit[1]).prop('readonly', false);
            $('#endtime_hh-' + paidHolidayIdSplit[1]).prop('readonly', false);
            $('#endtime_mm-' + paidHolidayIdSplit[1]).prop('readonly', false);
            $('#breaktime-' + paidHolidayIdSplit[1]).prop('readonly', false);
            $('#breaktime_midnight-' + paidHolidayIdSplit[1]).prop('readonly', false);
            for (var i = 0; i < projectCount; i++) {
                $('#worktime-' + paidHolidayIdSplit[1] + '-' + i).val(null);
                $('#worktime-' + paidHolidayIdSplit[1] + '-' + i).prop('readonly', false);
            }
            $('#detail-' + paidHolidayIdSplit[1]).prop('readonly', false);
        }
    });
});
@endsection
