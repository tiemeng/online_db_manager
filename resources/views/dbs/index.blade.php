@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="ibox-title form-inline">
                <form action="">
                    <div class="form-group col-sm-4">
                        <label class="control-label">连接名</label>
                        <select name="conn_name" class="form-control">
                            @foreach($conns as $conn)
                                <option @if($conn->conn_name == $conn_name) selected @endif value="{{$conn->conn_name}}">{{$conn->conn_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="name" class="">数据库名称：</label>
                        <input type="text" class="form-control" name="schema_name" value="{{$search['schema_name']??""}}" placeholder="请输入数据库名称">
                    </div>
                    <input type="submit" class="btn btn-info" value="查询">
                </form>
            </div>

            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
                <table class="table table-striped table-bordered table-hover m-t-md">
                    <thead>
                    <tr>
                        <th class="text-center">ID </th>
                        <th class="text-center">数据库名</th>
                        <th class="text-center">字符集</th>
                        <th class="text-center">校验字符集</th>
                        <th class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dbs as $key => $item)
                        <tr>
                            <td  class="text-center" >{{$key+1}}</td>
                            <td >{{$item->SCHEMA_NAME}}</td>
                            <td>{{$item->DEFAULT_CHARACTER_SET_NAME}}</td>
                            <td>{{$item->DEFAULT_COLLATION_NAME}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('dbs.tables',['db' => $item->SCHEMA_NAME,'conn_name'=>$conn_name])}}"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-paste"></i> 查看表结构</button></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right pagination m-t-no">
            <div class="text-center">
                {{$dbs->links()}}
            </div>
            <div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

@endsection
