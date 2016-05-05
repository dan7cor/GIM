<?php
class block_sh extends block_base {
	public function init() {
		$this->title = get_string('sh', 'block_sh');
	}
	public function get_content() {
		if ($this->content !== null) {
			return $this->content;
		}
	
		$this->content         =  new stdClass;
		$this->content->text   = 'Hello World';
		$this->content->footer = 'This took forever to understand';
	
		return $this->content;
	}
}