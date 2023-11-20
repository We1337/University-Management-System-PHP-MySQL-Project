<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{

    // Delete a student by their st_id.
    public function test_delete_student_by_id($config)
    {
        // Arrange
        $admin = new Admin($config);
        $studentID = 1;

        // Act
        $result = $admin->deleteStudent($studentID);

        // Assert
        $this->assertTrue($result);
    }
}