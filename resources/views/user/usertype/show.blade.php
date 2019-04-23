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
                <div class="card-header">{{ __('ユーザタイプ一覧') }}
                    <button id="createProjectstatus" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- ユーザタイプ一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="10%">ユーザタイプ名</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="workschedule-table">
                                    @foreach($usertypes as $key => $usertype)
                                    <tr>
                                        <td width="5%">{{ $usertype['id'] }}</td>
                                        <td width="10%">{{ $usertype['name'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="usertypeId-{{ $usertype['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $usertype['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="usertypeId-{{ $usertype['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $usertype['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- ユーザタイプ新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザタイプ 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.usertype.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-usertypeId" name="usertypeId" type="hidden" value="">
                    <div class="form-group">
                        <label for="usertypeName">ユーザタイプ名</label>
                        <input type="text" class="form-control" id="create-usertypeName" name="usertypeName">
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

{{-- ユーザタイプ編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザタイプ 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.usertype.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-usertypeId" name="usertypeId" type="hidden" value="">
                    <div class="form-group">
                        <label for="usertypeName">ユーザタイプ名</label>
                        <input type="text" class="form-control" id="edit-usertypeName" name="usertypeName">
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

{{-- ユーザタイプ削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ユーザタイプ 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.usertype.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-usertypeId" name="usertypeId" type="hidden" value="">
                    <div class="form-group">
                        <label for="usertypeName">ユーザタイプ名</label>
                        <input type="text" class="form-control" id="delete-usertypeName" name="usertypeName" readonly>
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

        {{-- ユーザタイプデータ --}}
        $('#create-usertypeId').val(null);
        $('#create-usertypeName').val(null);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);      // モーダル切替えボタン
        var usertypeId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.usertype.getUserTypeAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                usertypeId: usertypeId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- ユーザタイプデータ --}}
            usertypeData = data[0];
            $('#edit-usertypeId').val(usertypeData.id);
            $('#edit-usertypeName').val(usertypeData.name);

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);      // モーダル切替えボタン
        var usertypeId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.usertype.getUserTypeAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                usertypeId: usertypeId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- ユーザタイプデータ --}}
            usertypeData = data[0];
            $('#delete-usertypeId').val(usertypeData.id);
            $('#delete-usertypeName').val(usertypeData.name);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
