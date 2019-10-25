<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2019 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://demo.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace think\admin;

use think\Request;
use think\Route;
use think\Service;

/**
 * 模块注册服务
 * Class ThinkLibrary
 * @package think\admin
 */
class ThinkLibrary extends Service
{
    /**
     * 服务启动方法
     * @param Route $route
     */
    public function boot(Route $route)
    {
        // 图形验证码路由绑定
        $route->get('think/admin/captcha', function () {
            $image = \think\admin\extend\CaptchaExtend::instance();
            return json(['code' => '1', 'info' => '生成验证码', 'data' => [
                'uniqid' => $image->getUniqid(), 'image' => $image->getData(),
            ]]);
        });
        // 注册访问跨域中间键
        $this->app->middleware->add(function (Request $request, \Closure $next) {
            $header = [];
            if (($origin = $request->header('origin', '*')) !== '*') {
                $header['Access-Control-Allow-Origin'] = $origin;
                $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE';
                $header['Access-Control-Allow-Headers'] = 'Authorization,Content-Type,If-Match,If-Modified-Since,If-None-Match,If-Unmodified-Since,X-Requested-With';
                $header['Access-Control-Expose-Headers'] = 'User-Token-Csrf';
            }
            if ($request->isOptions()) {
                return response()->code(204)->header($header);
            } else {
                return $next($request)->header($header);
            }
        });
        // 注册系统任务指令
        $this->commands([
            'think\admin\queue\WorkQueue',
            'think\admin\queue\StopQueue',
            'think\admin\queue\StateQueue',
            'think\admin\queue\StartQueue',
            'think\admin\queue\QueryQueue',
            'think\admin\queue\ListenQueue',
        ]);
    }

}