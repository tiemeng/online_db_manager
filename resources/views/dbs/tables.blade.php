<div style="height:100%;width: 20%;position: fixed;top:3%;left: 0;z-index: 999;margin-left: 40px;overflow:auto;">
    <div class="ibox float-e-margins" style="margin-bottom: 0">
        <div class="ibox-content">
            <a class="menuid btn btn-primary btn-sm" href="javascript:location.reload()">刷新</a>
            <a class="menuid btn btn-primary btn-sm" href="javascript:history.go(-1)">返回</a> &nbsp;
            <a class="menuid btn btn-primary btn-sm" href="javascript:exportWord()">导出</a> &nbsp;
            <div class="file-manager">
                <h5><strong>{{$db}}</strong> 的表如下</h5>
                <ul class="folder-list" style="padding: 0">
                    @foreach($tables as $table)
                        <li>
                            <a href="#{{$table->table_name}}"><i class="fa fa-folder"></i> {{$table->table_name}}@if(!empty($table->table_comment))<br>({{$table->table_comment}})@endif</a>
                        </li>
                    @endforeach
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@extends('layouts.layout')
@section('content')
    <div class="row">

        <div class="col-sm-9 animated fadeInRight" style="float: right;margin-right: 10px">
            <div class="row">
                <div class="col-sm-12">
                    <div>
                        <div class="ibox-content">
                            @foreach($tablesInfo as $k=>$infos)
                                <a name="{{$k}}">
                            <h2>{{$k}}的表结构如下</h2>
                            <br>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="15%">列名</th>
                                    <th width="25%">数据类型</th>
                                    <th width="8%">索引</th>
                                    <th width="12%">是否为空</th>
                                    <th width="15%">默认值</th>
                                    <th width="25%">注释</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($infos as $info)
                                <tr>
                                    <td width="15%">{{$info->column_name}}</td>
                                    <td width="25%">{{strpos(str_replace(['character varying','without time zone'],['varchar',''],$info->column_type),'nextval')}}</td>
                                    <td width="8%">{{$info->column_key}}</td>
                                    <td width="12%">{{$info->is_nullable}}</td>
                                    <td width="15%">@php
                                    $default = str_replace(['::character varying',"'",'::jsonb','::timestamp without time zone'],"",$info->column_default);
                                    echo strpos($default,'nextval') !== false ? '' :$default;
                                    @endphp
                                    </td>
                                    <td width="25%">{{$info->column_comment}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                                </a>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
<div style="width: 35px;height: 35px;background-color: darkgrey;position: fixed;top:90%;right: 0;z-index: 999;"><a href="#" style="vertical-align: center">回到顶部</a></div>
<script src="https://cdn.staticfile.org/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $(".file-box").each(function () {
            animationHover(this, "pulse")
        })
    });

    function exportWord(){
        let conn_name = "{{$conn_name}}";
        let db = "{{$db}}";
        $.post("{{route("dbs.export")}}",{conn_name:conn_name,db:db,_token:'{{csrf_token()}}'},function(res){
            if(res.code == 200){
                layer.msg('导出成功');
            }else{
                layer.msg('导出失败');
            }
        },'json');
    }
</script>