<?php
/*
 * XTemp - XML Templating Engine for PHP
 * XhtmlPresenter.php created on 3. 10. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

/**
 * A standard Nette presenter that accepsts *.xhtml in template names
 * instead of the default Nette extensions
 *
 * @author burgetr
 */
class XhtmlPresenter extends \Nette\Application\UI\Presenter
{

	public function formatTemplateFiles()
	{
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$dir = dirname($this->getReflection()->getFileName());
		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);
		return array(
				"$dir/templates/$presenter/$this->view.xhtml",
				"$dir/templates/$presenter.$this->view.xhtml",
		);
	}

}
