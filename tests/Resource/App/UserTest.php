<?php

declare(strict_types=1);

namespace BearinUser\Tests\Resource\App;

use BEAR\Package\AppInjector;
use BEAR\Resource\ResourceInterface;
use BearinUser\Module\AppModule;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private ResourceInterface $resource;

    protected function setUp(): void
    {
        $this->resource = (new AppInjector(new AppModule(), 'test'))->getInstance(ResourceInterface::class);
    }

    public function testOnPost(): void
    {
        $page = $this->resource->post('/user', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User'
        ]);
        
        $this->assertSame(201, $page->code);
        $this->assertArrayHasKey('id', $page->body);
    }

    public function testOnGet(): void
    {
        $page = $this->resource->get('/user');
        
        $this->assertSame(200, $page->code);
        $this->assertIsArray($page->body);
    }
}
