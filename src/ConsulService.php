<?php 
namespace Wenwen1993\Consul;


class ConsulService {

    /**
     * @return \wenwen1993\Consul\Agent
     */
    public function to()
    {
        $agent = new Agent();

        return $agent;
    }
}