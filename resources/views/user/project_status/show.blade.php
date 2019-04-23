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
                <div class="card-header">{{ __('PJステータス一覧') }}
                    <button id="createProjectstatus" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- PJステータス一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="10%">PJステータス名</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="workschedule-table">
                                    @foreach($projectstatuses as $key => $projectstatus)
                                    <tr>
                                        <td width="5%">{{ $projectstatus['id'] }}</td>
                                        <td width="10%">{{ $projectstatus['name'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="projectstatusId-{{ $projectstatus['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $projectstatus['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="projectstatusId-{{ $projectstatus['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $projectstatus['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- PJステータス新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJステータス 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.projectstatus.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-projectstatusId" name="projectstatusId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectstatusName">PJステータス名</label>
                        <input type="text" class="form-control" id="create-projectstatusName" name="projectstatusName">
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

{{-- PJステータス編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJステータス 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.projectstatus.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-projectstatusId" name="projectstatusId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectstatusName">PJステータス名</label>
                        <input type="text" class="form-control" id="edit-projectstatusName" name="projectstatusName">
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

{{-- PJステータス削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJステータス 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.projectstatus.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-projectstatusId" name="projectstatusId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectstatusName">PJステータス名</label>
                        <input type="text" class="form-control" id="delete-projectstatusName" name="projectstatusName" readonly>
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

        {{-- PJ区分データ --}}
        $('#create-projectstatusId').val(null);
        $('#create-projectstatusName').val(null);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var projectstatusId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.projectstatus.getProjectstatusAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                projectstatusId: projectstatusId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- PJ区分データ --}}
            projectstatusData = data[0];
            $('#edit-projectstatusId').val(projectstatusData.id);
            $('#edit-projectstatusName').val(projectstatusData.name);

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var projectstatusId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.projectstatus.getProjectstatusAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                projectstatusId: projectstatusId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- PJ区分データ --}}
            projectstatusData = data[0];
            $('#delete-projectstatusId').val(projectstatusData.id);
            $('#delete-projectstatusName').val(projectstatusData.name);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
