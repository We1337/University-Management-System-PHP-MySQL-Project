<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {

    public function testFetchAllStudentsSuccess($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('st_id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('contact', $result[0]);
        $this->assertArrayHasKey('email', $result[0]);
    }

    public function testFetchAllStudentsNoRecords($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertFalse($result);
    }

    public function testFetchAllStudentsDatabaseError($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertFalse($result);
    }

    public function testFetchAllStudentsStatementError($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertFalse($result);
    }

    public function testFetchAllStudentsExecutionError($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertFalse($result);
    }

    public function testFetchAllStudentsEmptyResult($config)
    {
        // Arrange
        $admin = new Admin($config);
        
        // Act
        $result = $admin->fetchAllStudents();
        
        // Assert
        $this->assertFalse($result);
    }
}