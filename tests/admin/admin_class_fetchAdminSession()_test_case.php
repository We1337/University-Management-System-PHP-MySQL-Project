<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // Returns true if 'admin_login' session variable is set.
    public function test_admin_login_session_variable_set($config)
    {
        // Arrange
        $_SESSION['admin_login'] = true;
        $admin = new Admin($config);

        // Act
        $result = $admin->fetchAdminSession();

        // Assert
        $this->assertTrue($result);
    }
}