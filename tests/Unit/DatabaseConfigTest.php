<?php

namespace Tests\Unit;

use Tests\TestCase;

class DatabaseConfigTest extends TestCase
{
    /** @test */
    public function database_config_can_be_loaded_without_errors()
    {
        // This test ensures the database configuration can be loaded
        // without throwing errors about undefined PDO constants
        $config = config('database');
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('connections', $config);
        $this->assertArrayHasKey('mysql', $config['connections']);
        $this->assertArrayHasKey('mysql_backup', $config['connections']);
    }

    /** @test */
    public function mysql_connection_options_are_properly_configured()
    {
        $mysqlConfig = config('database.connections.mysql');
        
        $this->assertIsArray($mysqlConfig);
        $this->assertArrayHasKey('options', $mysqlConfig);
        
        // Verify that options is an array (can be empty if pdo_mysql not loaded)
        $this->assertIsArray($mysqlConfig['options']);
        
        // If pdo_mysql is loaded, verify the SSL option is set
        if (extension_loaded('pdo_mysql')) {
            $expectedKey = defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT') 
                ? PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT 
                : 1014;
            
            $this->assertArrayHasKey($expectedKey, $mysqlConfig['options']);
            $this->assertFalse($mysqlConfig['options'][$expectedKey]);
        }
    }

    /** @test */
    public function mysql_backup_connection_options_are_properly_configured()
    {
        $mysqlBackupConfig = config('database.connections.mysql_backup');
        
        $this->assertIsArray($mysqlBackupConfig);
        $this->assertArrayHasKey('options', $mysqlBackupConfig);
        
        // Verify that options is an array (can be empty if pdo_mysql not loaded)
        $this->assertIsArray($mysqlBackupConfig['options']);
        
        // If pdo_mysql is loaded, verify the SSL option is set
        if (extension_loaded('pdo_mysql')) {
            $expectedKey = defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT') 
                ? PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT 
                : 1014;
            
            $this->assertArrayHasKey($expectedKey, $mysqlBackupConfig['options']);
            $this->assertFalse($mysqlBackupConfig['options'][$expectedKey]);
        }
    }

    /** @test */
    public function pdo_constant_fallback_works_correctly()
    {
        // Test that our fallback logic works as expected
        $testKey = defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT') 
            ? PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT 
            : 1014;
        
        // Verify that if constant is defined, we get the correct value
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $this->assertEquals(1014, PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT);
        }
        
        // Verify our fallback value is correct
        $this->assertEquals(1014, $testKey);
    }
}