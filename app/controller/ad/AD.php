<?php
namespace app\controller\ad;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;

class Ad extends BaseController
{
    /**
     * 获取广告列表
     * @return \think\response\Json
     */
    public function get_ad_list()
    {
        try {
            // 获取type_id参数
            $typeId = $this->request->param('type_id');
            
            // 记录请求日志
            Log::info('get_ad_list - 开始获取广告列表', [
                'type_id' => $typeId,
                'ip' => $this->request->ip(),
                'time' => date('Y-m-d H:i:s')
            ]);
            
            // 检查type_id是否存在
            if (empty($typeId)) {
                Log::warning('get_ad_list - 缺少必需参数type_id');
                return $this->success([], '缺少必需参数type_id');
            }
            
            // 查询该分类下的所有广告内容
            $adList = Db::table('ntp_guanggao_content')
                ->where('type_id', $typeId)
                ->select();
            
            // 记录查询结果
            Log::info('get_ad_list - 查询成功', [
                'type_id' => $typeId,
                'count' => count($adList),
                'time' => date('Y-m-d H:i:s')
            ]);
            
            // 返回数据
            return $this->success($adList, '获取成功');
            
        } catch (\Exception $e) {
            // 记录错误日志
            Log::error('get_ad_list - 获取广告列表失败', [
                'type_id' => $typeId ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return $this->error('获取广告列表失败：' . $e->getMessage());
        }
    }
}