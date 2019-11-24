<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Psr7\Request;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function testBasicTest2()
    {
        $response = $this->post('/');
        
        $response->assertStatus(405);
    }
    
    /*public function send_non_integer_adult_number_leads_to_failure_of_search_results(){
        $request = $this->mockRequestData(['adult_number' => 'dummy']);
        $response = $this->get(route('hotels.third.search', $request));
        $this->assertValidationError($response, 'adult_number');
    }
    
    private function assertValidationError(TestResponse $response, string $field){
        $response->assertStatus(422);
        // The returned json should contain the validation error on the given field
        $errorMessages = $response->decodeResponseJson()['error']['message'];
        $this->assertArrayHasKey($field, $errorMessages);
    }
    
    
    private function mockRequestData(array $cutom_attributes = []){
        $request = [
            'from'         => $cutom_attributes['from'] ?? Carbon::today()->format('y-m-d'),
            'to'           => $cutom_attributes['to'] ?? Carbon::today()->addDays(3)->format('y-m-d'),
            'city'         => 'ATH',
            'adult_number' => $cutom_attributes['adult_number'] ?? rand(4, 10)
        ];
        return $request;
    }
    
    public function test_call_api_without_authentication(){
        $data = [
            'from_date'      => "2019-11-03",
            'to'             => "2019-12-03",
            'city'           => 'AUH',
            'adults_ number' => 10,
        ];
        $response = $this->get(route('hotels.third.search', $data));
        $response->assertStatus(400);
    }*/
    
}
