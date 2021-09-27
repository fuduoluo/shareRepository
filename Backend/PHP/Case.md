#### 随机生成头像
```php
if (!function_exists('make_avatar')) {
    function make_avatar($email)
    {
        $md5_email = md5($email);
        return "https://api.multiavatar.com/{$md5_email}.png";
    }
}
```