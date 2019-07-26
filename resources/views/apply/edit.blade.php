@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <h5>修改平台</h5>
            </div>
            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a>
                <a href="{{route('platforms.index')}}"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 平台列表</button></a>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                <form class="form-horizontal m-t-md" action="{{route('platforms.update',$platforms->id)}}" method="post">
                    {{method_field('PUT')}}
                    {!! csrf_field() !!}
                    <input type="hidden" class="form-control" name="id"  value="{{old('id',$platforms->id)}}">
                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">公司名称：</label>
                        <div class="input-group col-sm-2">
                            <input type="text" class="form-control" name="company_name"  value="{{old('company_name',$platforms->company_name)}}" data-msg-required="请输入平台名称">
                            {{method_field('PUT')}}
                            @if ($errors->has('company_name'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('company_name')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">关联平台：</label>
                        <div class="input-group col-sm-2">
                            <select class="form-control" name="merchant_id">
                                <option value="0" >请选择关联商户</option>
                                @foreach($merchants as $key => $item)
                                    <option @if(old('merchant_id',$platforms->merchant_id) == $item['merchant_id']) selected="selected" @endif                value="{{$item['merchant_id']}}"  >{{$item['name']}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">请输入平台名称：</label>
                        <div class="input-group col-sm-2">
                            <input type="text" class="form-control" name="name"  value="{{old('name',$platforms->name)}}" data-msg-required="请输入平台名称">
                            @if ($errors->has('name'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('name')}}</span>
                            @endif
                        </div>
                    </div>


                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">通知 url：</label>
                        <div class="input-group col-sm-2">
                            <input type="text" class="form-control" name="notify_url"  value="{{old('notify_url',$platforms->notify_url)}}"  data-msg-required="请输入通知 url">
                            @if ($errors->has('notify_url'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('notify_url')}}</span>
                            @endif
                        </div>
                    </div>


                    <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">状态：</label>
                        <div class="input-group col-sm-1">
                            <select class="form-control" name="status">
                                <option value="1" @if(old('status',$platforms->status) == 1) selected="selected" @endif>启用</option>
                                <option value="2" @if(old('status',$platforms->status) == 2) selected="selected" @endif>禁用</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('status')}}</span>
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