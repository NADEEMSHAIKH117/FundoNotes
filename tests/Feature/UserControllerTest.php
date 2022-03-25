<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Successfull registration
     * This test is for to see if user is getting Register Successfully
     *
     * @test
     */
    public function test_SuccessfulRegistration()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/auth/register', [
                "firstname" => "Nadeem",
                "lastname" => "Shaikh",
                "email" => "nadeem1236@gmail.com",
                "password" => "nadeem@123",
                "password_confirmation" => "nadeem@123"
            ]);

        $response->assertStatus(200)->assertJson(['message' => 'User successfully registered']);
    }

    /**
     * @test for
     * This test is for to see 
     * Already Registered User
     */
    public function test_If_User_Already_Registered()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/auth/register', [
                "firstname" => "Nadeem",
                "lastname" => "Shaikh",
                "email" => "nadeemshaikh1171998@gmail.com",
                "password" => "nadeem123",
                "password_confirmation" => "nadeem123"
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'The email has already been taken']);
    }

    /**
     * @test for
     * Successfull login
     */

    public function test_SuccessfulLogin()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json(
            'POST',
            '/api/auth/login',
            [
                "email" => "nadeemshaikh1171998@gmail.com",
                "password" => "nadeem123"
            ]
        );
        $response->assertStatus(200)->assertJson(['message' => 'Login successfull']);
    }

    /**
     * @test for
     * Unsuccessfull Login
     */

    public function test_UnSuccessfulLogin()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])->json(
            'POST',
            '/api/auth/login',
            [
                "email" => "nadeemshaikh420@gmail.com",
                "password" => "nadeem123"
            ]
        );
        $response->assertStatus(401)->assertJson(['message' => 'email not found register first']);
    }

    /**
     * @test for
     * Successfull Logout
     */
    public function test_SuccessfulLogout()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzIwMDc3MiwiZXhwIjoxNjQ3MjA0MzcyLCJuYmYiOjE2NDcyMDA3NzIsImp0aSI6Iml1R0RpS2I5a3MwTXp6VloiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.xsTmrHD7o7ZZ6TIv4B9DGeo1gkRqaVoBnWCmvROUSgM'
        ])->json('POST', '/api/auth/logout');
        $response->assertStatus(201)->assertJson(['message' => 'User successfully logget out']);
    }

    /**
     * @test for
     * Successfull forgotpassword
     */
    public function test_SuccessfulForgotPassword()
    { {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
            ])->json('POST', '/api/auth/forgotpassword', [
                "email" => "nadeemshaikh1171998@gmail.com"
            ]);

            $response->assertStatus(200)->assertJson(['message' => 'password reset link genereted in mail']);
        }
    }
    /**
     * @test for
     * UnSuccessfull forgotpassword
     */
    public function test_IfGiven_InvalidEmailId()
    { {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
            ])->json('POST', '/api/auth/forgotpassword', [
                "email" => "nadeem420@gmail.com"
            ]);

            $response->assertStatus(404)->assertJson(['message' => 'we can not find a user with that email address']);
        }
    }
    /**
     * @test for
     * Successfull resetpassword
     */
    public function test_SuccessfulResetPassword()
    { {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
            ])->json('POST', '/api/auth/resetpassword', [
                "new_password" => "nadeem123",
                "confirm_password" => "nadeem123",
                "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvYXV0aFwvZm9yZ290cGFzc3dvcmQiLCJpYXQiOjE2NDcyMDE5NzEsImV4cCI6MTY0NzIwNTU3MSwibmJmIjoxNjQ3MjAxOTcxLCJqdGkiOiJJeFpxUWZrbzNYRTY1QWU2Iiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.FQiX58aiJWyW3ttPJGkhf-NR9m3fHOiUMlABoYXLX5o"
            ]);

            $response->assertStatus(201)->assertJson(['message' => 'Password reset successfull!']);
        }
    }
}
