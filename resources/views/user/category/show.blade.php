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
                <div class="card-header">{{ __('PJ区分一覧') }}
                    <button id="createCategory" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- PJ区分一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="10%">PJ区分名</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="category-table">
                                    @foreach($categories as $key => $category)
                                    <tr>
                                        <td width="5%">{{ $category['id'] }}</td>
                                        <td width="10%">{{ $category['name'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="categoryId-{{ $category['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $category['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="categoryId-{{ $category['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $category['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- PJ区分新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ区分 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.category.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-categoryId" name="categoryId" type="hidden" value="">
                    <div class="form-group">
                        <label for="categoryName">PJ区分名</label>
                        <input type="text" class="form-control" id="create-categoryName" name="categoryName">
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

{{-- PJ区分編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ区分 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.category.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-categoryId" name="categoryId" type="hidden" value="">
                    <div class="form-group">
                        <label for="categoryName">PJ区分名</label>
                        <input type="text" class="form-control" id="edit-categoryName" name="categoryName">
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

{{-- PJ区分削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PJ区分 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.category.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-categoryId" name="categoryId" type="hidden" value="">
                    <div class="form-group">
                        <label for="categoryName">PJ区分名</label>
                        <input type="text" class="form-control" id="delete-categoryName" name="categoryName" readonly>
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
        $('#create-categoryId').val(null);
        $('#create-categoryName').val(null);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var categoryId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.category.getCategoryAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                categoryId: categoryId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- PJ区分データ --}}
            categoryData = data[0];
            $('#edit-categoryId').val(categoryData.id);
            $('#edit-categoryName').val(categoryData.name);

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var categoryId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.category.getCategoryAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                categoryId: categoryId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- PJ区分データ --}}
            categoryData = data[0];
            $('#delete-categoryId').val(categoryData.id);
            $('#delete-categoryName').val(categoryData.name);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
