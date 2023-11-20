<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // admin logs in successfully with correct credentials
    public function test_admin_login_success($config)
    {
        // Arrange
        $admin = new Admin($config);
        $username = "admin";
        $password = "password";

        // Act
        $result = $admin->adminLogin($username, $password);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($_SESSION['admin_login']);
        $this->assertEquals($_SESSION['admin_id'], 1);
        $this->assertEquals($_SESSION['admin_name'], $username);
    }
}