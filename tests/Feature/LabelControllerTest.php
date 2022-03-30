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
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json(
            'POST',
            '/api/auth/createLabel',
            [
                "labelname" => "new testcase2",
            ]
        );

        $response->assertStatus(201)->assertJson(['message' => 'Label added Sucessfully']);
    }

    //read all labels success
    public function test_IfGiven_AuthorisedToken_AndReturnAllLabels_SuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json('GET', '/api/auth/displayLabelById');

        $response->assertStatus(200)->assertJson(['message' => 'All Labels are Fetched Successfully']);
    }

    //read all labels error
    public function test_IfGiven_WrongAuthorisedToken_AndReturnAllLabels_ErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MzE3MywiZXhwIjoxNjQ4Mzc2NzczLCJuYmYiOjE2NDgzNzMxNzMsImp0aSI6IkVYZUl6Mk52cmF3aFVaTmgiLCJzdWIiOjUsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.6UBK-ghUL2sl7uOQ3oZGr0yTYlxemJQq3U4-HuYFeK4'
        ])->json('GET', '/api/auth/displayLabelById');

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }

    //label update success
    public function test_IfGiven_Label_idAnd_LabelNameAndToken_ShouldValidate_AndReturnUpdateSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json(
            'POST',
            '/api/auth/updateLabelById',
            [
                "id" => 1,
                "labelname" => "Label update",
            ]
        );

        $response->assertStatus(200)->assertJson(['message' => 'Label updated Sucessfully']);
    }

    //label update error
    public function test_IfGiven_WrongLabel_idAnd_LabelNameAndToken_ShouldValidate_AndReturnUpdateErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json(
            'POST',
            '/api/auth/updateLabelById',
            [
                "id" => 20,
                "labelname" => "Label update",
            ]
        );

        $response->assertStatus(404)->assertJson(['message' => 'Label not Found']);
    }

    //label delete success
    public function test_IfGiven_Label_idAnd_ShouldValidate_AndReturnDeleteSuccessStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json(
            'POST',
            '/api/auth/deleteLabelById',
            [
                "id" => 1,
            ]
        );

        $response->assertStatus(201)->assertJson(['message' => 'Label deleted Successfully']);
    }

    //delete error
    public function test_IfGiven_WrongLabel_idAnd_ShouldValidate_AndReturnDeleteErrorStatus()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0ODM3MjE5NCwiZXhwIjoxNjQ4Mzc1Nzk0LCJuYmYiOjE2NDgzNzIxOTQsImp0aSI6InVkVm54cU9oSHh0ck83VkciLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gIirND0vl62hejSUoRCnGSaD9f1cskUd2Qm-d3XmEFQ'
        ])->json(
            'POST',
            '/api/auth/deleteLabelById',
            [
                "id" => 20,
            ]
        );

        $response->assertStatus(404)->assertJson(['message' => 'Invalid authorization token']);
    }
}
