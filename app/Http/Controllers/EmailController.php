<?php

namespace App\Http\Controllers;

use App\Mail\GetNeeds;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    
    public function send(Request $request)
    {
        //获取邮件数据
        $emailData = [
            'company' => $request->input('companyx',''),
            'name'    => $request->input('namex',''),
            'email'   => $request->input('email',''),
            'message' => $request->input('messagex',''),
            'needs'   => $request->input('needsx',''),
        ];
        //插入数据库   
        Email::create($emailData);
        //发送邮件
        $data = [
            'info'    => $request->input('companyx','').'公司的'.$request->input('namex','').'有'.$request->input('needsx','').'的需求，邮箱为：'.$request->input('emailx',''),
            'message' => $request->input('messagex','')
        ];
        try{
            Mail::to('892963341@qq.com')
            ->cc('18013272501@163.com')
            ->send(new GetNeeds($data));

        }catch(\Exception $e){
           app('log')->info('发送邮件失败---'.$e->getMessage());
        }
        return $this->response()->noContent();
    }
}
