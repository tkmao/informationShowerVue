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
                <div class="card-header">{{ __('プロジェクト一覧') }}
                    <button id="createProject" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- プロジェクト一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="5%">コード</th>
                                        <th width="40%">プロジェクト名</th>
                                        <th width="10%">区分</th>
                                        <th width="10%">取引先企業</th>
                                        <th width="10%">ステータス</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="workschedule-table">
                                    @foreach($projects as $key => $project)
                                    <tr>
                                        <td width="5%">{{ $project['id'] }}</td>
                                        <td width="5%">{{ $project['code'] }}</td>
                                        <td width="40%">{{ $project['name'] }}</td>
                                        <td width="10%">{{ $project['category']['name'] }}</td>
                                        <td width="10%">{{ $project['company']['name'] }}</td>
                                        <td width="10%">{{ $project['projectStatus']['name'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="projectId-{{ $project['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $project['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="projectId-{{ $project['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $project['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- PJ新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.project.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-projectId" name="projectId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectCode">プロジェクトコード</label>
                        <input type="text" class="form-control col-md-3" id="create-projectCode" name="projectCode">
                    </div>
                    <div class="form-group">
                        <label for="projectName">プロジェクト名</label>
                        <input type="text" class="form-control" id="create-projectName" name="projectName">
                    </div>
                    <div class="form-group">
                        <label for="categoryId">区分</label>
                        <select class="form-control" id="create-categoryId" name="categoryId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="companyId">取引先企業</label>
                        <select class="form-control" id="create-companyId" name="companyId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="projectStatusId">ステータス</label>
                        <select class="form-control" id="create-projectStatusId" name="projectStatusId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userId">担当者</label>
                        <select class="form-control" id="create-userId" name="userId">
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

{{-- PJ編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.project.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-projectId" name="projectId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectCode">プロジェクトコード</label>
                        <input type="text" class="form-control" id="edit-projectCode" name="projectCode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="projectName">プロジェクト名</label>
                        <input type="text" class="form-control" id="edit-projectName" name="projectName">
                    </div>
                    <div class="form-group">
                        <label for="categoryId">区分</label>
                        <select class="form-control" id="edit-categoryId" name="categoryId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="companyId">取引先企業</label>
                        <select class="form-control" id="edit-companyId" name="companyId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="projectStatusId">ステータス</label>
                        <select class="form-control" id="edit-projectStatusId" name="projectStatusId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userId">担当者</label>
                        <select class="form-control" id="edit-userId" name="userId">
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

{{-- PJ削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.project.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-projectId" name="projectId" type="hidden" value="">
                    <div class="form-group">
                        <label for="projectCode">プロジェクトコード</label>
                        <input type="text" class="form-control col-md-3" id="delete-projectCode" name="projectCode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="projectName">プロジェクト名</label>
                        <input type="text" class="form-control" id="delete-projectName" name="projectName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="categoryId">区分</label>
                        <select class="form-control" id="delete-categoryId" name="categoryId" disabled>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="companyId">取引先企業</label>
                        <select class="form-control" id="delete-companyId" name="companyId" disabled>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="projectStatusId">ステータス</label>
                        <select class="form-control" id="delete-projectStatusId" name="projectStatusId" disabled>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userId">担当者</label>
                        <select class="form-control" id="delete-userId" name="userId" disabled>
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

let isSelected = false;

function changeProjectCode(categoryId) {
    switch (categoryId) {
        case '1':
            $('#create-projectCode').val('00XXX');
            break;
        case '2':
            $('#create-projectCode').val('10XXX');
            break;
        case '3':
            $('#create-projectCode').val('90XXX');
            break;
        case '4':
            $('#create-projectCode').val('91XXX');
            break;
    }
}

$(function () {
    $('#create-categoryId').change(function() {
        changeProjectCode($(this).val());
    });

    $('#createModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var submitType = 'create';
        var projectId = 0;

        $.ajax({
            url: "{{ route('api.user.project.getProjectAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                projectId: projectId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- 初期化 --}}
            isSelected = false;
            categoryId = '1';

            {{-- プロジェクトデータ --}}
            $('#create-projectId').val('');
            changeProjectCode(categoryId);
            $('#create-projectName').val('');

            {{-- カテゴリデータ --}}
            let categoryList = $('#create-categoryId');
            categoryList.empty();

            for(var i=0; i < data.categories.length; i++) {
                $option = $('<option>').val(data.categories[i].id).text(data.categories[i].name).prop('selected', isSelected);
                categoryList.append($option);
            }

            {{-- 会社データ --}}
            let companyList = $('#create-companyId');
            companyList.empty();

            for(var i=0; i < data.companies.length; i++) {
                $option = $("<option>").val(data.companies[i].id).text(data.companies[i].name).prop('selected', isSelected);
                companyList.append($option);
            }

            {{-- プロジェクトステータスデータ --}}
            let projectStatusList = $('#create-projectStatusId');
            projectStatusList.empty();

            for(var i=0; i < data.projectStatuses.length; i++) {
                $option = $("<option>").val(data.projectStatuses[i].id).text(data.projectStatuses[i].name).prop('selected', isSelected);
                projectStatusList.append($option);
            }

            {{-- ユーザデータ --}}
            let userList = $('#create-userId');
            userList.empty();

            for(var i=0; i < data.users.length; i++) {
                $option = $("<option>").val(data.users[i].id).text(data.users[i].name).prop('selected', isSelected);
                userList.append($option);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var projectId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.project.getProjectAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                projectId: projectId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- プロジェクトデータ --}}
            projectData = data.project[0];
            $('#edit-projectId').val(projectData.id);
            $('#edit-projectCode').val(projectData.code);
            $('#edit-projectName').val(projectData.name);
            
            {{-- カテゴリデータ --}}
            let categoryList = $('#edit-categoryId');
            categoryList.empty();

            for(var i=0; i < data.categories.length; i++) {
                isSelected = false;
                if (data.categories[i].id == projectData.category_id) {
                    isSelected = true;
                }
                $option = $('<option>').val(data.categories[i].id).text(data.categories[i].name).prop('selected', isSelected);
                categoryList.append($option);
            }

            {{-- 会社データ --}}
            let companyList = $('#edit-companyId');
            companyList.empty();

            for(var i=0; i < data.companies.length; i++) {
                isSelected = false;
                if (data.companies[i].id == projectData.company_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.companies[i].id).text(data.companies[i].name).prop('selected', isSelected);
                companyList.append($option);
            }

            {{-- プロジェクトステータスデータ --}}
            let projectStatusList = $('#edit-projectStatusId');
            projectStatusList.empty();

            for(var i=0; i < data.projectStatuses.length; i++) {
                isSelected = false;
                if (data.projectStatuses[i].id == projectData.status_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.projectStatuses[i].id).text(data.projectStatuses[i].name).prop('selected', isSelected);
                projectStatusList.append($option);
            }

            {{-- ユーザデータ --}}
            let userList = $('#edit-userId');
            userList.empty();

            for(var i=0; i < data.users.length; i++) {
                isSelected = false;
                if (data.users[i].id == projectData.user_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.users[i].id).text(data.users[i].name).prop('selected', isSelected);
                userList.append($option);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var projectId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.project.getProjectAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                projectId: projectId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- プロジェクトデータ --}}
            projectData = data.project[0];
            $('#delete-projectId').val(projectData.id);
            $('#delete-projectCode').val(projectData.code);
            $('#delete-projectName').val(projectData.name);
            
            {{-- カテゴリデータ --}}
            let categoryList = $('#delete-categoryId');
            categoryList.empty();

            for(var i=0; i < data.categories.length; i++) {
                isSelected = false;
                if (data.categories[i].id == projectData.category_id) {
                    isSelected = true;
                }
                $option = $('<option>').val(data.categories[i].id).text(data.categories[i].name).prop('selected', isSelected);
                categoryList.append($option);
            }

            {{-- 会社データ --}}
            let companyList = $('#delete-companyId');
            companyList.empty();

            for(var i=0; i < data.companies.length; i++) {
                isSelected = false;
                if (data.companies[i].id == projectData.company_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.companies[i].id).text(data.companies[i].name).prop('selected', isSelected);
                companyList.append($option);
            }

            {{-- プロジェクトステータスデータ --}}
            let projectStatusList = $('#delete-projectStatusId');
            projectStatusList.empty();

            for(var i=0; i < data.projectStatuses.length; i++) {
                isSelected = false;
                if (data.projectStatuses[i].id == projectData.status_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.projectStatuses[i].id).text(data.projectStatuses[i].name).prop('selected', isSelected);
                projectStatusList.append($option);
            }

            {{-- ユーザデータ --}}
            let userList = $('#delete-userId');
            userList.empty();

            for(var i=0; i < data.users.length; i++) {
                isSelected = false;
                if (data.users[i].id == projectData.user_id) {
                    isSelected = true;
                }
                $option = $("<option>").val(data.users[i].id).text(data.users[i].name).prop('selected', isSelected);
                userList.append($option);
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
