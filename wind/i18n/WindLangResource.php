<?php
Wind::import('WIND:i18n.IWindLangResource');
/**
 * 语言资源基础实现
 * 
 * 语言资源基础实现,支持xml,property,ini,php格式语言资源类型的解析,该语言资源组件基于wind组件模式进行开发.
 * 实现了语言包路径,默认语言文件,语言内容缓存等功能.
 * @example <code>
 * LANG 为包名,如果不填写则默认没有分包处理,资源类将自动在language包下面寻找
 * 支持解析格式: LANG:login.fail.expty = 'xxx'
 * </code>
 * @author Shi Long <long.shi@alibaba-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.windframework.com
 * @version $Id$
 * @package i18n
 */
class WindLangResource extends WindModule implements IWindLangResource {
	/**
	 * 缓存key前缀
	 *
	 * @var string
	 */
	protected $_cachePrefix = 'Wind.i18n.WindLangResource';
	/**
	 * 消息存储池
	 *
	 * @var array
	 */
	protected $_messages = array();
	/**
	 * 默认资源文件
	 *
	 * @var string
	 */
	protected $default = 'message';
	/**
	 * 资源文件后缀名定义
	 *
	 * @var string
	 */
	protected $suffix = '';
	/**
	 * 语言包目录
	 *
	 * @var string
	 */
	protected $path;
	/**
	 * 语言
	 *
	 * @var string
	 */
	protected $language;

	/* (non-PHPdoc)
	 * @see IWindLangResource::lang()
	 */
	public function getMessage($message, $params = array()) {
		$package = '';
		if (strpos($message, ':') != false) {
			list($package, $message) = explode(':', $message, 2);
		}
		$keys = explode('.', $message);
		$file = $keys[0];
		$path = $this->resolvedPath($package);
		if (is_file($path . '/' . $file . '.' . $this->suffix)) {
			$path = $path . '/' . $file . '.' . $this->suffix;
			unset($keys[0]);
		} elseif (is_file($path . '/' . $this->default . '.' . $this->suffix)) {
			$path = $path . '/' . $this->default . '.' . $this->suffix;
			$file = $this->default;
		} else
			throw new WindI18nException(
				'[wind.WindTranslater.translate] lang resource file  ' . $this->path . 'is not exit.');
		
		if (!isset($this->_messages[$path])) {
			$cache = Wind::getApp()->getComponent('windCache');
			$_cache = $this->_cachePrefix . $package . $file;
			$messages = Wind::getApp()->getComponent('configParser')->parse($path, $_cache, '', $cache);
			$this->_messages[$path] = $messages;
		}
		$message = $this->_getMessage($this->_messages[$path], $keys);
		$params && $message = call_user_func_array('sprintf', array($message) + $params);
		return $message;
	}

	/**
	 * 获取一条message信息
	 * 
	 * @param array $messages
	 * @param string $key
	 */
	protected function _getMessage($messages, $keys) {
		if (is_string($keys)) return '';
		foreach ($keys as $value) {
			if (!isset($messages[$value])) continue;
			$messages = $messages[$value];
		}
		return is_array($messages) ? '' : $messages;
	}

	/**
	 * 解析资源文件路径信息
	 *
	 * @param string $package
	 * @return string
	 */
	protected function resolvedPath($package) {
		$this->path || $this->path = Wind::getRootPath(Wind::getAppName());
		$this->language || $this->language = Wind::getApp()->getRequest()->getAcceptLanguage();
		$path = $this->path . '/' . $this->language . '/' . $package;
		$path = Wind::getRealDir(trim($path, '/'), true);
		if (!is_dir($path)) throw new WindI18nException(
			'[Wind.WindTranslater.resolvedPath] resolve resource path fail, path ' . $path . ' is not exit.');
		return $path;
	}

	/* (non-PHPdoc)
	 * @see WindModule::setConfig()
	 */
	public function setConfig($config) {
		parent::setConfig($config);
		$this->suffix = $this->getConfig('suffix', '', '');
		$this->default = $this->getConfig('default', '', 'message');
		$this->path = $this->getConfig('path', '', '');
		$this->language = $this->getConfig('language', '', 'zh_cn');
	}
}

?>