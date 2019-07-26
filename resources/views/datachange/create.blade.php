@extends('layouts.layout')

@section('css')
<style>
    .animated{-webkit-animation-fill-mode: none;}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox-title">
            <h5>添加申请</h5>
        </div>
        <div class="ibox-content">
            <a href="{{route('datachange.index')}}">
                <button class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus-circle"></i> 申请列表
                </button>
            </a>
            <div class="hr-line-dashed m-t-sm m-b-sm"></div>
            <form class="form-horizontal m-t-md" action="{{route('datachange.store')}}" method="POST">
                <div class="form-group">
                    <label class="col-sm-2 control-label">数据库类型：</label>
                    <div class="col-sm-2">
                        <select onchange="getDbs(this.value)" name="db_type" class="form-control">
                            <option value="0" >请选择</option>
                            @foreach($dbType as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('db_type'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('db_type')}}</span>
                        @endif
                    </div>
                </div>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">数据库名：</label>
                    <div class="col-sm-3">
                        <select onchange="getTable(this)" name="db_name" class="db_name form-control">
                            <option value="" >请选择</option>
                        </select>
                        @if ($errors->has('db_name'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('db_name')}}</span>
                        @endif
                    </div>
                </div>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">表名：</label>
                    <div class="col-sm-3">
                        <select  name="table_name" class="table_name form-control">
                            <option value="" >请选择</option>
                        </select>
                        @if ($errors->has('table_name'))
                            <span style="color: red" class="help-block m-b-none"><i class="fa fa-info-circle"></i>{{$errors->first('table_name')}}</span>
                        @endif
                    </div>
                </div>
                <div class="hr-line-dashed m-t-sm m-b-sm"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">变更SQL：</label>
                    <div class="col-sm-3">
                        <textarea required rows="5" data-msg-required="请输入执行sql" class="form-control" name="exc_sql"></textarea>
                        @if ($errors->has('exc_sql'))
                            <span class="help-block m-b-none" style="color: red"><i class="fa fa-info-circle"></i>{{$errors->first('exc_sql')}}</span>
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
                {{csrf_field()}}
            </form>
        </div>
    </div>
</div>
<div id="functions" style="display: none;">
    @include('rules.fonticon')
</div>
@section('footer-js')
<script>

   function getDbs(driver){
       $.post('{{route('datachange.dbs')}}/'+driver,{_token:'{{csrf_token()}}'},(re)=>{
           let option = '<option>请选择</option>';
           $(".table_name").html(option);
           if(re.data.length > 0){
               $.each(re.data, function(data,connection){
                   $.each(connection,(db,conn)=>{
                       option+="<option data-con='"+conn+"' value='"+db+"'>"+db+"</option>";
                   });

               });
           }
           $(".db_name").html(option);
       },'json')

   }
   function getTable(obj){
       let db = $(obj).val();
       let conn = $(obj).find('option:selected').attr('data-con');
       let option = '<option>请选择</option>';
       $(".table_name").html(option);
       if(db.length > 0 && conn != undefined){
           $("form").append("<input type='hidden' name='conn_name' value='"+conn+"'/>");
           $.post('{{route('datachange.tables')}}/'+conn+"/"+db,{_token:'{{csrf_token()}}'},(data)=>{
               if(data.data.length > 0){
                   $.each(data.data,(k,v)=>{
                        option+="<option value='"+v+"'>"+v+"</option>";
                   });
               }
               $(".table_name").html(option);
           },'json')
       }


   }
</script>
@endsection
@endsection
