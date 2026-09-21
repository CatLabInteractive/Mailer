<?php

namespace CatLab\Mailer\Tests;

use CatLab\Mailer\Mailer;
use CatLab\Mailer\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testModuleAcceptsAMailer()
    {
        // Loading Module.php used to raise an "implicitly nullable" deprecation on PHP 8.4+.
        $this->assertInstanceOf(Module::class, new Module(new Mailer()));
    }
}
