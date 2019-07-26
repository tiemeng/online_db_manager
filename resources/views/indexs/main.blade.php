
@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h3>进行中</h3>
                    <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p>
                    <ul class="sortable-list connectList agile-list">
                        <li class="success-element">
                            全面、较深入地掌握我们“产品”的功能、特色和优势并做到应用自如。
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.01
                            </div>
                        </li>
                        <li class="success-element">
                            根据自己以前所了解的和从其他途径搜索到的信息，录入客户资料150家。
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="warning-element">
                            锁定有意向客户20家。
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                        <li class="warning-element">
                            力争完成销售指标。
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="info-element">
                            在总结和摸索中前进。
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.08.04
                            </div>
                        </li>
                        <li class="success-element">
                            不断学习行业知识、产品知识，为客户带来实用介绍内容
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="danger-element">
                            先友后单：与客户发展良好友谊，转换销售员角色，处处为客户着想
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.11.04
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h3>已完成</h3>
                    <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p>
                    <ul class="sortable-list connectList agile-list">
                        <li class="info-element">
                            制定工作日程表
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                        <li class="warning-element">
                            每天坚持打40个有效电话，挖掘潜在客户
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="warning-element">
                            拜访客户之前要对该客户做全面的了解(客户的潜在需求、职位、权限以及个人性格和爱好)
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="warning-element">
                            提高自己电话营销技巧，灵活专业地与客户进行电话交流
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.08.04
                            </div>
                        </li>
                        <li class="success-element">
                            通过电话销售过程中了解各盛市的设备仪器使用、采购情况及相关重要追踪人
                            <div class="agile-detail">
                                <a href="agile_board.html#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection
