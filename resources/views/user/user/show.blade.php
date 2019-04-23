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
                <div class="card-header">{{ __('ユーザ一覧') }}
                    <button id="createUser" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- ユーザ一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="3%">ID</th>
                                        <th width="10%">ユーザ名</th>
                                        <th width="10%">Email</th>
                                        <th width="10%">ユーザタイプ</th>
                                        <th width="12%">勤務形態</th>
                                        <th width="5%">一日の勤務時間</th>
                                        <th width="5%">月の上限勤務時間</th>
                                        <th width="10%">勤務時間下限</th>
                                        <th width="10%">勤務時間上限</th>
                                        <th width="5%">残有給日数</th>
                                        <th width="5%">入社日</th>
                                        <th width="10%">権限</th>
                                        <th width="5%">編集</th>
                                        <th width="5%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="user-table">
                                    @foreach($users as $key => $user)
                                    <tr>
                                        <td width="3%">{{ $user['id'] }}</td>
                                        <td width="10%">{{ $user['name'] }}</td>
                                        <td width="10%">{{ $user['email'] }}</td>
                                        <td width="10%">{{ $user['userType']['name'] }}</td>
                                        @if ( $user['workingtime_type'] == config('const.workingtimeType.fluctuation.id'))
                                        <td width="12%">{{ config('const.workingtimeType.fluctuation.text') }}</td>
                                        @else
                                        <td width="12%">{{ config('const.workingtimeType.fix.text') }}</td>
                                        @endif
                                        <td width="5%">{{ $user['worktime_day'] }}</td>
                                        <td width="5%">{{ $user['maxworktime_month'] }}</td>
                                        <td width="10%">{{ $user['workingtime_min'] }}</td>
                                        <td width="10%">{{ $user['workingtime_max'] }}</td>
                                        <td width="5%">{{ $user['paid_holiday'] }}</td>
                                        <td width="5%">{{ $user['hiredate'] }}</td>
                                        @if ( $user['is_admin'] == 0)
                                        <td width="10%">{{ config('const.is_admin.general') }}</td>
                                        @else
                                        <td width="10%">{{ config('const.is_admin.is_admin') }}</td>
                                        @endif
                                        <td width="5%">
                                            {{-- 編集用モーダル --}}
                                            <button id="userId-{{ $user['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $user['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="5%">
                                            {{-- 削除用モーダル --}}
                                            <button id="userId-{{ $user['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $user['id'] }}" data-target="#deleteModal">削除</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ユーザ新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザ 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.user.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-userId" name="userId" type="hidden" value="">
                    <div class="form-group">
                        <label for="userName">ユーザ名</label>
                        <input type="text" class="form-control" id="create-userName" name="userName">
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" class="form-control" id="create-userEmail" name="userEmail">
                    </div>
                    <div class="form-group">
                        <label for="userPassword">パスワード</label>
                        <input type="password" class="form-control" id="create-userPassword" name="userPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="userPasswordConfirm">パスワード（確認）</label>
                        <input type="password" class="form-control" id="create-userPasswordConfirm" name="userPasswordConfirm" required>
                    </div>
                    <div class="form-group">
                        <label for="userTypeId">ユーザタイプ</label>
                        <select class="form-control" id="create-userTypeId" name="userTypeId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="workingtimeType">勤務形態</label><br>
                        <label for="create-workingtimeType1">
                            <input type="radio" id="create-workingtimeType1" name="workingtimeType" value="1">{{ config('const.workingtimeType.fluctuation.text') }}
                        </label><br>
                        <label for="create-workingtimeType2">
                            <input type="radio" id="create-workingtimeType2" name="workingtimeType" value="2">{{ config('const.workingtimeType.fix.text') }}
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="worktimeDay">一日の勤務時間</label>
                        <input type="number" min="0" class="form-control" id="create-worktimeDay" name="worktimeDay">
                    </div>
                    <div class="form-group">
                        <label for="maxWorktimeMonth">月の上限勤務時間(上限値を記載、例：基本勤務時間から、+40時間が上限であれば、「40」を入力)</label>
                        <input type="number" min="0" class="form-control" id="create-maxWorktimeMonth" name="maxWorktimeMonth">
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMin">勤務時間下限</label>
                        <input type="number" min="0" class="form-control" id="create-workingtimeMin" name="workingtimeMin">
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMax">勤務時間上限</label>
                        <input type="number" min="0" class="form-control" id="create-workingtimeMax" name="workingtimeMax">
                    </div>
                    <div class="form-group">
                        <label for="paidHoliday">有給日数</label>
                        <input type="number" min="0" class="form-control" id="create-paidHoliday" name="paidHoliday">
                    </div>
                    <div class="form-group">
                        <label for="hiredate">入社日</label>
                        <input type="date" class="form-control" id="create-hiredate" name="hiredate">
                    </div>
                    <div class="form-group">
                        <label for="userIsAdmin">権限</label>
                        <select class="form-control" id="create-userIsAdmin" name="userIsAdmin">
                            <option value="0" selected>{{ config('const.is_admin.general') }}</option>
                            <option value="1">{{ config('const.is_admin.is_admin') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">保存</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ユーザ編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザ 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.user.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-userId" name="userId" type="hidden" value="">
                    <div class="form-group">
                        <label for="userName">ユーザ名</label>
                        <input type="text" class="form-control" id="edit-userName" name="userName">
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" class="form-control" id="edit-userEmail" name="userEmail">
                    </div>
                    <div class="form-group">
                        <label for="userTypeId">ユーザタイプ</label>
                        <select class="form-control" id="edit-userTypeId" name="userTypeId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="workingtimeType">勤務形態</label><br>
                        <label for="edit-workingtimeType1">
                            <input type="radio" id="edit-workingtimeType1" name="workingtimeType" value="1">{{ config('const.workingtimeType.fluctuation.text') }}
                        </label><br>
                        <label for="edit-workingtimeType2">
                            <input type="radio" id="edit-workingtimeType2" name="workingtimeType" value="2">{{ config('const.workingtimeType.fix.text') }}
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="worktimeDay">一日の勤務時間</label>
                        <input type="number" min="0" class="form-control" id="edit-worktimeDay" name="worktimeDay">
                    </div>
                    <div class="form-group">
                        <label for="maxWorktimeMonth">月の上限勤務時間(上限値を記載、例：基本勤務時間から、+40時間が上限であれば、「40」を入力)</label>
                        <input type="number" min="0" class="form-control" id="edit-maxWorktimeMonth" name="maxWorktimeMonth">
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMin">勤務時間下限</label>
                        <input type="number" min="0" class="form-control" id="edit-workingtimeMin" name="workingtimeMin">
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMax">勤務時間上限</label>
                        <input type="number" min="0" class="form-control" id="edit-workingtimeMax" name="workingtimeMax">
                    </div>
                    <div class="form-group">
                        <label for="paidHoliday">有給日数</label>
                        <input type="number" min="0" class="form-control" id="edit-paidHoliday" name="paidHoliday">
                    </div>
                    <div class="form-group">
                        <label for="hiredate">入社日</label>
                        <input type="date" class="form-control" id="edit-hiredate" name="hiredate">
                    </div>
                    <div class="form-group">
                        <label for="userIsAdmin">権限</label>
                        <select class="form-control" id="edit-userIsAdmin" name="userIsAdmin">
                            <option value="0">{{ config('const.is_admin.general') }}</option>
                            <option value="1">{{ config('const.is_admin.is_admin') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">保存</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ユーザ削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザ 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.user.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-userId" name="userId" type="hidden" value="">
                    <div class="form-group">
                        <label for="userName">ユーザ名</label>
                        <input type="text" class="form-control" id="delete-userName" name="userName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" class="form-control" id="delete-userEmail" name="userEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label for="userTypeId">ユーザタイプ</label>
                        <select class="form-control" id="delete-userTypeId" name="userTypeId" disabled>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="workingtimeType">勤務形態</label><br>
                        <label for="delete-workingtimeType1">
                            <input type="radio" id="delete-workingtimeType1" name="workingtimeType" value="1">{{ config('const.workingtimeType.fluctuation.text') }}
                        </label><br>
                        <label for="delete-workingtimeType2">
                            <input type="radio" id="delete-workingtimeType2" name="workingtimeType" value="2">{{ config('const.workingtimeType.fix.text') }}
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="worktimeDay">一日の勤務時間</label>
                        <input type="number" min="0" class="form-control" id="delete-worktimeDay" name="worktimeDay" readonly>
                    </div>
                    <div class="form-group">
                        <label for="maxWorktimeMonth">月の上限勤務時間(上限値を記載、例：基本勤務時間から、+40時間が上限であれば、「40」を入力)</label>
                        <input type="number" min="0" class="form-control" id="delete-maxWorktimeMonth" name="maxWorktimeMonth" readonly>
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMin">勤務時間下限</label>
                        <input type="number" min="0" class="form-control" id="delete-workingtimeMin" name="workingtimeMin" readonly>
                    </div>
                    <div class="form-group">
                        <label for="workingtimeMax">勤務時間上限</label>
                        <input type="number" min="0" class="form-control" id="delete-workingtimeMax" name="workingtimeMax" readonly>
                    </div>
                    <div class="form-group">
                        <label for="paidHoliday">有給日数</label>
                        <input type="number" min="0" class="form-control" id="delete-paidHoliday" name="paidHoliday" readonly>
                    </div>
                    <div class="form-group">
                        <label for="hiredate">入社日</label>
                        <input type="date" class="form-control" id="delete-hiredate" name="hiredate" readonly>
                    </div>
                    <div class="form-group">
                        <label for="userIsAdmin">権限</label>
                        <select class="form-control" id="delete-userIsAdmin" name="userIsAdmin" disabled>
                            <option value="0">{{ config('const.is_admin.general') }}</option>
                            <option value="1">{{ config('const.is_admin.is_admin') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">削除</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('customJS')
{{-- グローバル変数 --}}
const isFluctuation = {{ config('const.workingtimeType.fluctuation.id') }};
const isFix = {{ config('const.workingtimeType.fix.id') }};
const isNotAdmin = 0;
const defaultWorktimeDay = 8;
const defaultMaxWorktimeMonth = 20;
const defaultWorktimeMin = 160;
const defaultWorktimeMax = 180;

{{-- 勤務時間上限・下限入力フォーム変更関数 --}}
function changeWorkingtimeForm(execType, workingtimeType) {
    let worktimeDay = $('#' + execType + '-worktimeDay');
    let maxWorktimeMonth = $('#' + execType + '-maxWorktimeMonth');
    let worktimeMin = $('#' + execType + '-workingtimeMin');
    let worktimeMax = $('#' + execType + '-workingtimeMax');

    if (workingtimeType == isFluctuation) {
        {{-- 一日の勤務時間、月の上限勤務時間フォーム変更 --}}
        if (worktimeDay.val() == '') {
            worktimeDay.val(defaultWorktimeDay);
        }
        if (maxWorktimeMonth.val() == '') {
            maxWorktimeMonth.val(defaultMaxWorktimeMonth);
        }
        worktimeDay.prop('readonly', false);
        maxWorktimeMonth.prop('readonly', false);
        worktimeDay.prop('required', true);
        maxWorktimeMonth.prop('required', true);

        {{-- 勤務時間上限・下限入力フォーム変更 --}}
        worktimeMin.prop('readonly', true);
        worktimeMax.prop('readonly', true);
        worktimeMin.prop('required', false);
        worktimeMax.prop('required', false);
    } else {
        {{-- 一日の勤務時間、月の上限勤務時間フォーム変更 --}}
        worktimeDay.prop('readonly', true);
        maxWorktimeMonth.prop('readonly', true);
        worktimeDay.prop('required', false);
        maxWorktimeMonth.prop('required', false);

        {{-- 勤務時間上限・下限入力フォーム変更 --}}
        if (worktimeMin.val() == '' && worktimeMax.val() == '') {
            worktimeMin.val(defaultWorktimeMin);
            worktimeMax.val(defaultWorktimeMax);
        }
        worktimeMin.prop('readonly', false);
        worktimeMax.prop('readonly', false);
        worktimeMin.prop('required', true);
        worktimeMax.prop('required', true);
    }
}

$(function () {
    {{-- 勤務形態を変更した際の処理 --}}
    $('input[name="workingtimeType"]:radio').change( function() {
        thisId = $(this).attr("id");
        thisIdName = thisId.split("-");
        let radioval = $(this).val();
        if(radioval == isFluctuation){
            $("input[name='workingtimeType']").prop('checked',false);
            $('input[name="workingtimeType"][value=' + radioval + ']').prop('checked',true);
            changeWorkingtimeForm(thisIdName[0], isFluctuation);
        }else{
            $("input[name='workingtimeType']").prop('checked',false);
            $('input[name="workingtimeType"][value=' + radioval + ']').prop('checked',true);
            changeWorkingtimeForm(thisIdName[0], isFix);
        }
    }); 

    $('#createModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var submitType = 'create';

        $.ajax({
            url: "{{ route('api.user.user.getUserAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                userId: 0
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- ユーザデータ --}}
            $('#create-userId').val(null);
            $('#create-userName').val(null);
            $('#create-userEmail').val(null);
            $('#create-paidHoliday').val(null);
            $('#create-hiredate').val(null);
            $('#create-userIsAdmin').val(isNotAdmin);

            let userTypeList = $('#create-userTypeId');
            userTypeList.empty();

            for(var i=0; i < data.userType.length; i++) {
                isSelected = false;
                $option = $("<option>").val(data.userType[i].id).text(data.userType[i].name).prop('selected', isSelected);
                userTypeList.append($option);
            }
            $("input[name='workingtimeType']").prop('checked',false);
            $("input[name='workingtimeType'][value='1']").prop('checked',true);
            $('#create-worktimeDay').val(defaultWorktimeDay);
            $('#create-maxWorktimeMonth').val(defaultMaxWorktimeMonth);
            $('#create-workingtimeMin').val(null);
            $('#create-workingtimeMax').val(null);
            $('#create-workingtimeMin').prop('readonly', true);
            $('#create-workingtimeMax').prop('readonly', true);
            $('#create-workingtimeMin').prop('required', false);
            $('#create-workingtimeMax').prop('required', false);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var userId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.user.getUserAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                userId: userId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- ユーザデータ --}}
            userData = data.user[0];
            $('#edit-userId').val(userData.id);
            $('#edit-userName').val(userData.name);
            $('#edit-userEmail').val(userData.email);
            $('#edit-hiredate').val(userData.hiredate);

            let userTypeList = $('#edit-userTypeId');
            userTypeList.empty();

            for(var i=0; i < data.userType.length; i++) {
                isSelected = false;
                if (data.userType[i].id == userData.usertype_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.userType[i].id).text(data.userType[i].name).prop('selected', isSelected);
                userTypeList.append($option);
            }

            if (userData.workingtime_type == isFluctuation) {
                $("input[name='workingtimeType']").prop('checked',false);
                $('input[name="workingtimeType"][value=' + (userData.workingtime_type) + ']').prop('checked',true);

                {{-- 一日の勤務時間、月の上限勤務時間フォーム変更 --}}
                $('#edit-worktimeDay').val(userData.worktime_day);
                $('#edit-maxWorktimeMonth').val(userData.maxworktime_month);

                $('#edit-worktimeDay').prop('readonly', false);
                $('#edit-worktimeDay').prop('required', true);

                $('#edit-maxWorktimeMonth').prop('readonly', false);
                $('#edit-maxWorktimeMonth').prop('required', true);

                {{-- 勤務時間上限・下限入力フォーム変更 --}}
                $('#edit-workingtimeMin').val('');
                $('#edit-workingtimeMax').val('');
                $('#edit-workingtimeMin').val(userData.workingtime_min);
                $('#edit-workingtimeMax').val(userData.workingtime_max);
                $('#edit-workingtimeMin').prop('readonly', true);
                $('#edit-workingtimeMax').prop('readonly', true);
                $('#edit-workingtimeMin').prop('required', false);
                $('#edit-workingtimeMax').prop('required', false);
            } else {
                $("input[name='workingtimeType']").prop('checked',false);
                $('input[name="workingtimeType"][value=' + (userData.workingtime_type) + ']').prop('checked',true);

                {{-- 一日の勤務時間、月の上限勤務時間フォーム変更 --}}
                $('#edit-worktimeDay').val(userData.worktime_day);
                $('#edit-maxWorktimeMonth').val(userData.maxworktime_month);

                $('#edit-worktimeDay').prop('readonly', true);
                $('#edit-worktimeDay').prop('required', false);

                $('#edit-maxWorktimeMonth').prop('readonly', true);
                $('#edit-maxWorktimeMonth').prop('required', false);

                {{-- 勤務時間上限・下限入力フォーム変更 --}}
                $('#edit-workingtimeMin').val('');
                $('#edit-workingtimeMax').val('');
                $('#edit-workingtimeMin').val(userData.workingtime_min);
                $('#edit-workingtimeMax').val(userData.workingtime_max);
                $('#edit-workingtimeMin').prop('readonly', false);
                $('#edit-workingtimeMax').prop('readonly', false);
                $('#edit-workingtimeMin').prop('required', true);
                $('#edit-workingtimeMax').prop('required', true);
            }

            $('#edit-paidHoliday').val(userData.paid_holiday);
            $('#edit-userIsAdmin').val(userData.is_admin);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var userId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.user.getUserAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                userId: userId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- ユーザデータ --}}
            userData = data.user[0];
            $('#delete-userId').val(userData.id);
            $('#delete-userName').val(userData.name);
            $('#delete-userEmail').val(userData.email);
            $('#delete-paidHoliday').val(userData.paid_holiday);
            $('#delete-userIsAdmin').val(userData.is_admin);
            $('input[name="workingtimeType"][value=' + (userData.workingtime_type) + ']').prop('checked',true);
            $('#delete-worktimeDay').val(userData.worktime_day);
            $('#delete-maxWorktimeMonth').val(userData.maxworktime_month);
            $('#delete-workingtimeMin').val(userData.workingtime_min);
            $('#delete-workingtimeMax').val(userData.workingtime_max);
            $('#delete-hiredate').val(userData.hiredate);

            let userTypeList = $('#delete-userTypeId');
            userTypeList.empty();

            for(var i=0; i < data.userType.length; i++) {
                isSelected = false;
                if (data.userType[i].id == userData.usertype_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.userType[i].id).text(data.userType[i].name).prop('selected', isSelected);
                userTypeList.append($option);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
