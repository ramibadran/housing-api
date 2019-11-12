<?php
namespace App\Transformers;

use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

/**
 * Class Respond.
 *
 * @package app\Respond
 *
 * @author Rami Badran <ramibadran.82@gmail.com>
 */
abstract class Respond{
    
    protected function scope($item, $itemTransformer, $fractal, $type){
        switch($type){
            case 'item':
                return $this->respondWithItem($item,$itemTransformer,$fractal);
            case 'paginate':
                return $this->respondPaginatorWithCollection($item,$itemTransformer,$fractal);
            case 'collection':
                return $this->respondWithCollection($item,$itemTransformer,$fractal);
        }
    }
    
    private function respondPaginatorWithCollection($item,$itemTransformer,$fractal){
        $scope = new Collection($item, $itemTransformer);
        $scope->setPaginator(new IlluminatePaginatorAdapter($item));
        $scopeOfData = $fractal->createData($scope); // Transform data
        return $scopeOfData->toArray();
    }
    
    private function respondWithCollection($item,$itemTransformer,$fractal){
        $scope = new Collection($item, $itemTransformer);
        $scopeOfData = $fractal->createData($scope); // Transform data
        return $scopeOfData->toArray();
    }
    
    private function respondWithItem($item,$itemTransformer,$fractal){
        
    }
}