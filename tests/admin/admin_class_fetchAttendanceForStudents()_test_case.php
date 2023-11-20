<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // Returns an array of attendance records for students when the query is successful.
    public function test_fetch_attendance_for_students_successful_query($config)
    {
        // Create a mock connection object
        $connectionMock = $this->createMock(DatabaseConnection::class);
        $connectionMock->method('connect')->willReturn(true);

        // Create a mock statement object
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('execute')->willReturn(true);
        $statementMock->method('fetchAll')->willReturn(['attendance1', 'attendance2']);

        // Set up the mock objects
        $admin = new Admin($config);

        // Call the method under test
        $result = $admin->fetchAttendanceForStudents();

        // Assert the result
        $this->assertEquals(['attendance1', 'attendance2'], $result);
    }
}