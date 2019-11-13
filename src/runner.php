<?php
/**
 * Initialisation script for the module
 * @author Aetiom <aetiom@protonmail.com>
 * @package zeus
 * @version 1.0
 */

$config = $this->getConfig();

// Retrieve form parameters from config file
$formParam = $config->getConfigByFileName('config.php');


//$this->collection = new BfwScribe\Collection('bfw-form');



/**
 * Create new Zeus form
 * @return \BfwForm form
 */
$this->build = function(string $id) use($formParam) {
    
    $form = new \BfwForm\Form($id, $formParam);
    
    $formPresets = $this->getConfig()->getConfigByFilename('formPresets.php');
    $inputPresets = $this->getConfig()->getConfigByFilename('inputPresets.php');

    if (!isset($formPresets[$id])) {
        throw new \Exception('no preset found for '.$id.' form');
    }

    $map = $formPresets[$id];
    
    foreach ($map as $name => $opts) {
        if (!isset($inputPresets[$name])) {
            continue;
        }
        
        $map[$name] = array_merge_recursive($opts, $inputPresets[$name]);
    }
    
    $form->setInputs($map);

    

    $app = \BFW\Application::getInstance();

    if ($app->getModuleList()->hasModule('bfw-scribe')) {
        $bfwScribe = $app->getModuleList()->getModuleByName('bfw-scribe');
        
        foreach ($form->getInputs() as $name => $input) {
            $bfwScribe->handler->addCollection($id.'-'.$name, $input->getCollection());
        }

        //var_dump($bfwTwig);
        //exit;
    }


    return $form;
};

/**
 * Get corresponding Zeus form input
 * @return \BfwForm\Input form input
 */
$this->getFormInput = function(string $id, string $name) use($formParam) {
    
    $formPresets = $this->getConfig()->getConfigByFilename('formPresets.php');
    $inputPresets = $this->getConfig()->getConfigByFilename('inputPresets.php');
    
    if (!isset($formPresets[$id])) {
        throw new \Exception('no preset found for '.$id.' form');
    }
    
    if (!isset($formPresets[$id][$name])) {
        throw new \Exception('no preset found for '.$name.
                                ' form input in ' .$id.' form preset');
    }
    
    if (!isset($inputPresets[$name])) {
        throw new \Exception('no preset found for '.$name.' form input');
    }
    
    $preset = array_merge($formPresets[$id][$name], $inputPresets[$name]);
    $preset['errors'] = $formParam['errors'];
    
    $options = new \BfwForm\Options($formParam);
    $input = new \BfwForm\Input($name, $preset, $options);
    
    if (isset($preset['formerInput'])) {
        $formerInput = $this->getFormInput($id, $preset['formerInput']);
        $input->link($formerInput);
    }
    
    return $input;
};

/**
 * Get corresponding Zeus form token
 * @return \BfwForm\Options token
 */
$this->getFormToken = function(string $id) use($formParam) {
    
    $options = new \BfwForm\Options($formParam);
    return new \BfwForm\Token($id, $options);
};