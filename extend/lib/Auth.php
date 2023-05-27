<?php
 
namespace lib;

use think\facade\Db;
use think\facade\Config;
use think\facade\Session;
use think\facade\Request;
 
 

class Auth
{
	protected $_config = [
        'auth_on'           =>  true,                // 认证开关
        'auth_type'         =>  1,                   // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        =>  'auth_group',        // 用户组数据表名
        'auth_group_access' =>  'auth_group_access', // 用户-用户组关系表
        'auth_rule'         =>  'auth_rule',         // 权限规则表
        'auth_user'         =>  'users',             // 用户信息表
        
    ];
	
	public function __construct()
    {
        if (Config::get('app.auth')) {
            $this->_config = array_merge($this->_config, Config::get('app.auth'));
        }
    }
	
	/**
     * 检查权限
     * @param  string|array  $name     需要验证的规则列表，支持逗号分隔的权限规则或索引数组
     * @param  integer  $uid      认证用户ID
     * @param  string   $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and' 则表示需满足所有规则才能通过验证
     * @param  string   $mode     执行check的模式
     * @param  integer  $type     规则类型
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $relation = 'or', $mode = 'url', $type = 1)
    {
        if (!$this->_config['auth_on']) {
            return true;
        }
        $authList = $this->getAuthList($uid, $type);
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = [];
        if ($mode === 'url') {
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }
        foreach ($authList as $auth) {

            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode === 'url' && $query != $auth) {
                parse_str($query, $param); // 解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    $list[] = $auth;
                }
            } elseif (in_array($auth, $name)) {
                $list[] = $auth;
            }
        }
        if ($relation === 'or' && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation === 'and' && empty($diff)) {
            return true;
        }
        return false;
    }
    /**
     * 根据用户ID获取用户组，返回值为数组
     * @param  integer $uid 用户ID
     * @return array      用户所属用户组 ['uid'=>'用户ID', 'group_id'=>'用户组ID', 'title'=>'用户组名', 'rules'=>'用户组拥有的规则ID，多个用英文,隔开']
     */
     
}
