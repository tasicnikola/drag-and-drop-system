<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    protected const GUID_EXAMPLE = '/^[a-fA-F\d]{8}-[a-fA-F\d]{4}-[a-fA-F\d]{4}-[a-fA-F\d]{4}-[a-fA-F\d]{12}$/';
    protected const GET_ENTITY_INSTANCE = 'getEntityInstance';
    protected const GET_BY_GUID = 'getByGuid';
    protected const GET_GUID = 'getGuid';
    protected const GET_ALL = 'getAll';
    protected const REMOVE = 'remove';
    protected const SAVE = 'save';
    protected const FIND = 'find';

    public function testBaseTest(): void
    {
        $this->assertTrue(true);
    }
}
