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
                <div class="card-header">{{ __('祝日一覧') }}
                    <button id="createHoliday" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- 祝日一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-10">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="10%">日付</th>
                                        <th width="20%">祝日名</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="holiday-table">
                                    @foreach($holidays as $key => $holiday)
                                    <tr>
                                        <td width="5%">{{ $holiday['id'] }}</td>
                                        <td width="10%">{{ $holiday['date'] }}</td>
                                        <td width="20%">{{ $holiday['name'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="holidayId-{{ $holiday['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $holiday['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="holidayId-{{ $holiday['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $holiday['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- 祝日新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">祝日 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.holiday.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-holidayId" name="holidayId" type="hidden" value="">
                    <div class="form-group">
                        <label for="holidayDate">日付</label>
                        <input type="date" class="form-control" id="create-holidayDate" name="holidayDate">
                    </div>
                    <div class="form-group">
                        <label for="holidayName">祝日名</label>
                        <input type="text" class="form-control" id="create-holidayName" name="holidayName">
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

{{-- 祝日編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">祝日 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.holiday.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-holidayId" name="holidayId" type="hidden" value="">
                    <div class="form-group">
                        <label for="holidayDate">日付</label>
                        <input type="date" class="form-control" id="edit-holidayDate" name="holidayDate">
                    </div>
                    <div class="form-group">
                        <label for="holidayName">祝日名</label>
                        <input type="text" class="form-control" id="edit-holidayName" name="holidayName">
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

{{-- 祝日削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">祝日 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.holiday.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-holidayId" name="holidayId" type="hidden" value="">
                    <div class="form-group">
                        <label for="holidayDate">日付</label>
                        <input type="date" class="form-control" id="delete-holidayDate" name="holidayDate" readonly>
                    </div>
                    <div class="form-group">
                        <label for="holidayName">祝日名</label>
                        <input type="text" class="form-control" id="delete-holidayName" name="holidayName" readonly>
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

$(function () {
    $('#createModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var submitType = 'create';

        {{-- 祝日データ --}}
        $('#create-holidayId').val(null);
        $('#create-holidayDate').val(null);
        $('#create-holidayName').val(null);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var holidayId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.holiday.getHolidayAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                holidayId: holidayId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- 祝日データ --}}
            holidayData = data[0];
            $('#edit-holidayId').val(holidayData.id);
            $('#edit-holidayDate').val(holidayData.date);
            $('#edit-holidayName').val(holidayData.name);

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var holidayId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.holiday.getHolidayAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                holidayId: holidayId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- 祝日データ --}}
            holidayData = data[0];
            $('#delete-holidayId').val(holidayData.id);
            $('#delete-holidayDate').val(holidayData.date);
            $('#delete-holidayName').val(holidayData.name);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
