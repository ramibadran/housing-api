<?php
namespace App\Http\Controllers\ThirdPart;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;


class HotelController extends Controller{
    /*
     * This function responsible to return data from third party service "best hotel" based on user query,
     * i assumend my result based on the following query example
     * input city     example AMM
     * fromDate       example 2019-2-12
     * toDate         example 2019-2-15
     * numberOfAdults example 2
     */
    public function getBestHotel(Request $request){
        $hotels['data'] = array([
                            'hotel'         => 'best_hotel_1',
                            'hotelRate'     => '3',
                            'hotelFare'     => '20,23',
                            'roomAmenities' => 'king bed,extra bed,delux,tv'
                        ],[
                            'hotel'         => 'best_hotel_2',
                            'hotelRate'     => '4',
                            'hotelFare'     => '25,23',
                            'roomAmenities' => 'king bed,2 extra bed,delux,tv,see view'
                        ]
            
        );
        return response()->json($hotels);
    }
    
    /*
     * This function responsible to return a data from third party service "crazy hotel" based on user query,
     * i assumend my result based on the following query example
     * input city     example AMM
     * fromDate       example 2019-02-12T10:15:30Z
     * toDate         example 2011-12-15T10:15:30Z
     * adultsCount    example 2
     */
    public function getCrazyHotel(Request $request){
        $hotels['data'] = array([
            'hotelName' => 'crazy_hotel_1',
            'rate'      => '**',
            'price '    => '20,23',
            'discount'  => null,
            'amenities' => 'king bed,extra bed,delux,tv'
        ],[
            'hotelName' => 'crazy_hotel_2',
            'rate '     => '*',
            'price '    => '25,23',
            'discount'  => null,
            'amenities' => 'king bed,2 extra bed,delux,tv,see view'
        ]
            
        );
        return response()->json($hotels);
    }
}

                