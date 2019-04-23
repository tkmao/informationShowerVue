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
            <div class="card">
                <div class="card-header">{{ __('週報作成') }}</div>

                <div class="card-body">
                    {{-- 週報情報を取得 --}}
                    <input id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                    <input id="weekly_report_id" name="weekly_report_id" type="hidden" value="">
                    <input id="is_subumited" name="is_subumited" type="hidden" value="">
                    {{-- 週報の提出情報を表示 --}}
                    <div id="subbmited-success" class="row" style="display: none;">
                        <div class="col-md-2"></div>
                        <div class="col-md-6 alert alert-success alert-dissmissible">
                            <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <div>週報は提出済みです</div>
                        </div>
                    </div>
                    <div id="subbmited-warning" class="row" style="display: none;">
                        <div class="col-md-2"></div>
                        <div class="col-md-6 alert alert-warning alert-dissmissible">
                            <button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <div>週報は未提出です</div>
                        </div>
                    </div>

                    {{-- ユーザ名 --}}
                    <div class="form-group row">
                        <label for="project_id" class="col-md-2 col-form-label text-md-right">{{ __('ユーザ名 ') }}</label>
                        <div class="col-md-6">
                            <div class="col-xs-3">
                                <p>{{ $userName }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 対象週を表示 --}}
                    <div class="form-group row">
                        <label for="weeknumber" class="col-md-2 col-form-label text-md-right">{{ __('対象週 ') }}</label>
                        <div class="col-md-6">
                            <div class="col-xs-3">
                                <select id="targetweek" class="form-control col-md-10 col-form-label text-md-left" name="targetweek">
                                    @foreach ($targetWeeks as $key => $value)
                                    <option value="{{ $key }}"<?php if ($key == $targetWeekNumber) echo ' selected'; ?>>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- プロジェクト情報の設定 --}}
                    <div class="form-group row">
                        <label for="project_id" class="col-md-2 col-form-label text-md-right">{{ __('案件内容 ') }}</label>
                        <div class="col-md-6">
                            <div class="col-xs-3">
                                <select class="form-control" id="project_id" name="project_id" disabled>
                                    <option value="{{ config('const.undefineProjectId') }}" selected="selected"></option>
                                    @foreach ($projects as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 対象週の勤務表表示 --}}
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('今週の作業') }}</label>
                        <div class="col-md-8">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="10%">日付</th>
                                        <th width="15%">開始時間</th>
                                        <th width="15%">終了時間</th>
                                        <th width="15%">休憩時間(h)</th>
                                        <th width="15%">勤務時間(h)</th>
                                        <th width="30%">内容</th>
                                    </tr>
                                </thead>
                                <tbody id="workschedule-table">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 各入力項目 --}}
                    <div class="form-group row">
                        <label for="nextweek_schedule" class="col-md-2 col-form-label text-md-right">{{ __('来週の作業') }}</label>
                        <div class="col-md-8">
                            <textarea id="nextweek_schedule" name="nextweek_schedule" rows="5" cols="100" class="form-control" disabled></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="thismonth_dayoff" class="col-md-2 col-form-label text-md-right">{{ __('今月の休暇') }}</label>
                        <div class="col-md-8">
                            <textarea id="thismonth_dayoff" name="thismonth_dayoff" rows="5" cols="100" class="form-control" disabled></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="site_information" class="col-md-2 col-form-label text-md-right">{{ __('現場情報') }}</label>
                        <div class="col-md-8">
                            <textarea id="site_information" name="site_information" rows="5" cols="100" class="form-control" disabled></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="opinion" class="col-md-2 col-form-label text-md-right">{{ __('所感') }}</label>
                        <div class="col-md-8">
                            <textarea id="opinion" name="opinion" rows="5" cols="100" class="form-control" disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('customJS')
{{-- 変数初期化 --}}
let targetWeekNumber = {{ $targetWeekNumber }};
let userId = {{ $userId }};
let undefineProjectId = {{ config('const.undefineProjectId') }};

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
    let breakInt = 0;
    let breakDecimal = 0;
    let breakMinute = 0;
    let breaktime = 0;
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

    {{-- 合計休憩時間表示 --}}
    $('#sumbreaktime').text(breaktimeSum);

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

        worktimeByDayWithoutBreak = timeMath.sub(value.endtime, value.starttime);
        worktimeByDay = timeMath.sub(worktimeByDayWithoutBreak, breaktime);

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
}

{{-- データ取得用API --}}
function getWeeklyReport(targetWeekNumber) {

    $.ajax({
        url: "{{ route('api.user.weeklyreport.getWeeklyReportAPI') }}",
        dataType: 'json',
        data: {
            userId: userId,
            targetWeek: targetWeekNumber
        },
    }).done(function(data, textStatus, jqXHR){
        $('#weekly_report_id').val(data.weekly_report_id);
        $('#is_subumited').val(data.is_subumited);

        {{-- 週報を登録済みかをチェック --}}
        if (typeof data.is_subumited === 'undefined' || data.is_subumited == 0) {
            $('#subbmited-success').hide();
            $('#subbmited-warning').show();
        } else {
            $('#subbmited-success').show();
            $('#subbmited-warning').hide();
        }

        {{-- 週報のプロジェクトコードの指定 --}}
        if (typeof data.project_id === 'undefined') {
            $("#project_id").val(undefineProjectId);
        } else {
            $("#project_id").val(data.project_id);
        }

        {{-- 週報情報を設定 --}}
        $('textarea[id="nextweek_schedule"]').val(data.nextweek_schedule);
        $('textarea[id="thismonth_dayoff"]').val(data.thismonth_dayoff);
        $('textarea[id="site_information"]').val(data.site_information);
        $('textarea[id="opinion"]').val(data.opinion);

        {{-- 勤務表の情報を再登録 --}}
        $('#workschedule-table').empty();

        for (let i=0; i<data.workSchedules.length; i++) {
            var trid = data.workSchedules[i].workdate;

            $('#workschedule-table').append('<tr id="' + (data.workSchedules[i].workdate) + '"></tr>');
            if (data.workSchedules[i].is_holiday == 1) {
                $('#' + trid).addClass("table-secondary");
            }

            $('#' + trid).append('<input name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][id]" type="hidden" value="' + (data.workSchedules[i].work_schedule_id) + '">');
            $('#' + trid).append('<input name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][week_number]" type="hidden" value="' + (data.workSchedules[i].week_number) + '">');
            $('#' + trid).append('<input name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][workdate]" type="hidden" value="' + (data.workSchedules[i].workdate) + '">');
            $('#' + trid).append('<td width="10%">' + (data.workSchedules[i].workdate_fordisplay) + '</td>');
            $('#' + trid).append('<td width="15%"><div style="display:flex;"><input id="starttime_hh-' + i + '" name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][starttime_hh]" type="number" value="' + (data.workSchedules[i].starttime_hh) + '" class="form-control starttime_hh cul-worktime" style="width:60px;" disabled>：<input id="starttime_mm-' + i + '" name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][starttime_mm]" type="number" value="' + (data.workSchedules[i].starttime_mm) + '" class="form-control starttime_mm cul-worktime" style="width:60px;" disabled></div></td>');
            $('#' + trid).append('<td width="15%"><div style="display:flex;"><input id="endtime_hh-' + i + '" name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][endtime_hh]" type="number" value="' + (data.workSchedules[i].endtime_hh) + '" class="form-control endtime_hh cul-worktime" style="width:60px;" disabled>：<input id="endtime_mm-' + i + '" name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][endtime_mm]" type="number" value="' + (data.workSchedules[i].endtime_mm) + '" class="form-control endtime_mm cul-worktime" style="width:60px;" disabled></div></td>');
            $('#' + trid).append('<td width="15%"><input id="breaktime-' + i + '" name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][breaktime]" type="number" step="0.25" value="' + (data.workSchedules[i].breaktime) + '" class="form-control breaktime cul-worktime" style="width:70px;" disabled></td>');
            $('#' + trid).append('<td width="15%"><div id="subtime-' + i + '" class="subtime"></div></td>');
            $('#' + trid).append('<td width="30%"><textarea name="workschedules[' + (data.workSchedules[i].work_schedule_id) + '][detail]" rows="1" cols="40" disabled>' + (data.workSchedules[i].detail) + '</textarea></td>');
        }

        {{-- サマリーの表示 --}}
        $('#workschedule-table').append('<tr class="table-warning"></tr>');

        $('.table-warning').append('<td width="10%">合計</td>');
        $('.table-warning').append('<td width="15%">開始時間</td>');
        $('.table-warning').append('<td width="15%">終了時間</td>');
        $('.table-warning').append('<td width="15%"><div id="sumbreaktime" class="sumbreaktime"></div></td>');
        $('.table-warning').append('<td width="15%"><div id="sumworktime" class="sumworktime"></div></td>');
        $('.table-warning').append('<td width="30%"></td>');

        {{-- APIでデータを取得したら時間再計算 --}}
        culcurateSummary();
    }).fail(function(jqXHR, textStatus, errorThrown){
        swal("エラー", "開発者に問い合わせお願い致します", "warning");
    });
}

{{-- 対象週が変更された場合に実行 --}}
$(function() {
    $('#targetweek').change(function() {
        targetWeekNumber = $("#targetweek").val();
        getWeeklyReport(targetWeekNumber);
    });
});

$(document).ready(function() {
    {{-- 描画終了後にデータ取得 --}}
    getWeeklyReport(targetWeekNumber);
});

@endsection
