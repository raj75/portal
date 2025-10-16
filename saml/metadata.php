<?php
 
/**
 *  SAML Metadata view
 */

require_once '../external_lib/_saml_toolkit_loader.php';

require_once 'settings.php';

try {
    #$auth = new OneLogin_Saml2_Auth($settingsInfo);
    #$settings = $auth->getSettings();
    // Now we only validate SP settings
    $settings = new OneLogin\Saml2\Settings($settings, true);
    $metadata = $settings->getSPMetadata();
    $errors = $settings->validateMetadata($metadata);
    if (empty($errors)) {
        header('Content-type: text/xml');
        // header('Content-Disposition: attachment; filename="metadata.xml"');
        echo $metadata;
    } else {
        throw new OneLogin_Saml2_Error(
            'Invalid SP metadata: '.implode(', ', $errors),
            OneLogin_Saml2_Error::METADATA_SP_INVALID
        );
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
