<?php
/**
 * @author Qiong Wu <papa0924@gmail.com> 2010-11-3
 * @link http://www.phpwind.com
 * @copyright Copyright &copy; 2003-2110 phpwind.com
 * @license 
 */

/**
 * 路由解析器接口
 * 职责: 路由解析, 返回路由对象
 * 实现路由解析器必须实现该接口的doParser()方法
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id$ 
 * @package 
 */
abstract class WRouter {
	protected $_urlRule;
	protected $_parserName = 'url';
	
	protected $_action;
	protected $_controller;
	protected $_app1;
	protected $_app2;
	
	/**
	 * 通过实现该接口实现路由解析
	 * @return WRouterContext
	 */
	abstract function doParser($configObj, $request);
	
	/**
	 * 获得业务操作
	 */
	public function getAction() {
		return $this->_action;
	}
	
	/**
	 * 获得业务对象
	 */
	public function getController() {
		return $this->_controller;
	}
	
	/**
	 * 获得一组应用入口目录名
	 */
	public function getApp1() {
		return $this->_app1;
	}
	
	/**
	 * 获得一组应用入口二级目录名
	 */
	public function getApp2() {
		return $this->_app2;
	}

}