<?php

namespace App\Middleware;

use App\Application;
use App\Model\Entity\Option;
use Cake\Cache\Cache;
use Cake\Console\CommandRunner;
use Cake\Console\Shell;
use Cake\Console\ShellDispatcher;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use Migrations\Migrations;
use Zend\Diactoros\Response\RedirectResponse;

class CustomUpdateMiddleware
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $version = '1.1';
        if ($this->version_checked($version)){
            $this->version_update_1_0();
        }
        return $next($request, $response);
    }

    public function version_update_1_0(){
        $connection = ConnectionManager::get('default');

        //Traffics
        $traffics = TableRegistry::getTableLocator()->get('traffics');
        if (!$traffics->getSchema()->hasColumn('device')){
            $connection->execute('ALTER TABLE traffics ADD device tinyint(4) DEFAULT 0');
        }
    }

    public function version_checked($version){
        $add = true;
        $option = TableRegistry::getTableLocator()->get('Options');
        $mainVersion = $option->find()->where(['name' => 'main_version'])->first();
        if (!$mainVersion){
            $mainVersion = new Option();
        } else {
            $add = false;
            $main_version = $mainVersion->value;
            if(version_compare($version,$main_version,'>')) $add = true;
        }

        if ($add){
            $option->patchEntity($mainVersion,[
                'name' => 'main_version',
                'value' => $version
            ]);
            return $option->save($mainVersion);
        }
        return false;
    }
}
