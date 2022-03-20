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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0Nzc5MTE2NCwiZXhwIjoxNjQ3Nzk0NzY0LCJuYmYiOjE2NDc3OTExNjQsImp0aSI6IkI0aE05azRBb1dUbWp6dnUiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.lP4Q-p7yxmIXKkLnUf4neejSMm2aGXyhhuPK-sYjfhM'
        ])->json(
            'POST',
            '/api/auth/addCollaboratorByNoteId',
            [
                "note_id" => "2",
                "email" => "nadeemshaikh1171998@gmail.com",
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Collaborator created Sucessfully']);
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDA0NDI5OSwiZXhwIjoxNjQ0MDQ3ODk5LCJuYmYiOjE2NDQwNDQyOTksImp0aSI6IlVpMjlFbUoyNzBOR3JvWlYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DA0vqFJDJHlKBZxc12glxdriXKgMv77zZjxCyBV0BB4'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDA0NDI5OSwiZXhwIjoxNjQ0MDQ3ODk5LCJuYmYiOjE2NDQwNDQyOTksImp0aSI6IlVpMjlFbUoyNzBOR3JvWlYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DA0vqFJDJHlKBZxc12glxdriXKgMv77zZjxCyBV0BB4'
        ])->json(
            'POST',
            '/api/auth/updateNoteByCollaborator',
            [
                "note_id" => "3",
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDA0NDI5OSwiZXhwIjoxNjQ0MDQ3ODk5LCJuYmYiOjE2NDQwNDQyOTksImp0aSI6IlVpMjlFbUoyNzBOR3JvWlYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DA0vqFJDJHlKBZxc12glxdriXKgMv77zZjxCyBV0BB4'
        ])->json(
            'POST',
            '/api/auth/updateNoteByCollaborator',
            [
                "note_id" => "9",
                "title" => "update title",
                "description" => "update desc",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Collaborator Email not registered']);
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDA0NDI5OSwiZXhwIjoxNjQ0MDQ3ODk5LCJuYmYiOjE2NDQwNDQyOTksImp0aSI6IlVpMjlFbUoyNzBOR3JvWlYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DA0vqFJDJHlKBZxc12glxdriXKgMv77zZjxCyBV0BB4'
        ])->json(
            'POST',
            '/api/auth/removeCollaborator',
            [
                "note_id" => "2",
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDA0NDI5OSwiZXhwIjoxNjQ0MDQ3ODk5LCJuYmYiOjE2NDQwNDQyOTksImp0aSI6IlVpMjlFbUoyNzBOR3JvWlYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DA0vqFJDJHlKBZxc12glxdriXKgMv77zZjxCyBV0BB4'
        ])->json(
            'POST',
            '/api/auth/removeCollaborator',
            [
                "note_id" => "2",
                "email" => "nadeemshaikh1171998@gmail.com",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Collaborator could not deleted']);
    }
}

