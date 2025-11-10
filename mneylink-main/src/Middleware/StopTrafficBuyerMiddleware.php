<?php

namespace App\Middleware;

use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;

class StopTrafficBuyerMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $conn = ConnectionManager::get('default');
        $traffic_expired = $conn->execute("SELECT * FROM traffics WHERE views = count AND status = 1")->fetchAll('assoc');
        if (!empty($traffic_expired)){
            foreach ($traffic_expired as $traffic){
                $conn->update('traffics', ['status' => 3], ['id' => $traffic['id']]);
            }
        }
        return $next($request, $response);
    }
}
