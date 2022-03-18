<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_IfGiven_LabelName_ShouldValidate_AndReturnSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NzYzMDYzMywiZXhwIjoxNjQ3NjM0MjMzLCJuYmYiOjE2NDc2MzA2MzMsImp0aSI6Ik5pYXlQQmV5YUlFWlprNVEiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gHqOPyUk8_SS1FLMWrJvNTaMniioVTpkIFpTcgrRvus'
        ])->json('POST', '/api/auth/createLabel', 
        [
            "labelname" => "new test new",
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'Label added Sucessfully']);
    }

        //create label Error
        public function test_IfGiven_LabelName_ShouldValidate_AndReturnErrorsStatus()
        {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
            ])->json('POST', '/api/auth/createLabel', 
                [
                    "labelname" => "new label",
                ]
            );
    
            $response->assertStatus(401)->assertJson(['message' => 'Label Name already exists']);
        }

            //read all labels success
    public function test_IfGiven_AuthorisedToken_AndReturnAllLabels_SuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
        ])->json('GET', '/api/auth/displayLabelById');

        $response->assertStatus(201)->assertJson(['message' => 'Labels Fetched  Successfully']);
    }

    //read all labels error
    public function test_IfGiven_WrongAuthorisedToken_AndReturnAllLabels_ErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer J0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
        ])->json('GET', '/api/auth/displayLabelById');

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }

        //label update success
        public function test_IfGiven_Label_idAnd_LabelNameAndToken_ShouldValidate_AndReturnUpdateSuccessStatus()
        {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
            ])->json('POST', '/api/auth/updateLabelById',
            [
                "id" => 1,
                "labelname" => "Label update",
            ]);
    
            $response->assertStatus(201)->assertJson(['message' => 'Label updated Sucessfully']);
        }
    
        //label update error
        public function test_IfGiven_WrongLabel_idAnd_LabelNameAndToken_ShouldValidate_AndReturnUpdateErrorStatus()
        {
            $response = $this->withHeaders([
                'Content-Type' => 'Application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
            ])->json('POST', '/api/auth/updupdateLabelByIdatelabel',
            [
                "id" => 20,
                "labelname" => "Label update",
            ]);
    
            $response->assertStatus(404)->assertJson(['message' => 'Label not Found']);
        }

            //label delete success
    public function test_IfGiven_Label_idAnd_ShouldValidate_AndReturnDeleteSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
        ])->json('POST', '/api/auth/deleteLabelById',
        [
            "id" => 2,
        ]);

        $response->assertStatus(201)->assertJson(['message' => 'Label successfully deleted']);
    }

    //delete error
    public function test_IfGiven_WrongLabel_idAnd_ShouldValidate_AndReturnDeleteErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer J0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0MjA0NzY3NywiZXhwIjoxNjQyMDUxMjc3LCJuYmYiOjE2NDIwNDc2NzcsImp0aSI6IlVzRXNPbG5LZDFRYk55ZUEiLCJzdWIiOjksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.JBmXbrnLVPErwkeLmiF2G3JBNIh1Odyx3CHD8aTzZU0'
        ])->json('POST', '/api/auth/deleteLabelById',
        [
            "id" => 20,
        ]);

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }
}
