<?php
namespace Rackem;

class Rewritable extends Php
{
	public $rewrite_base;

	public function __construct($app, $public_folder, $options = array())
	{
		parent::__construct($app, $public_folder, $options);
		$defaults = array(
			'rewrite_base' => '/index.php'
		);
		$this->options = array_merge($this->options, $defaults);
		$this->rewrite_base = $this->options['rewrite_base'];
	}

	public function call($env)
	{
		$path = realpath($this->public_folder.$env['PATH_INFO']);
		if(!$this->is_valid($env['PATH_INFO']))
		{
			if(is_file($path))
			{
				$file = new \Rackem\File($this->public_folder);
				return $file->call($env);
			}
			$path = $this->public_folder.$this->rewrite_base.$env['PATH_INFO'];
		}
		return $this->run($env, $path);
	}
}
