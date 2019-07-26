<?php

namespace App\Http\Controllers;



use App\Services\IndexCountService;
use App\Util\Common;

class IndexsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('indexs.index');
    }

    public function main()
    {
//        // 读取redis  todo  读取 存入 方法   数据核验  和   样式美化
//        $data = $this->_tool->getRedisData('index_count');
//
//        if($data){
////            echo 1;
////            dump($data);
//
//        }else{
////            echo 2;
//            //咩有就统计 并写入
//            $indexcount  = new IndexCountService();
//            $data  = $indexcount->getIndexCount();
////            dump($data);
//
//            $this->_tool->setRedisDataExpire('index_count',$data,3600);
//        }
        $data = [];
//        Common::getDBsByDriver('mysql');

        //显示
        return view('indexs.main',['data'=> $data]);
    }
}
