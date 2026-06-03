<?php

namespace Tests\Integration;

use Framework\Database;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected Database $database;

    protected function setUp(): void
    {
        parent::setUp();

        // connect to the DB container using the environment variables or defaults
        $this->database = new Database(
            'db',
            'maindb',
            'user',
            'root',
            '3306'
        );

        // start a transaction to keep the database clean
        $this->database->exec("SET AUTOCOMMIT = 0");
        $this->database->exec("START TRANSACTION");
    }

    protected function tearDown(): void
    {
        // rollback any changes made during the test
        $this->database->exec("ROLLBACK");
        parent::tearDown();
    }
}
