<?php

namespace Elisad\d7;

class Agent
{
    static public function timeAgent()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/elisad.d7/agentLog.txt';
        file_put_contents($path, date('Y-m-d H:i:s'), FILE_APPEND);
        return '\Elisad\d7\Agent::timeAgent();';
    }
}