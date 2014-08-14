<?php

namespace ForkCMS\Bundle\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;

class InstallerController extends Controller
{
    public function step1Action()
    {
        $this->checkInstall();

        $libraryFolder = $this->container->getParameter('kernel.root_dir')
            . '/../library';

        $fs = new Filesystem();
        if ($fs->exists($libraryFolder)) {
            $this->get('session')->set('path_library', $libraryFolder);

            return $this->redirect($this->generateUrl('install_step2'));
        } else {
            return $this->render(
                'ForkCMSInstallerBundle:Installer:step1.html.twig'
            );
        }
    }

    public function step2Action()
    {
        $this->checkInstall();
        var_dump('2');exit;
    }

    public function step3Action()
    {
        $this->checkInstall();
        var_dump('3');exit;
    }

    public function step4Action()
    {
        $this->checkInstall();
        var_dump('4');exit;
    }

    public function step5Action()
    {
        $this->checkInstall();
        var_dump('5');exit;
    }

    public function step6Action()
    {
        $this->checkInstall();
        var_dump('6');exit;
    }

    public function step7Action()
    {
        $this->checkInstall();
        var_dump('7');exit;
    }

    public function noStepAction()
    {
        $this->checkInstall();

        return $this->redirect($this->generateUrl('install_step1'));
    }

    protected function checkInstall()
    {
        $fs = new FileSystem();
        $installCacheDir = $this->container->getParameter('kernel.cache_dir');
        if ($fs->exists($installCacheDir . 'installed.txt')) {
            exit('This Fork has already been installed. To reinstall, delete
                 installed.txt from the ' . $installCacheDir . ' directory. To log in,
                 <a href="/private">click here</a>.');
        }
    }
}