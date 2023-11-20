<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {

    // Retrieves distinct attendance dates from the 'attn' table.
    public function test_fetch_distinct_attendance_dates($config) {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchDistinctAttendanceDates();
        
        // Assert
        $this->assertIsArray($result);
    }

}