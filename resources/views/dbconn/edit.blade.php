@extends('layouts.layout')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-title">
            <h5>修改申请</h5>
        </div>
        <div class="ibox-content">
            <a href="{{route('dbconn.index')}}">
                <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 连接列表
                </button>
            </a>
            <div class="hr-line-dashed m-t-sm m-b-sm"></div>
            <form class="form-horizontal m-t-md" action="{{route('dbconn.update',$info->id)}}" method="POST">
                <div class="form-group">
                    <label class="col-sm-2 control-label">驱动类型：</label>
                    <div class="col-sm-2">
                        <select name="driver" class="form-control">
                            <option value="">请选择</option>
                            @foreach($driver as $value)
                                <option @if($value==$info['driver']) selected @endif value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('driver'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('driver')}}</span>
                        @endif
                    </div>
                </div>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">连接名：</label>
                    <div class="col-sm-3">
                        <input type="text" name="conn_name" value="{{$info->conn_name}}" class="form-control" required data-msg-required="请输入连接名">
                        @if ($errors->has('conn_name'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('conn_name')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">主机地址：</label>
                    <div class="col-sm-3">
                        <input type="text" name="host" value="{{$info->host}}" class="form-control" required data-msg-required="请输入主机地址">
                        @if ($errors->has('host'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('host')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">端口：</label>
                    <div class="col-sm-3">
                        <input type="text" name="port" value="{{$info->port}}" class="form-control" required data-msg-required="请输入端口号">
                        @if ($errors->has('port'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('port')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">数据库名：</label>
                    <div class="col-sm-3">
                        <input type="text" name="database" value="{{$info->database}}" class="form-control" required data-msg-required="请输入数据库名">
                        @if ($errors->has('database'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('database')}}</span>
                        @endif
                    </div>
                </div>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">用户名：</label>
                    <div class="col-sm-3">
                        <input type="text" name="username" value="{{$info->username}}" class="form-control" required data-msg-required="请输入用户名">
                        @if ($errors->has('username'))
                            <span class="help-block m-b-none" style="color: red"><i class="fa fa-info-circle"></i>{{$errors->first('username')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码：</label>
                    <div class="col-sm-3">
                        <input type="text" name="password" value="{{$info->password}}" class="form-control" required data-msg-required="请输入密码">
                        @if ($errors->has('password'))
                            <span class="help-block m-b-none" style="color: red"><i class="fa fa-info-circle"></i>{{$errors->first('password')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">字符集：</label>
                    <div class="col-sm-3">
                        <input type="text" name="charset" value="{{$info->charset}}" class="form-control" required data-msg-required="请输入字符集">
                        @if ($errors->has('charset'))
                            <span class="help-block m-b-none" style="color: red"><i class="fa fa-info-circle"></i>{{$errors->first('charset')}}</span>
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
                {{method_field('PATCH')}}
                {{csrf_field()}}
            </form>
        </div>
    </div>
</div>

@endsection
