<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

trait RemarkMapTrait
{
    public function mapDescList(array $data, array $extraData = [])
    {
        $data = array_merge($data, $extraData);

        $result = [];
        foreach ($data as $k => $v) {
            $result["*.{$k}"] = $v;
        }

        return $result;
    }

    /**
     * 获取注解备注
     *
     * @param string $desc
     * @param array $map
     *
     * example:
     *
     * $desc = '状态'
     * $map = [
            0 => '禁用',
            1 => '启用',
        ]
     *
     * return '状态: 0-禁用,1-启用'
     *
     * @return string
     */
    public function getRemarkByMap(string $desc, array $map)
    {
        $kvs = array_map(function ($v, $k) {
            return sprintf('%s-%s', $v, $k);
        }, array_keys($map), array_values($map));

        $kvStr = implode(',', $kvs);

        return sprintf('%s: %s', $desc, $kvStr);
    }
}
