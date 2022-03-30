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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
        ])->json(
            'POST',
            '/api/auth/updateNoteById',
            [
                "id" => "2",
                "title" => "updated title",
                "description" => "updated description",
            ]
        );
        $response->assertStatus(200)->assertJson(['message' => 'Note updated Successfully']);
    }

    /**
     * @test 
     * for Unsuccessfull Note Updatation
     */

    public function test_FailUpdateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
        ])->json(
            'POST',
            '/api/auth/updateNoteById',
            [
                "id" => "1",
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
        ])->json(
            'POST',
            '/api/auth/deleteNoteById',
            [
                "id" => "12"
            ]
        );
        $response->assertStatus(200)->assertJson(['message' => 'Note deleted Successfully']);
    }

    /**
     * @test 
     * for Successfull Deletion of Node
     */
    public function test_UnSuccessfullDeleteNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
        ])->json(
            'POST',
            '/api/auth/archiveNoteById',
            [
                "id" => "5"
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
        ])->json(
            'POST',
            '/api/auth/colourNoteById',
            [
                "id" => "10",
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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2ODcyNSwiZXhwIjoxNjQ4MzcyMzI1LCJuYmYiOjE2NDgzNjg3MjUsImp0aSI6IjNEM3NIaElOdUU5QmlOaGYiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.-1NwGP3RoYpk7ut04Ha43sOcaeY774PQV50-Yhcsw1Q'
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
