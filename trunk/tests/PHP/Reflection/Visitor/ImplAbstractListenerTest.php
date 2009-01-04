<?php
/**
 * This file is part of PHP_Reflection.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008-2009, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    PHP_Reflection
 * @subpackage Visitor
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2009 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.manuel-pichler.de/
 */

require_once dirname(__FILE__) . '/../AbstractTest.php';
require_once dirname(__FILE__) . '/_dummy/TestImplAbstractVisitor.php';
require_once dirname(__FILE__) . '/_dummy/TestImplAbstractListener.php';

require_once 'PHP/Reflection/AST/Class.php';
require_once 'PHP/Reflection/AST/File.php';
require_once 'PHP/Reflection/AST/Function.php';
require_once 'PHP/Reflection/AST/Interface.php';
require_once 'PHP/Reflection/AST/Method.php';
require_once 'PHP/Reflection/AST/Package.php';
require_once 'PHP/Reflection/AST/Property.php';

/**
 * Test case for the default visit listener implementation.
 *
 * @category   PHP
 * @package    PHP_Reflection
 * @subpackage Visitor
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2009 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.manuel-pichler.de/
 */
class PHP_Reflection_Visitor_ImplAbstractListenerTest extends PHP_Reflection_AbstractTest
{
    public function testDefaultImplementationCallsListeners()
    {
        $file = new PHP_Reflection_AST_File(null);
        
        $package   = new PHP_Reflection_AST_Package('package');
        $class     = $package->addType(new PHP_Reflection_AST_Class('clazz'));
        $method1   = $class->addMethod(new PHP_Reflection_AST_Method('m1'));
        $method2   = $class->addMethod(new PHP_Reflection_AST_Method('m2'));
        $property  = $class->addProperty(new PHP_Reflection_AST_Property('$p1'));
        $interface = $package->addType(new PHP_Reflection_AST_Interface('interfs'));
        $method3   = $interface->addMethod(new PHP_Reflection_AST_Method('m3'));
        $method4   = $interface->addMethod(new PHP_Reflection_AST_Method('m4'));
        $function  = $package->addFunction(new PHP_Reflection_AST_Function('func'));
        
        $class->setSourceFile($file);
        $function->setSourceFile($file);
        $interface->setSourceFile($file);
        
        $listener = new PHP_Reflection_Visitor_TestImplAbstractListener();
        $visitor  = new PHP_Reflection_Visitor_TestImplAbstractVisitor();
        $visitor->addVisitListener($listener);
        $visitor->visitPackage($package);
        
        $this->assertArrayHasKey($file->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($file->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($package->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($package->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($class->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($class->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($function->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($function->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($interface->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($interface->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($method1->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($method1->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($method2->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($method2->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($method3->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($method3->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($method4->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($method4->getUUID() . '#end', $listener->nodes);
        $this->assertArrayHasKey($property->getUUID() . '#start', $listener->nodes);
        $this->assertArrayHasKey($property->getUUID() . '#end', $listener->nodes);
    }
}