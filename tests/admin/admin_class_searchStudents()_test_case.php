<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
// Returns an array of matching student records when a valid search parameter is provided.
    public function test_valid_search_parameter()
    {
        // Arrange
        $searchParam = "John";
        $expectedResult = [
            ['st_id' => 1, 'name' => 'John Doe', 'contact' => '1234567890', 'email' => 'john@example.com'],
            ['st_id' => 2, 'name' => 'John Smith', 'contact' => '9876543210', 'email' => 'johnsmith@example.com']
        ];
        $mockConnection = $this->createMock(DatabaseConnection::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockConnection->method('connect')->willReturn($mockConnection);
        $mockConnection->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('bindParam')->willReturn(true);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetchAll')->willReturn($expectedResult);
        $admin = new Admin($mockConnection);

        // Act
        $result = $admin->searchStudents($searchParam);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}