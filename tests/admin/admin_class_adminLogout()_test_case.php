<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
// Admin logs out successfully
    function test_admin_logs_out_successfully($config) {
        $admin = new Admin($config);
        $_SESSION['admin_login'] = true;
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_name'] = 'admin';

        $admin->adminLogout();

        $this->assertFalse($_SESSION['admin_login']);
        $this->assertArrayNotHasKey('admin_id', $_SESSION);
        $this->assertArrayNotHasKey('admin_name', $_SESSION);
    }
}