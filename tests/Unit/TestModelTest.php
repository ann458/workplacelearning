<?php

use App\Http\Controllers\ActingActivityController;
use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\AvailableActingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\EvidenceUploadHandler;
use App\Services\Factories\LAAFactory;
use App\Services\LAAUpdater;
use App\Services\LearningActivityActingExportBuilder;
use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class TestModelTest extends PHPUnit_Framework_TestCase {
	function testTrue() {
		$this->assertTrue(true);
	}
}

class TestModelTest extends PHPUnit_Framework_TestCase {
	
	//проверяем условие, что строка не может быть пустой
	function testStringCannotBeEmpty() {
		$model=new TestModel;
		$model->setAttributes(15,'');	
		$this->assertFalse($model->saveData());	//мы утверждаем, что на выходе должна быть ложь!
		
		$model->setAttributes(15,'aaaa');
		$this->assertTrue($model->saveData());	//а теперь истина
	}
	
	//проверяем условие 10<i<1000
	function testIntMustBeGreater() {
		$model=new TestModel;
		/* Условия ложны */
		$model->setAttributes(2,'test1');	
		$this->assertFalse($model->saveData());	
		
		$model->setAttributes(10,'test2');	
		$this->assertFalse($model->saveData());	
		
		$model->setAttributes(1000,'test3');	
		$this->assertFalse($model->saveData());	
		
		$model->setAttributes(1025,'test4');	
		$this->assertFalse($model->saveData());	
		
		/* Условие истинно */
		$model->setAttributes(600,'test5');	
		$this->assertTrue($model->saveData());	
	}
	
	//проверяем корректность чтения/записи
	function testSaveLoad() {
		$i=13;
		$str='test';
		$model=new TestModel;
		$model->setAttributes($i,$str);	
		$this->assertTrue($model->saveData());	//записали данные

		$fetchModel=new TestModel;
		$this->assertTrue($fetchModel->loadData());	//прочитали данные
		//сравниваем прочитанные данные и исходные
		$this->assertEquals($fetchModel->num,$i);
		$this->assertEquals($fetchModel->str,$str);
	}
}
class TestModel {
	public $num;
	public $str;
	
	public $fname="file.txt";
	
	public function setAttributes($i, $s) {
		$this->num=(int)$i;
		$this->str=$s;
	}
	
	public function saveData() {
		if (!strlen($this->str)) return false;
		if ($this->num<=10 || $this->num>=1000) return false;
		if (!strlen($this->str)) return false;
		$h=fopen($this->fname,'w+');
		$res=fputs($h, $this->str."\r\n".$this->num);
		fclose($h);
		return (bool)$res;
	}
	
	public function loadData() {
		$arr=file($this->fname, FILE_IGNORE_NEW_LINES);
		$arr=file($this->fname);
		if ($arr==false) return false;
		list($this->str,$this->num)=$arr;
		return (bool)$arr;
	}
}
