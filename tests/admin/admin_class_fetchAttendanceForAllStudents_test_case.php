<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // Should retrieve attendance records for all students on a specific date
    public function test_fetch_attendance_for_all_students_success()
    {
        // Arrange
        $date = "2021-01-01";
        $expectedResult = [
            [
                "name" => "John Doe",
                "st_id" => 1,
                "atten" => "Present",
                "at_date" => "2021-01-01"
            ],
            [
                "name" => "Jane Smith",
                "st_id" => 2,
                "atten" => "Absent",
                "at_date" => "2021-01-01"
            ]
        ];
        $mockConnection = $this->createMock(DatabaseConnection::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockConnection->method("prepare")->willReturn($mockStatement);
        $mockStatement->method("bindParam")->willReturn(true);
        $mockStatement->method("execute")->willReturn(true);
        $mockStatement->method("fetchAll")->willReturn($expectedResult);
        $admin = new Admin($mockConnection);

        // Act
        $result = $admin->fetchAttendanceForAllStudents($date);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}