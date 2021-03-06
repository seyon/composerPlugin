<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace Symbb\ExtensionBundle;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Yaml\Parser;
use Composer\Package\Link;
use Composer\Package\LinkConstraint\VersionConstraint;

class ComposerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        
        $extensionList  = \Symbb\ExtensionBundle\Api::getExtensions();

        $package        = $composer->getPackage();
        
        $required   = $package->getRequires();
        foreach($extensionList as $extensionKey => $extension){ 
            if($extension->isEnabled() && $extension->hasComposer()){
                $constraint = $extension->getVersionConstraint();
                $v = $extension->getVersion();
                if(empty($constraint)){
                   $constraint = "=";
                }
                $v = new VersionConstraint($constraint, $v); 
                $link = new Link('symbb/symbb', $extension->getPackage(),  $v);
                $required[$extension->getPackage()] = $link;
            }
        }
        
        $package->setRequires($required);
        
    }
    
}
