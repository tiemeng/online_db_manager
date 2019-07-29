@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title form-inline">
                <form action="">
                    <div class="form-group col-sm-4">
                        <label for="name" class="">数据库名：</label>
                        <input type="text" class="form-control" name="db_name" value="@if(isset($search['db_name'])){{$search['db_name']}}@endif" placeholder="请输入数据库名">
                    </div>
                    <div class="form-group col-sm-4" >
                        <label for="key" class="">表名：</label>

                        <input type="text" class="form-control" name="table_name" value="@if(isset($search['table_name'])){{$search['table_name']}}@endif" placeholder="请输入表名">
                    </div>
                    <input type="submit" class="btn btn-info" value="查询">
                </form>
            </div>

            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
                <a href="{{route('datachange.create')}}" link-url="javascript:void(0)"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 添加申请</button></a>
                <table class="table table-striped table-bordered table-hover m-t-md">
                    <thead>
                    <tr>
                        <th>ID </th>
                        <th class="text-center">数据库类型</th>
                        <th class="text-center">数据库名</th>
                        <th>表名</th>
                        <th>变更SQL</th>
                        <th>申请人</th>
                        <th>审核人</th>
                        <th>建立时间</th>
                        <th class="text-center" width="100">状态</th>
                        <th class="text-center" width="150">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $key => $item)
                        <tr>
                            <td class="text-center" >{{$item->id}}</td>
                            <td class="text-center" >{{$item->db_type}}</td>
                            <td class="text-center">{{$item->db_name}}</td>
                            <td>{{$item->table_name}}</td>
                            <td title="点击查看详情" data-backdrop="false"  data-toggle="modal" data-target="#myModal{{$item->id}}">
                                {{mb_substr($item->exc_sql,0,40)}}

                            </td>
                            <td>{{$item->apply_user}}</td>
                            <td>{{$item->audit_user}}</td>
                            <td>{{$item->created_at}}</td>
                            <td class="text-center">
                                <span class="text-navy status">{{$status[$item->status]}}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if($item->status == 1)
                                        {{--<a href="{{route('datachange.status',['status'=>2,$item['id']])}}">--}}
                                            <button onclick="audit({{$item['id']}},2)" data-id="{{$item['id']}}" data-status="2" class="btn btn-info btn-xs " type="button"> 通过</button>
                                        {{--</a>--}}
                                        {{--<a href="{{route('datachange.status',['status'=>3,$item->id])}}">--}}
                                            <button onclick="audit({{$item['id']}},3)" data-id="{{$item['id']}}" data-status="2" class="btn btn-danger btn-xs" type="button"> 驳回</button>
                                        {{--</a>--}}
                                        <a href="{{route('datachange.edit',['id' => $item->id])}}"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-paste"></i> 编辑</button></a>
                                        </a>
                                    @elseif($item->status==2)
                                        {{--<a href="{{route('datachange.status',['status'=>4,$item->id])}}">--}}
                                            <button class="btn btn-info btn-xs" onclick="exec({{$item->id}})" id="exec" type="button"> 执行</button>
                                        {{--</a>--}}
                                    @endif

                                </div>
                            </td>
                        </tr>

                            <div class="modal inmodal fade" id="myModal{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 5px">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="false">&times;</span><span class="sr-only">Close</span>
                                            </button>
                                            <h4 class="modal-title">待执行sql</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                {!! nl2br($item->exc_sql) !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right pagination m-t-no">
            <div class="text-center">
                {{$list->links()}}
            </div>
            <div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <script src="{{loadEdition('/js/jquery.min.js')}}"></script>
    <script>
        function audit(id,status){
            $.post('{{route("datachange.status")}}',{id:id,status:status,_token:'{{csrf_token()}}'},function(res){
                if(res.code == 200){
                    // webSocket.send(JSON.stringify({
                    //     'message': "审核通过",
                    //     'type': 'chat'
                    // }));
                    location.reload();

                }else{
                    layer.msg(res.msg,{time:3000},function(){
                        location.reload();
                    });
                }
            },"json");
        }

        function exec(id){
            $.post('{{route('datachange.exec')}}',{id:id,_token:'{{csrf_token()}}'},function(res){
                if(res.code == 200){
                    location.reload();
                }else{
                    layer.msg(res.msg,{time:3000},function(){
                        location.reload();
                    });
                }
            },"json")
        }
        // $(function(){
        //     $("#exec").click(function(){
        //         webSocket.send(JSON.stringify({
        //             'message': "执行sql中",
        //             'type': 'chat'
        //         }));
        //     });
        // })
    </script>
    {{--<script src="{{loadEdition('/js/plugins/layer/layer.min.js')}}"></script>--}}
@endsection

