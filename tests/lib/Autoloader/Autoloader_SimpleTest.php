<?php

require_once dirname(__FILE__) . '/../../../lib/Autoloader/Simple.php';

/**
 * Test class for Autoloader_Simple.
 * Generated by PHPUnit on 2011-09-26 at 16:34:21.
 */
class Autoloader_SimpleTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function getFilePathWithNamespace() {
        $_ = DIRECTORY_SEPARATOR;
        $this->assertSame("Foo{$_}Bar{$_}Baz.php", Autoloader_Simple::getFilePath('Foo\\Bar\\Baz'));
        $this->assertSame("Foo_Bar{$_}Baz.php", Autoloader_Simple::getFilePath('Foo_Bar\\Baz'));
        $this->assertSame("Foo{$_}Bar{$_}Baz.php", Autoloader_Simple::getFilePath('Foo\\Bar_Baz'));
    }

    /**
     * @test
     */
    public function getFilePathWithoutNamespace() {
        $_ = DIRECTORY_SEPARATOR;
        $this->assertSame("Foo{$_}Bar{$_}Baz.php", Autoloader_Simple::getFilePath('Foo_Bar_Baz'));
        $this->assertSame("Foo.php", Autoloader_Simple::getFilePath('Foo'));
    }

}
