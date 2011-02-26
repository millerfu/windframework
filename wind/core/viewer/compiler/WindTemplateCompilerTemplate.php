<?php

L::import('WIND:core.viewer.AbstractWindTemplateCompiler');
/**
 * Enter description here ...
 *
 * the last known user to change this file in the repository  <$LastChangedBy$>
 * @author Qiong Wu <papa0924@gmail.com>
 * @version $Id$
 * @package 
 */
class WindTemplateCompilerTemplate extends AbstractWindTemplateCompiler {

	protected $source = '';

	protected $suffix = 'htm';

	/* (non-PHPdoc)
	 * @see AbstractWindTemplateCompiler::compile()
	 */
	public function compile($key, $content) {
		if (!isset($this->source)) return $content;
		preg_match('/[\$\(\/\\]/i', $this->source, $result);
		if (!empty($result)) {
			$content = '<?php include(' . $this->source . '); ?>';
		} else {
			$content = '<?php include(\'' . addslashes($this->windViewerResolver->compile($this->source)) . '\'); ?>';
		}
		return $content;
	}

	/* (non-PHPdoc)
	 * @see AbstractWindTemplateCompiler::getProperties()
	 */
	public function getProperties() {
		return array('source', 'suffix');
	}

}

?>