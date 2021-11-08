#### 1. [laravel Orwhere 使用](https://www.cnblogs.com/lxwphp/p/10938996.html)
```php
方式1：
$monitor2Where = [['devid', $DeviceId], ['is_del', 0]];
        $monitorPowerInfo = $this->deviceMonitorInfoModel->where($monitor2Where)->where(function($query){
            $query->where('ins_type','<>',1)
                ->orWhere(function($query){
                    $query->where('ins_type', NULL);
                });
        })->get($fields)->toArray();
        
方式2：
$this->deviceMonitorInfoModel->where($monitor2Where)->whereRaw('(ins_type <> 1 OR ins_type IS NULL)')->get($fields)->toArray();

等价于 where devid = $DeviceId AND os_del =0  (ins_type <> 1 OR ins_type = null)
```
