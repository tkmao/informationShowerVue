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

            {{-- 対象週を表示 --}}
            <div class="row pull-left">
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

            <div class="p-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#weeklyReport" class="nav-link active" data-toggle="tab">週報内容</a>
                    </li>
                    <li class="nav-item">
                        <a href="#weeklyWorkSummary" class="nav-link" data-toggle="tab">勤務時間内容</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="weeklyReport">
                        <p id="weeklyReport-employee"></p>
                        <p id="weeklyReport-submitcount"></p>

                        <div class="row pull-left">
                            <table id="weeklyreportlist-table" class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="10%">ユーザ名</th>
                                        <th width="15%">プロジェクト名</th>
                                        <th width="15%">来週の作業</th>
                                        <th width="10%">今月の休暇</th>
                                        <th width="20%">現場情報</th>
                                        <th width="20%">所感</th>
                                        <th width="10%">提出状況</th>
                                    </tr>
                                </thead>
                                <tbody id="weeklyreportlist-tablebody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="weeklyWorkSummary">
                        <p id="weeklyWorkSummary-employee"></p>
                        <p id="weeklyWorkSummary-submitcount"></p>
                        <p id="weeklyWorkSummary-basicworkingday"></p>
                        <p id="weeklyWorkSummary-shortagecount"></p>
                        <p id="weeklyWorkSummary-overtimecount"></p>

                        <div class="row pull-left">
                            <table id="weeklyWorkSummary-table" class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="10%">ユーザ名</th>
                                        <th width="10%">勤務時間グラフ</th>
                                        <th width="15%">勤務時間<br>(当月累計)</th>
                                        <th width="10%">基本勤務時間</th>
                                        <th width="10%">当月残り勤務時間</th>
                                        <th width="10%">超過時間<br>(当月累計)</th>
                                        <th width="10%">出勤日数<br>(当月累計)</th>
                                        <th width="10%">欠勤日数<br>(当月累計)</th>
                                        <th width="10%">超過日数<br>(当月累計)</th>
                                        <th width="10%">提出状況</th>
                                    </tr>
                                </thead>
                                <tbody id="weeklyWorkSummary-tablebody">
                                </tbody>
                            </table>
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
const isFluctuation = {{ config('const.workingtimeType.fluctuation.id') }};
let targetWeekNumber = {{ $targetWeekNumber }};
let undefineProjectId = {{ config('const.undefineProjectId') }};
let employee = 0;
let workingDayAWeek = 0;
let shortageCount = 0;
let overtimeCount = 0;
let submitCount = 0;
let workdate = '';
let isGetWorkdate = false;

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
function getWeeklyReportSummary(targetWeekNumber) {

    $.ajax({
        url: "{{ route('api.user.weeklyreportanalyze.getWeeklyReportSummaryAPI') }}",
        dataType: 'json',
        data: {
            targetWeek: targetWeekNumber
        },
    }).done(function(data, textStatus, jqXHR){
        {{-- 初期化 --}}
        shortageCount = 0;
        overtimeCount = 0;
        submitCount = 0;
        workdate = '';
        isGetWorkdate = false;

        {{-- 全社員数 --}}
        employee = Object.keys(data).length;

        {{-- 勤務表の情報を再登録 --}}
        $('#weeklyreportlist-tablebody').empty();
        $('#weeklyWorkSummary-tablebody').empty();

        {{-- データ表示 --}}
        Object.keys(data).forEach(function (key) {
            {{-- 初期化 --}}
            workingDayAWeek = 0;
            dayoffCount = 0;
            projectCode = null;
            projectName = null;
            nextweekSchedule = null;
            thismonthDayoff = null;
            siteInformation = null;
            opinion = null;

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

            {{-- 週報情報のセレクタ設定 --}}
            weeklyReport = data[key].weeklyReport;

            {{-- 週報があるかないか --}}
            if (weeklyReport === null) {
                {{-- 週報の提出状況 --}}
                isSubmited = false;
            } else {
                {{-- 週報内容 --}}
                projectCode = weeklyReport.project_code;
                projectName = weeklyReport.project_name;
                nextweekSchedule = weeklyReport.nextweek_schedule;
                thismonthDayoff = weeklyReport.thismonth_dayoff;
                siteInformation = weeklyReport.site_information;
                opinion = weeklyReport.opinion;

                {{-- 週報の提出状況 --}}
                if (weeklyReport.is_subumited == true) {
                    {{-- 週報提出済み --}}
                    isSubmited = true;
                } else {
                    {{-- 週報未提出 --}}
                    isSubmited = false;
                }
            }

            {{-- 勤務時間計算 --}}
            Object.keys(data[key].workSchedule.workSchedules).forEach(function(index) {
                {{-- セレクタ設定 --}}
                workSchedule = data[key].workSchedule.workSchedules[index];

                {{-- 日付取得チャート用 --}}
                if (!isGetWorkdate) {
                    workdate = workSchedule.workdate;
                    isGetWorkdate = true;
                }

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
            {{-- 勤務時間表示 --}}
            worktimeByWeek = parseFloat(worktimeByWeekForDisplay);
            {{-- 超過日数表示（今週の勤務日数と比較） --}}
            if (workingDayAWeek > workDayByWeek) {
                holidayWork  = workingDayAWeek - workDayByWeek;
            } else {
                holidayWork  = 0;
            }

            {{-- 当月の残り時間・超過時間表示 --}}
            if (basicWorktimeMin > worktimeByWeek) {
                {{-- 当月の残り勤務時間・超過時間 --}}
                shortagetime = basicWorktimeMin - worktimeByWeek;
                overtime = 0;
                {{-- 当月の残り勤務時間の人数カウント --}}
                shortageCount++;
            } else if (basicWorktimeMax < worktimeByWeek) {
                {{-- 当月の残り勤務時間・超過時間 --}}
                shortagetime = 0;
                overtime = worktimeByWeek - basicWorktimeMax;
                {{-- 超過時間の人数カウント --}}
                overtimeCount++;
            } else {
                {{-- 当月の残り勤務時間・超過時間 --}}
                shortagetime = 0;
                overtime = 0;
            }
            {{-- 提出状況表示 --}}
            if (isSubmited) {
                submitCount++;
            }

            {{-- trタグ作成 --}}
            var trid = data[key].user_id;
            weeklyreportlistTag = 'weeklyreportlist-' + trid;
            weeklyWorkSummaryTag = 'weeklyWorkSummary-' + trid;
            
            $('#weeklyreportlist-tablebody').append('<tr id="' + (weeklyreportlistTag) + '"></tr>');
            $('#weeklyWorkSummary-tablebody').append('<tr id="' + (weeklyWorkSummaryTag) + '"></tr>');

            {{-- 週報内容 tdタグ作成 --}}
            $('#' + weeklyreportlistTag).append('<td width="10%"><a href="/weeklyreport/show/' + (data[key].user_id) + '/' + (parseInt(targetWeekNumber)) + '">' + data[key].user_name + '</a></td>'); // ユーザ
            $('#' + weeklyreportlistTag).append('<td width="15%">' + (projectName) + '</td>'); // プロジェクト名
            $('#' + weeklyreportlistTag).append('<td width="15%">' + (nextweekSchedule) + '</td>'); // 来週の作業
            $('#' + weeklyreportlistTag).append('<td width="10%">' + (thismonthDayoff) + '</td>'); // 今月の休暇
            $('#' + weeklyreportlistTag).append('<td width="20%">' + (siteInformation) + '</td>'); // 現場情報
            $('#' + weeklyreportlistTag).append('<td width="20%">' + (opinion) + '</td>'); // 所感

            {{-- 勤務時間内容 tdタグ作成 --}}
            $('#' + weeklyWorkSummaryTag).append('<td width="10%"><a href="/weeklyreport/show/' + (data[key].user_id) + '/' + (parseInt(targetWeekNumber)) + '">' + data[key].user_name + '</a></td>'); // ユーザ
            $('#' + weeklyWorkSummaryTag).append('<td width="10%"><a href="/chart/' + (data[key].user_id) + '/' + (workdate) + '"> グラフ</a></td>'); // 勤務時間グラフ
            $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + worktimeByWeekForDisplay + ' h' + '</td>'); // 勤務時間
            $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (basicWorktimeMin) + ' 〜 ' + (basicWorktimeMax) + ' h</td>'); // 当月の基本勤務時間
            if (shortagetime > 0) {
                $('#' + weeklyWorkSummaryTag).append('<td width="10%"><font color="blue">' + (shortagetime) + ' h' + '</font></td>'); // 当月の残り勤務時間
                $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (overtime) + ' h' + '</td>'); // 超過時間
            } else if (overtime > 0) {
                $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (shortagetime) + ' h' + '</td>'); // 当月の残り勤務時間
                $('#' + weeklyWorkSummaryTag).append('<td width="10%"><font color="red">' + (overtime) + ' h' + '</font></td>'); // 超過時間
            } else {
                $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (shortagetime) + ' h' + '</td>'); // 当月の残り勤務時間
                $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (overtime) + ' h' + '</td>'); // 超過時間
            }
            $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (workingDayAWeek) + ' 日' + '</td>'); // 出勤日数
            $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (dayoffCount) + ' 日' + '</td>'); // 欠勤日数
            $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + (holidayWork) + ' 日' + '</td>'); // 超過日数
            if (isSubmited) {
                $('#' + weeklyreportlistTag).append('<td width="10%">' + '提出済' + '</td>'); // 提出状況
                $('#' + weeklyWorkSummaryTag).append('<td width="10%">' + '提出済' + '</td>'); // 提出状況
            } else {
                $('#' + weeklyreportlistTag).append('<td width="10%"><font color="red">' + '未提出' + '</font></td>'); // 提出状況
                $('#' + weeklyWorkSummaryTag).append('<td width="10%"><font color="red">' + '未提出' + '</font></td>'); // 提出状況
            }
        });

        {{--
            weeklyreportTable = jQuery('#weeklyreportlist-table').DataTable();
            weeklyWorkTable = jQuery('#weeklyWorkSummary-table').DataTable();
            weeklyreportTable.row.add( {
                "user_name":        data[key].user_name,
                "projectName":      projectName,
                "nextweekSchedule": nextweekSchedule,
                "thismonthDayoff":  thismonthDayoff,
                "siteInformation":  siteInformation,
                "opinion":          opinion
            }).draw();

            if (isSubmited) {
                weeklyWorkTable.row.add( {
                    "user_name":        data[key].user_name,
                    "worktimeByWeek":   worktimeByWeekForDisplay,
                    "workingDayAWeek":  workingDayAWeek,
                    "dayoffCount":      dayoffCount,
                    "holidayWork":      holidayWork,
                    "basicWorktimeMin": basicWorktimeMin,
                    "basicWorktimeMax": basicWorktimeMax,
                    "shortagetime":     shortagetime,
                    "overtime":         overtime,
                    "isSubmited":       "提出済"
                }).draw();
            } else {
                weeklyWorkTable.row.add( {
                    "user_name":        data[key].user_name,
                    "worktimeByWeek":   worktimeByWeekForDisplay,
                    "workingDayAWeek":  workingDayAWeek,
                    "dayoffCount":      dayoffCount,
                    "holidayWork":      holidayWork,
                    "basicWorktimeMin": basicWorktimeMin,
                    "basicWorktimeMax": basicWorktimeMax,
                    "shortagetime":     shortagetime,
                    "overtime":         overtime,
                    "isSubmited":       "未提出"
                }).draw();
            }
        -- }}


        {{-- サマリー表示 --}}
        $('#weeklyReport-employee').text('社員人数：' + employee + ' 人');
        $('#weeklyReport-submitcount').text('週報提出人数：' + submitCount + ' 人');
         
        $('#weeklyWorkSummary-employee').text('社員人数：' + employee + ' 人');
        $('#weeklyWorkSummary-submitcount').text('週報提出人数：' + submitCount + ' 人');
        $('#weeklyWorkSummary-basicworkingday').text('基本出勤日数：' + workDayByWeek + ' 人');
        $('#weeklyWorkSummary-shortagecount').text('不足時間人数：' + shortageCount + ' 人');
        $('#weeklyWorkSummary-overtimecount').text('超過時間人数：' + overtimeCount + ' 人');
    }).fail(function(jqXHR, textStatus, errorThrown){
        console.log('test2');
    });
}

{{-- 対象週が変更された場合に実行 --}}
$(function() {
    $('#targetweek').change(function() {
        targetWeekNumber = $("#targetweek").val();
        getWeeklyReportSummary(targetWeekNumber);
    });
});

$(document).ready(function() {
    {{-- 描画終了後にデータ取得 --}}
    getWeeklyReportSummary(targetWeekNumber);
});

{{--
$(document).ready(function ($) {
    $('#weeklyreportlist-table').DataTable();
    $('#weeklyWorkSummary-table').DataTable();
});
--}}
@endsection
