# TYPO3-ext-tw_places

**Please note:**

There already is a basic tw_places extension with the version number 1.0.0 which is incompatible
with this extension. This extension is rewritten from scratch for TYPO3 10, relying on some
ideas first implemented in the older extension. That's why this extension will start with
version number 2.0.0.

## Configuration

### Form Framework 
* plugin.tx_form.settings.yamlConfigurations.20 = EXT:tw_places/Configuration/Yaml/Form/CustomFormSetup.yaml

* Possible configuration values for FormFactory when calling `<formvh:render factoryClass="Tollwerk\TwPlaces\Domain\Factory\Form\SearchFormFactory"/>`
    * theme (string), will be set as property "theme" in the search field element     
