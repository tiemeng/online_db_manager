@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <h5>黑名单详情</h5>
            </div>

            <div class="ibox-content">
                <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
                <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
                <a href="{{route('roles.index')}}" link-url="javascript:void(0)"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 新增黑名单</button></a>
                <div class="ibox-title form-inline">
                    <div class="form-group col-sm-pull-2" style="font-size: 16px;" >
                        借款总金额：<span style="font-size: 18px; color: red">{{$borrow_mount}}</span> 元
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover m-t-md">
                    <thead>
                    <tr>
                        <th class="text-center" width="100">ID</th>
                        <th class="text-center" >注册平台</th>
                        <th class="text-center" >平台借款金额</th>
                        <th class="text-center" >平台投资金额</th>
                        <th class="text-center" >被拉黑</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(isset($detail))
                        @foreach($detail as $key => $item)
                            <tr>
                                <td class="text-center" > {{$item->id}} </td>
                                <td class="text-center" > {{$item->platform_name}} </td>
                                <td class="text-center" > {{$item->borrowing}}元 </td>
                                <td class="text-center" > {{$item->investment}}元 </td>
                                <td class="text-center" >
                                    @if($item->status == 1)
                                        <span style="color: red">是</span>
                                    @elseif($item->status == 2)
                                        <span style="color: lime">否</span>
                                    @else
                                        <span style="color: yellow">未知状态</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td  class="text-center" colspan="4" >未找到相关信息！</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="pull-right pagination m-t-no">
                    <div class="text-center">
                        {{$detail->links()}}
                    </div>
                    <div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection