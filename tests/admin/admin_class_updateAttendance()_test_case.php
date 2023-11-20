<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {

    // Update attendance records for all students on a specific date with valid attendance values
    public function test_update_attendance_all_students_valid_values()
    {
        // Arrange
        $date = "2021-01-01";
        $atten = [
            1 => "present",
            2 => "absent",
            3 => "present"
        ];
        $expectedResult = true;
                
        // Assert
    }
}