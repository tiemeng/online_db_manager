@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="ibox-title form-inline">
                <form action="">
                    <div class="form-group col-sm-4">
                        <label for="name" class="">平台名称：</label>
                        <input type="text" class="form-control" name="name" value="{{$search['name']}}" placeholder="请输入平台名称">
                    </div>
                    <div class="form-group col-sm-4" >
                        <label for="key" class="">key：</label>

                        <input type="text" class="form-control" name="key" value="{{$search['key']}}" placeholder="请输入平台 key">
                    </div>
                    <input type="submit" class="btn btn-info" value="查询">
                </form>
            </div>

            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
                <a href="{{route('apply.create')}}" link-url="javascript:void(0)"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 添加平台</button></a>
                <table class="table table-striped table-bordered table-hover m-t-md">
                    <thead>
                    <tr>
                        <th>ID </th>
                        <th>数据库类型</th>
                        <th>数据库名</th>
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
                            <td  class="text-center" >{{$item->id}}</td>
                            <td  class="text-center" >{{$item->db_type}}</td>
                            <td>{{$item->db_name}}</td>
                            <td>{{$item->table_name}}</td>
                            <td>{{$item->exc_sql}}</td>
                            <td>{{$item->apply_id}}</td>
                            <td>{{$item->audit_id}}</td>
                            <td>{{$item->created_at}}</td>
                            <td class="text-center">
                                @if($item->status == 1)
                                    <span data-status="{{$item->status}}" data-id="{{$item->id}}" class="text-navy status">启用</span>
                                @else
                                    <span data-status="{{$item->status}}" data-id="{{$item->id}}" class="text-danger status">禁用</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('apply.edit',['id' => $item->id])}}"><button class="btn btn-primary btn-xs" type="button"><i class="fa fa-paste"></i> 编辑</button></a>
                                    <a href="javascript:0"><button  class="btn btn-danger btn-xs btn-delete"  data-id="{{$item->id}}" type="submit"><i class="fa fa-trash-o"></i> 删除</button>
                                        </a>
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
                {{$list->links()}}
            </div>
            <div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <script src="{{loadEdition('/js/jquery.min.js')}}"></script>
    <script src="{{loadEdition('/js/plugins/layer/layer.min.js')}}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.btn-delete').click(function(){
                var id = $(this).data('id');
                layer.confirm('确定删除吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    axios.delete('/platforms/delete/' + id)
                            .then(function (response) {
                                layer.msg(response.data.msg);
                                // 请求成功之后重新加载页面
                                location.reload();
                            })
                });
            });


            $('.status').click(function(){
                var id = $(this).data('id');
                var status = $(this).data('status');
                axios.put('/platforms/status/',{
                    id:id,
                    status:status
                }).then(function (response) {
                    // 请求成功之后重新加载页面
                    location.reload();
                });
            });
        });

    </script>
@endsection
