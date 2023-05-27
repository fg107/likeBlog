<?php
// +----------------------------------------------------------------------
// | Captcha配置文件
// +----------------------------------------------------------------------

return [

	'verify'=>[
        // 验证码字体大小
            'fontSize' =>14,
            // 验证码位数
            'length' => 4,
			 // 是否画混淆曲线
			'useCurve' => true,
            // 关闭验证码杂点
            'useNoise' => true,
            // 验证码图片高度
            'imageH'   => 34,
            // 验证码图片宽度
            'imageW'   => 145,
            // 验证码过期时间（s）
            'expire'   => 60,
    ],
    //验证码位数
    'length'   => 5,
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码过期时间
    'expire'   => 1800,
    // 是否使用中文验证码
    'useZh'    => false,
    // 是否使用算术验证码
    'math'     => false,
    // 是否使用背景图
    'useImgBg' => false,
    //验证码字符大小
    'fontSize' => 25,
    // 是否使用混淆曲线
    'useCurve' => true,
    //是否添加杂点
    'useNoise' => true,
    // 验证码字体 不设置则随机
    'fontttf'  => '',
    //背景颜色
    'bg'       => [243, 251, 254],
    // 验证码图片高度
    'imageH'   => 0,
    // 验证码图片宽度
    'imageW'   => 0,

    // 添加额外的验证码设置
    'verify' => [
        'length'=>4,
        'useCurve' => false,
        'useNoise' => false,
        'fontSize' => 18,
        
    ],
];
