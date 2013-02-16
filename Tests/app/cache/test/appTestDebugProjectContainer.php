<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * appTestDebugProjectContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class appTestDebugProjectContainer extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array();
        $this->scopeChildren = array();
    }

    /**
     * Gets the 'devtrw_google_maps.directions' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Devtrw\Bundle\GoogleMapsBundle\Service\Directions A Devtrw\Bundle\GoogleMapsBundle\Service\Directions instance.
     */
    protected function getDevtrwGoogleMaps_DirectionsService()
    {
        return $this->services['devtrw_google_maps.directions'] = new \Devtrw\Bundle\GoogleMapsBundle\Service\Directions($this->get('devtrw_google_maps.google_maps_client'));
    }

    /**
     * Gets the 'devtrw_google_maps.google_maps_client' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Devtrw\Bundle\GoogleMapsBundle\Client\GoogleMapsClient A Devtrw\Bundle\GoogleMapsBundle\Client\GoogleMapsClient instance.
     */
    protected function getDevtrwGoogleMaps_GoogleMapsClientService()
    {
        return $this->services['devtrw_google_maps.google_maps_client'] = $this->get('guzzle.service_builder')->get('google.maps');
    }

    /**
     * Gets the 'guzzle.service_builder' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Guzzle\Service\Builder\ServiceBuilder A Guzzle\Service\Builder\ServiceBuilder instance.
     */
    protected function getGuzzle_ServiceBuilderService()
    {
        $a = new \Guzzle\Log\ArrayLogAdapter();

        $b = new \Guzzle\Plugin\Log\LogPlugin($a);

        $this->services['guzzle.service_builder'] = $instance = call_user_func(array('Guzzle\\Service\\Builder\\ServiceBuilder', 'factory'), '/home/vagrant/GoogleMapsBundle/DependencyInjection/../Resources/config/guzzle-services.json');

        $instance->addGlobalPlugin($b);
        $instance->addGlobalPlugin($b);

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!array_key_exists($name, $this->parameters)) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return array_key_exists(strtolower($name), $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }
    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'kernel.root_dir' => '/home/vagrant/GoogleMapsBundle/Tests/app',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
            'kernel.name' => 'app',
            'kernel.cache_dir' => '/home/vagrant/GoogleMapsBundle/Tests/app/cache/test',
            'kernel.logs_dir' => '/home/vagrant/GoogleMapsBundle/Tests/app/logs',
            'kernel.bundles' => array(
                'DevtrwGoogleMapsBundle' => 'Devtrw\\Bundle\\GoogleMapsBundle\\DevtrwGoogleMapsBundle',
                'DdeboerGuzzleBundle' => 'Ddeboer\\GuzzleBundle\\DdeboerGuzzleBundle',
            ),
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'appTestDebugProjectContainer',
            'devtrw_google_maps.google_maps_client.name' => 'google.maps',
            'devtrw_google_maps.guzzle.config' => '/home/vagrant/GoogleMapsBundle/DependencyInjection/../Resources/config/guzzle-services.json',
            'devtrw_google_maps.service.directions.class' => 'Devtrw\\Bundle\\GoogleMapsBundle\\Service\\Directions',
            'devtrw_google_maps.google_maps_client.class' => 'Devtrw\\Bundle\\GoogleMapsBundle\\Client\\GoogleMapsClient',
            'guzzle.service_builder.class' => 'Guzzle\\Service\\Builder\\ServiceBuilder',
            'guzzle.service_builder_factory.class' => 'Guzzle\\Service\\Builder\\ServiceBuilder',
            'guzzle.plugin.log.class' => 'Guzzle\\Plugin\\Log\\LogPlugin',
            'guzzle.plugin.log.monolog.adapter.class' => 'Guzzle\\Log\\MonologLogAdapter',
            'guzzle.plugin.log.array.adapter.class' => 'Guzzle\\Log\\ArrayLogAdapter',
            'guzzle.data_collector.class' => 'Ddeboer\\GuzzleBundle\\DataCollector\\HttpDataCollector',
            'guzzle.cache.adapter.doctrine.class' => 'Guzzle\\Cache\\DoctrineCacheAdapter',
            'guzzle.cache.adapter.zend.class' => 'Guzzle\\Cache\\Zf1CacheAdapter',
            'guzzle.cache.driver.apc.class' => 'Doctrine\\Cache\\ApcCache',
            'guzzle.cache.driver.array.class' => 'Doctrine\\Cache\\ArrayCache',
            'guzzle.service_builder.configuration_file' => '/home/vagrant/GoogleMapsBundle/DependencyInjection/../Resources/config/guzzle-services.json',
        );
    }
}
