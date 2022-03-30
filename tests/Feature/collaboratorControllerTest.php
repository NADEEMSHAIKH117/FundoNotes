<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class collaboratorControllerTest extends TestCase
{
        /**
     * @test 
     * for successfull add Collaborator
     * to given noteid
     */
    public function test_SuccessfullAddCollaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/addCollaboratorByNoteId',
            [
                "note_id" => "5",
                "email" => "nadeemshaikh1171998@gmail.com",
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Collaborator create Sucessfully']);
    }

        /**
     * @test 
     * for Unsuccessfull add Collaborator
     * to given noteid
     */
    public function test_UnSuccessfullAddCollaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/addCollaboratorByNoteId',
            [
                "note_id" => "3",
                "email" => "test@gmail.com",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'User Not Registered']);
    }

        /**
     * @test 
     * for successfull Update Note 
     * By Collaborator
     * to given noteid
     */
    public function test_SuccessfullUpdate_Note_ByCollaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/updateNoteByCollaborator',
            [
                "note_id" => "5",
                "title" => "update title",
                "description" => "update desc",
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note updated Sucessfully']);
    }
    /**
     * @test 
     * for Unsuccessfull Update Note 
     * By Collaborator
     * to given noteid
     */
    public function test_UnSuccessfullUpdate_Note_ByCollaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/updateNoteByCollaborator',
            [
                "note_id" => "9",
                "title" => "update title",
                "description" => "update desc",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'note_id is not correct']);
    }

        /**
     * @test 
     * for successfull Remove Collaborator
     * to given noteid
     */
    public function test_Successfull_Remove_Collaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/removeCollaborator',
            [
                "note_id" => "5",
                "email" => "nadeemshaikh1171998@gmail.com",
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Collaborator deleted Sucessfully']);
    }
    /**
     * @test 
     * for Unsuccessfull Remove Collaborator
     * to given noteid
     */
    public function test_UnSuccessfull_Remove_Collaborator()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3NDY5MSwiZXhwIjoxNjQ4Mzc4MjkxLCJuYmYiOjE2NDgzNzQ2OTEsImp0aSI6IlIyMER1a08yOE9taFRiYXUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gqwtX02PNiRYqNjCVNneWp1CtvW0fCNLndNj0jxuLH0'
        ])->json(
            'POST',
            '/api/auth/removeCollaborator',
            [
                "note_id" => "5",
                "email" => "nadeemshaikh1171998@gmail.com",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Collaborater Not created']);
    }
}

