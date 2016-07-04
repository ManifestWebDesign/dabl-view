<?php

use Dabl\View\View;

/**
 * Created by IntelliJ IDEA.
 * User: dan
 * Date: 7/4/16
 * Time: 9:36 AM
 */
class ViewTest extends PHPUnit_Framework_TestCase {

	/**
	 * @expectedException Dabl\View\Exception\DirectoryNotFoundException
	 * @expectedExceptionMessage Directory 'test-directory' not found
	 */
	function testSetDirectoryNotExists() {
		View::addDirectory('test-directory');
	}

	function testSetDirectoryExists() {
		View::addDirectory(__DIR__);
		$this->assertEquals(View::getDirectories(), array(__DIR__ . '/'));
	}

	function testAddDirectoryTwice() {
		View::addDirectory(__DIR__);
		View::addDirectory(__DIR__ . '/');
		$this->assertEquals(View::getDirectories(), array(__DIR__ . '/'));
	}

	/**
	 * @expectedException Dabl\View\Exception\ViewNotFoundException
	 */
	function testSetFileNotExists() {
		View::addDirectory(__DIR__);
		View::create('foobar');
	}

	function testSetFileExists() {
		View::addDirectory(__DIR__);
		View::create('ViewTest');
	}

	function testLoadReturnsOutput() {
		View::addDirectory(__DIR__ . '/views');
		$output = View::load('my-view', array('my_param' => 'hello world'), true);
		$this->assertEquals('<div>hello world</div>', $output);
	}

}