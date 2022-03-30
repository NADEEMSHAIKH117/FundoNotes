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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM2NjYxNSwiZXhwIjoxNjQ4MzcwMjE1LCJuYmYiOjE2NDgzNjY2MTUsImp0aSI6IlRPODZYM1VjcXpoTUNwMFIiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Ag8LyZ7vC5T9yjrjecWJebHJ8U657aOsRGIHvvP2a_0'
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
     * Successfull resetpassword
     */
    public function test_SuccessfulResetPassword()
    { {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
            ])->json('POST', '/api/auth/resetpassword', [
                "new_password" => "nadeem123",
                "password_confirmation" => "nadeem123",
                "token" => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9mb3Jnb3RwYXNzd29yZCIsImlhdCI6MTY0ODM2ODExOSwiZXhwIjoxNjQ4MzcxNzE5LCJuYmYiOjE2NDgzNjgxMTksImp0aSI6IkxKMkhpNkVUbWcxakZpQUkiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.63RMYVCws7Nuy2rU5IowM7Je3kM0nPaRmYYZpevuwnk'
            ]);

            $response->assertStatus(200)->assertJson(['message' => 'Password reset successfull!']);
        }
    }
}
