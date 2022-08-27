<?php

namespace App\Response;

use Dingo\Api\Event\ResponseWasMorphed;

class FormatResponse
{

    public function handle(ResponseWasMorphed $event)
    {
        $statusCode = $event->response->getStatusCode();
        if ($statusCode == 200) {
            // 取dingo配置文件内的异常时响应格式字段，确保整个响应数据格式一致
            $errorFormat = config('api.errorFormat');
            $errorFormatKey = array_keys($errorFormat);
            // 追加的响应
            $addData = [
                $errorFormatKey[0] => 200, // HTTP响应状态码
                $errorFormatKey[1] => 'success', // 信息描述
            ];
            $tempContent = [];

            if (is_null($event->content)) {
                $event->content = [];
            }
            // 响应内容
            $tempContent = [
                'data' => isset($event->content['data']) ? $event->content['data'] : $event->content // 解决使用了transform后data重复问题
            ];
            /**
             * 控制器分页举例
             * $histories = DB::table('jpush_message_history')->paginate(3);
             * return $this->response->paginator($histories, new TestTransformer());
             */
            // 分页处理
            if (isset($event->content) && isset($event->content['meta']) && isset($event->content['meta']['pagination'])) {
                // Log::debug("对响应的分页数据进行重新组装");
                $tempContent['pageSize'] = $event->content['meta']['pagination']['per_page'];
                $tempContent['total'] = $event->content['meta']['pagination']['total'];
                $tempContent['currentPage'] = $event->content['meta']['pagination']['current_page'];
                $tempContent['total_pages'] = $event->content['meta']['pagination']['total_pages'];
            }

            $event->content = array_merge($addData, $tempContent);
        }
    }
}
