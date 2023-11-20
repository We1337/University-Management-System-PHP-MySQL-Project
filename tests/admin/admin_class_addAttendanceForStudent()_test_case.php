<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // Successfully add a student's attendance record to 'at_student' and 'attn' tables
    public function test_add_attendance_success()
    {
        // Arrange
        $studentName = "John Doe";
        $studentID = 1;
        $expectedResult = true;
        
        $mockConnection = $this->createMock(DatabaseConnection::class);
        $mockStatement1 = $this->createMock(PDOStatement::class);
        $mockStatement2 = $this->createMock(PDOStatement::class);
        
        $mockConnection->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO `at_student` (`name`, `st_id`) VALUES (:studentName, :studentID)")
            ->willReturn($mockStatement1);
        
        $mockConnection->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO `attn` (`st_id`) VALUES (:studentID)")
            ->willReturn($mockStatement2);
        
        $mockStatement1->expects($this->once())
            ->method('bindParam')
            ->with(":studentName", $studentName, PDO::PARAM_STR);
        
        $mockStatement1->expects($this->once())
            ->method('bindParam')
            ->with(":studentID", $studentID, PDO::PARAM_INT);
        
        $mockStatement2->expects($this->once())
            ->method('bindParam')
            ->with(":studentID", $studentID, PDO::PARAM_INT);
        
        $mockConnection->expects($this->once())
            ->method('beginTransaction');
        
        $mockStatement1->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $mockStatement2->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $mockConnection->expects($this->once())
            ->method('commit');
        
        $admin = new Admin($mockConnection);
        
        // Act
        $result = $admin->addAttendanceForStudent($studentName, $studentID);
        
        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}