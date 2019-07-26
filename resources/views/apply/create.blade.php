@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <h5>添加申请</h5>
            </div>
            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a>
                <a href="{{route('apply.index')}}"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 申请列表</button></a>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                <form class="form-horizontal m-t-md" action="{{route('apply.store')}}" method="POST">
                    {!! csrf_field() !!}
                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">数据库类型：</label>
                        <div class="input-group col-sm-2">
                            <select class="form-control" name="db_type">
                                <option value="0" >请选择数据库类型</option>
                                @foreach($dbType as $key => $value)
                                    <option value="{{$value}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('db_type'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('db_type')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">请输入数据库名：</label>
                        <div class="input-group col-sm-2">
                            <input required type="text" class="form-control" name="db_name"  value="{{old('db_name')}}" data-msg-required="请输入数据库名称">
                            @if ($errors->has('db_name'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('db_name')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">请输入表名：</label>
                        <div class="input-group col-sm-2">
                            <input required type="text" class="form-control" name="table_name"  value="{{old('table_name')}}" data-msg-required="请输入表名">
                            @if ($errors->has('table_name'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('table_name')}}</span>
                            @endif
                        </div>
                    </div>


                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">变更SQL：</label>
                        <div class="input-group col-sm-2">
                            <textarea required rows="5" data-msg-required="请输入执行sql" class="form-control" name="exec_sql"></textarea>
                            @if ($errors->has('exec_sql'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('exec_sql')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                    <div class="form-group">
                        <div class="col-sm-12 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;保 存</button>
                            <button class="btn btn-white" type="reset"><i class="fa fa-repeat"></i> 重 置</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
@endsection