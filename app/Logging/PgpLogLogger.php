<?php

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;

class PgpLogLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('pgplog');

        // Obtém apenas o ano da data atual
        $year = date('Y');

        // Adicione um manipulador de arquivo rotativo que gera um novo arquivo por ano
        $logFilePath = storage_path("logs/pgplog.log");
        $logger->pushHandler(new RotatingFileHandler($logFilePath, 365, Level::Debug, true, null, false, 'Y'));

        return $logger;
    }
}
