<?php
namespace PharIo\Phive {

    class PharService {

        /**
         * @var PharDownloader
         */
        private $downloader;

        /**
         * @var PharInstaller
         */
        private $installer;

        /**
         * @var PharRepository
         */
        private $repository;

        /**
         * @param PharDownloader $downloader
         * @param PharInstaller  $installer
         * @param PharRepository $repository
         */
        public function __construct(PharDownloader $downloader, PharInstaller $installer, PharRepository $repository) {
            $this->downloader = $downloader;
            $this->installer = $installer;
            $this->repository = $repository;
        }

        /**
         * @param Url  $pharUrl
         *
         * @param      $destination
         * @param bool $makeCopy
         *
         * @return File
         * @throws PharRepositoryException
         * @throws VerificationFailedException
         */
        public function installByUrl(Url $pharUrl, $destination, $makeCopy = false) {
            $name = $this->getPharName($pharUrl);
            $version = $this->getPharVersion($pharUrl);
            if (!$this->repository->hasPhar($name, $version)) {
                $phar = new Phar($name, $version, $this->downloader->download($pharUrl));
            } else {
                $phar = $this->repository->getPhar($name, $version);
            }
            $this->install($phar, $destination, $makeCopy);
        }

        /**
         * @param Phar   $phar
         * @param string $destination
         * @param bool   $makeCopy
         */
        public function install(Phar $phar, $destination, $makeCopy = false) {
            $destination = $destination . '/' . $phar->getName();
            $this->installer->install($phar->getFile(), $destination, $makeCopy);
            $this->repository->addUsage($phar, $destination);
        }

        /**
         * @param Url $url
         *
         * @return string
         */
        private function getPharName(Url $url) {
            $filename = pathinfo((string)$url, PATHINFO_FILENAME);
            preg_match('/(.*)-[0-9].[0-9].[0-9].*/', $filename, $matches);

            return $matches[1];
        }

        /**
         * @param URl $url
         *
         * @return Version
         */
        private function getPharVersion(URl $url) {
            $filename = pathinfo((string)$url, PATHINFO_FILENAME);
            preg_match('/-([0-9].[0-9].[0-9].*)/', $filename, $matches);

            return new Version($matches[1]);
        }
    }

}

