    @extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title form-inline">
                <form action="{{route('dbconn.index')}}" method="get" >
                    <div class="form-group col-sm-pull-2">
                        <label class="control-label">驱动类型</label>
                        <select name="driver" class="form-control">
                            <option value="">请选择</option>
                            @foreach($driverType as $value)
                                <option @if($driver == $value) selected @endif value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="btn btn-info" value="查询">
                </form>
            </div>
            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
                <a href="{{route('dbconn.create')}}" link-url="javascript:void(0)"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 新增连接</button></a>
                <table class="table table-striped table-bordered table-hover m-t-md">
                    <thead>
                    <tr>
                        <th class="text-center" width="100">ID</th>
                        <th class="text-center" >连接名</th>
                        <th class="text-center" >驱动类型</th>
                        <th class="text-center" >用户名</th>
                        <th class="text-center" >端口</th>
                        <th class="text-center" >主机地址</th>
                        <th class="text-center" >数据库名</th>
                        <th class="text-center" >操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="text-center" > {{$item->id}} </td>
                            <td class="text-center" >{{$item->conn_name}}</td>
                            <td class="text-center" >{{$item->driver}}</td>
                            <td class="text-center" >{{$item->username}}</td>
                            <td class="text-center" >{{$item->port}}</td>
                            <td class="text-center" >{{$item->host}}</td>
                            <td class="text-center" >{{$item->database}}</td>
                            <td class="text-center">
                                <a href="{{route('dbconn.edit',['id' => $item->id])}}"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-paste"></i> 编辑</button></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    <div class="pull-right pagination m-t-no">
                        <div class="text-center">
                            {{$list->links()}}
                        </div>
                        <div>
                        </div>
                    </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection