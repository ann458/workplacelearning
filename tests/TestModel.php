<?php
class TestModel {
	public $num;
	public $str;

	public function setAttributes($i, $s) {}
	/*
	@return: true, udaje boli ulozeny
			false, ak nie
	*/
	public function saveData() {return false;}
	/*
	@return: true, udaje boli precitani
			false, ak nie
	*/
	public function loadData() {return false;}
}