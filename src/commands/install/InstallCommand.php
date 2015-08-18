<?php
namespace PharIo\Phive {

    use TheSeer\CLI\Command;

    class InstallCommand implements Command {

        /**
         * @var InstallCommandConfig
         */
        private $config;

        /**
         * @var PharService
         */
        private $pharService;

        /**
         * InstallCommand constructor.
         *
         * @param InstallCommandConfig $config
         * @param PharService          $pharService
         */
        public function __construct(InstallCommandConfig $config, PharService $pharService) {
            $this->config = $config;
            $this->pharService = $pharService;
        }

        public function execute() {
            $this->pharService->installByUrl($this->config->getPharUrl(), $this->config->getWorkingDirectory());
        }

    }

}
