<?php

declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Controller;

class ControllerTest extends TestCase
{
    private Controller $controller;

    protected function setUp(): void
    {
        $this->controller = new Controller(new Request(), new Response());
    }

    public function testRender(): void
    {
        $template = 'test';
        $data = ['name' => 'test'];
        
        $result = $this->controller->render($template, $data);
        
        $this->assertIsString($result);
    }

    public function testView(): void
    {
        $view = $this->controller->view();
        
        $this->assertNotNull($view);
    }

    public function testResponse(): void
    {
        $response = $this->controller->response();
        
        $this->assertNotNull($response);
    }
}
