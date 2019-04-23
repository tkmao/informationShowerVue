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
                <div class="card-header">{{ __('企業一覧') }}
                    <button id="createCompany" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">新規作成</button>
                </div>
                <div class="card-body">
                    {{-- 企業一覧表示 --}}
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="5%">ID</th>
                                        <th width="10%">企業名</th>
                                        <th width="10%">郵便番号</th>
                                        <th width="35%">住所</th>
                                        <th width="10%">電話番号</th>
                                        <th width="10%">fax</th>
                                        <th width="10%">編集</th>
                                        <th width="10%">削除</th>
                                    </tr>
                                </thead>
                                <tbody id="company-table">
                                    @foreach($companies as $key => $company)
                                    <tr>
                                        <td width="5%">{{ $company['id'] }}</td>
                                        <td width="10%">{{ $company['name'] }}</td>
                                        <td width="10%">{{ $company['zipcode'] }}</td>
                                        <td width="35%">{{ $company['address'] }}</td>
                                        <td width="10%">{{ $company['phone'] }}</td>
                                        <td width="10%">{{ $company['fax'] }}</td>
                                        <td width="10%">
                                            {{-- 編集用モーダル --}}
                                            <button id="companyId-{{ $company['id'] }}" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-whatever="{{ $company['id'] }}" data-target="#editModal">修正</button>
                                        </td>
                                        <td width="10%">
                                            {{-- 削除用モーダル --}}
                                            <button id="companyId-{{ $company['id'] }}" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-whatever="{{ $company['id'] }}" data-target="#deleteModal">削除</button>
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

{{-- 企業新規作成のモーダル --}}
<div class="modal" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">企業 新規作成画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.company.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="create-companyId" name="companyId" type="hidden" value="">
                    <div class="form-group">
                        <label for="companyName">企業名</label>
                        <input type="text" class="form-control" id="create-companyName" name="companyName">
                    </div>
                    <div class="form-group">
                        <label for="zipcode">郵便番号</label>
                        <input type="text" class="form-control" id="create-zipcode" name="zipcode">
                    </div>
                    <div class="form-group">
                        <label for="address">住所</label>
                        <input type="text" class="form-control" id="create-address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="phone">電話番号</label>
                        <input type="text" class="form-control" id="create-phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="fax">FAX番号</label>
                        <input type="text" class="form-control" id="create-fax" name="fax">
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

{{-- 企業編集のモーダル --}}
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">企業 編集画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.company.edit') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="edit-companyId" name="companyId" type="hidden" value="">
                    <div class="form-group">
                        <label for="companyName">企業名</label>
                        <input type="text" class="form-control" id="edit-companyName" name="companyName">
                    </div>
                    <div class="form-group">
                        <label for="zipcode">郵便番号</label>
                        <input type="text" class="form-control" id="edit-zipcode" name="zipcode">
                    </div>
                    <div class="form-group">
                        <label for="address">住所</label>
                        <input type="text" class="form-control" id="edit-address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="phone">電話番号</label>
                        <input type="text" class="form-control" id="edit-phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="fax">FAX番号</label>
                        <input type="text" class="form-control" id="edit-fax" name="fax">
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

{{-- 企業削除のモーダル --}}
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">企業 削除画面</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('user.company.delete') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input id="delete-companyId" name="companyId" type="hidden" value="">
                    <div class="form-group">
                        <label for="companyName">企業名</label>
                        <input type="text" class="form-control" id="delete-companyName" name="companyName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="zipcode">郵便番号</label>
                        <input type="text" class="form-control" id="delete-zipcode" name="zipcode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="address">住所</label>
                        <input type="text" class="form-control" id="delete-address" name="address" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">電話番号</label>
                        <input type="text" class="form-control" id="delete-phone" name="phone" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fax">FAX番号</label>
                        <input type="text" class="form-control" id="delete-fax" name="fax" readonly>
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

        {{-- 企業データ --}}
        $('#create-companyId').val(null);
        $('#create-companyName').val(null);
        $('#create-zipcode').val(null);
        $('#create-address').val(null);
        $('#create-phone').val(null);
        $('#create-fax').val(null);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var companyId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'edit';

        $.ajax({
            url: "{{ route('api.user.company.getCompanyAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                companyId: companyId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- 企業データ --}}
            companyData = data[0];
            $('#edit-companyId').val(companyData.id);
            $('#edit-companyName').val(companyData.name);
            $('#edit-zipcode').val(companyData.zipcode);
            $('#edit-address').val(companyData.address);
            $('#edit-phone').val(companyData.phone);
            $('#edit-fax').val(companyData.fax);

        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // モーダル切替えボタン
        var companyId = button.data('whatever'); // data-* 属性から情報を抽出
        var submitType = 'delete';

        $.ajax({
            url: "{{ route('api.user.company.getCompanyAPI') }}",
            dataType: 'json',
            data: {
                submitType: submitType,
                companyId: companyId
            },
        }).done(function(data, textStatus, jqXHR){
            {{-- 企業データ --}}
            companyData = data[0];
            $('#delete-companyId').val(companyData.id);
            $('#delete-companyName').val(companyData.name);
            $('#delete-zipcode').val(companyData.zipcode);
            $('#delete-address').val(companyData.address);
            $('#delete-phone').val(companyData.phone);
            $('#delete-fax').val(companyData.fax);
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log('test2');
        });
    });
});

$(document).ready(function() {
    console.log('testtest');
});
@endsection
