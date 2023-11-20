<?php

use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase {
    // Returns query result when data is found
    public function test_returns_query_result_when_data_found()
    {
        // Arrange
        $studentId = 1;
        $expectedResult = [
            ['id' => 1, 'st_id' => 1, 'semester' => 1, 'cgpa' => 3.5],
            ['id' => 2, 'st_id' => 1, 'semester' => 2, 'cgpa' => 3.7]
        ];
        
    }
}