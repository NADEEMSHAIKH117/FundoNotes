<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    /**
     * @test 
     * for successfull notecreation
     */
    public function test_SuccessfullCreateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwNTM3MiwiZXhwIjoxNjQ3MjA4OTcyLCJuYmYiOjE2NDcyMDUzNzIsImp0aSI6ImVZOWhTZjFMZWVzTWFjQm4iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.e7mdd_8o6qQCJzdnpGlSGy7ZEr6L8pEhOmABLsj-gxQ'
        ])->json(
            'POST',
            '/api/auth/createNotes',
            [
                "title" => "testing title",
                "description" => "testing description",
            ]
        );
        $response->assertStatus(200)->assertJson(['message' => 'notes created successfully']);
    }

    /**
     * @test 
     * for Successfull Note update
     */

    public function test_SuccessfullUpdateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwNjQ0NSwiZXhwIjoxNjQ3MjEwMDQ1LCJuYmYiOjE2NDcyMDY0NDUsImp0aSI6ImhlNko0eFhSNVpIdlBtMjYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qvlE8YaxaPQx91WGbYzBk3R_rTXWmqbfdWuRHBV1Y9k'
        ])->json(
            'POST',
            '/api/auth/updateNoteById',
            [
                "id" => "1",
                "title" => "updated title",
                "description" => "updated description",
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note updated Successfully']);
    }

    /**
     * @test 
     * for Unsuccessfull Note Updatation
     */

    public function test_FailUpdateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwNjQ0NSwiZXhwIjoxNjQ3MjEwMDQ1LCJuYmYiOjE2NDcyMDY0NDUsImp0aSI6ImhlNko0eFhSNVpIdlBtMjYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qvlE8YaxaPQx91WGbYzBk3R_rTXWmqbfdWuRHBV1Y9k'
        ])->json(
            'POST',
            '/api/auth/updateNoteById',
            [
                "id" => "12",
                "title" => "title Test",
                "description" => "description Test",
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
    }

    /**
     * @test 
     * for Successfull Deletion of Node
     */
    public function test_SuccessfullDeleteNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwNzA5NSwiZXhwIjoxNjQ3MjEwNjk1LCJuYmYiOjE2NDcyMDcwOTUsImp0aSI6IlVqeDhMdnNweFd0dXoxZlciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.tfRqoYVQ8HC3L5PLk9g1rXKyNGsWYQWV5k0d0KUoiyE'
        ])->json(
            'POST',
            '/api/auth/deleteNoteById',
            [
                "id" => "6"
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note deleted Successfully']);
    }

    /**
     * @test 
     * for Successfull Deletion of Node
     */
    public function test_UnSuccessfullDeleteNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwNzA5NSwiZXhwIjoxNjQ3MjEwNjk1LCJuYmYiOjE2NDcyMDcwOTUsImp0aSI6IlVqeDhMdnNweFd0dXoxZlciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.tfRqoYVQ8HC3L5PLk9g1rXKyNGsWYQWV5k0d0KUoiyE'
        ])->json(
            'POST',
            '/api/auth/deleteNoteById',
            [
                "id" => "12"
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
    }

    /**
     * @test 
     * for Successfull pinned the Node
     */
    public function test_Successfull_Pinned_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/pinNoteById',
            [
                "id" => "4"
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note pinned Successfully']);
    }

    /**
     * @test 
     * for UnSuccessfull pinned the Node
     */
    public function test_UnSuccessfull_Pinned_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/pinNoteById',
            [
                "id" => "12"
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
    }

    /**
     * @test 
     * for Successfull Archive the Node
     */
    public function test_Successfull_Archived_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/archiveNoteById',
            [
                "id" => "4"
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note archived Successfully']);
    }

    /**
     * @test 
     * for Successfull Archive the Node
     */
    public function test_UnSuccessfull_Archived_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/archiveNoteById',
            [
                "id" => "12"
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
    }

    /**
     * @test 
     * for Successfull coloring the Node
     */
    public function test_Successfull_Color_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/colourNoteById',
            [
                "id" => "4",
                "colour" => "green"
            ]
        );
        $response->assertStatus(201)->assertJson(['message' => 'Note coloured Sucessfully']);
    }

    /**
     * @test 
     * for UnSuccessfull coloring the Node
     */
    public function test_UnSuccessfull_Color_Note()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzI0MDA4NywiZXhwIjoxNjQ3MjQzNjg3LCJuYmYiOjE2NDcyNDAwODcsImp0aSI6Ik82ekdYRzVIZ014TVplMk8iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.y3EyYrVm5Dm1IYvIFWRJsEFpo3-_-NPH9slwjDetiZY'
        ])->json(
            'POST',
            '/api/auth/colourNoteById',
            [
                "id" => "12",
                "colour" => "green"
            ]
        );
        $response->assertStatus(404)->assertJson(['message' => 'Notes not Found']);
    }
}
