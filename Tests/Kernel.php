<?php
namespace Plugin\FlashSale\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Eccube\Kernel
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getProjectDir()
    {
        $dir = parent::getProjectDir();
        $dir = str_replace(
            DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Plugin'.DIRECTORY_SEPARATOR.'FlashSale',
            '',
            $dir
        );
        return $dir;
    }

    /**
     * {@inheritdoc}
     *
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    public function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $confDir = $this->getProjectDir().'/app/config/eccube';
        $loader->load($confDir.'/packages/*'.self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir.'/packages/'.$this->environment)) {
            $loader->load($confDir.'/packages/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        }
        $loader->load($confDir.'/services'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/services_'.$this->environment.self::CONFIG_EXTS, 'glob');

        // プラグインのservices.phpをロードする.
        $dir = $this->getProjectDir().'/app/Plugin/*/Resource/config';
        $loader->load($dir.'/services'.self::CONFIG_EXTS, 'glob');
        $loader->load($dir.'/services_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function loadEntityProxies()
    {
        foreach (glob($this->getProjectDir().'/app/proxy/entity/*.php') as $file) {
            require_once $file;
        }
    }
}
