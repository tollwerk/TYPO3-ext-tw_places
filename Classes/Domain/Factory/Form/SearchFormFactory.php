<?php


namespace Tollwerk\TwPlaces\Domain\Factory\Form;


use Tollwerk\TwBase\Domain\Model\UnsubmittableFormDefinition;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Form\Domain\Configuration\ConfigurationService;
use TYPO3\CMS\Form\Domain\Factory\AbstractFormFactory;
use TYPO3\CMS\Form\Domain\Model\FormDefinition;

/**
 * Class SearchFormFactory
 *
 * Form for seraching places by latitude:longitude
 * or fuzzy location search string
 *
 * @package Tollwerk\TwPlaces\Domain\Factory\Form
 */
class SearchFormFactory extends AbstractFormFactory
{
    /**
     * The form key. Should be overwritten by the extending class.
     *
     * @var string
     */
    protected static $key = 'search';

    /**
     * The typoscript setup for plugin.tx_twrws
     *
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var FormDefinition
     */
    protected $form = null;

    /**
     * Constructor
     */
    public function __construct(ObjectManager $objectManager, ConfigurationManager $configurationManager)
    {
        $this->objectManager = $objectManager;
        $this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TwPlaces');
    }

    /**
     * @param array $configuration
     * @param string|null $prototypeName
     * @return FormDefinition
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public function build(array $configuration, string $prototypeName = null): FormDefinition
    {
        // Set configuration
        $this->configuration = $configuration;
        $configurationService = $this->objectManager->get(ConfigurationService::class);
        $prototypeConfiguration = $configurationService->getPrototypeConfiguration('tx_twplaces_search');
        $prototypeConfiguration['overrideConfiguration'] = $this->configuration;

        // Basic form setup
        $this->form = $this->objectManager->get(
            UnsubmittableFormDefinition::class,
            static::$key,
            $prototypeConfiguration
        );
        $this->form->setRenderingOption('honeypot', ['enable' => false]);
        $this->form->setRenderingOption('controllerAction', 'searchForm');

        // Create form page and fields
        $page = $this->form->createPage('search');
        $geoselectField = $page->createElement('geoselect', 'Geoselect');

        $this->triggerFormBuildingFinished($this->form);
        return $this->form;
    }
}