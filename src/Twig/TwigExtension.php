<?php
namespace AdsBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension implements GlobalsInterface{

    private static $stylesLoaded = false;
    private static $scriptsLoaded = false;
    private static $adsSectionLoaded = false;
    
    public function __construct(private ParameterBagInterface $parameterBag)
    {}

    public function getGlobals(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction("ads", [$this, "loadAds"], ['is_safe' => ['html']]),
            new TwigFunction("ads_styles", [$this, "loadStyles"], ['is_safe' => ['html']]),
            new TwigFunction("ads_scripts", [$this, "loadScripts"], ['is_safe' => ['html']])
        ];
    }


    public function loadAds(){
        if(self::$adsSectionLoaded) throw new \Exception("The ads section has already been loaded");
        self::$adsSectionLoaded = true;
        return '
            <div id="ad">
                <div id="ad__close-btn"><i class="fas fa-times"></i></div>
                <div id="ad__container"></div>
                <div id="ad__time-bar"></div>
            </div>
        ';
    }

    public function loadStyles(){
        if(self::$stylesLoaded) throw new \Exception("The ads styles have already been loaded");
        self::$stylesLoaded = true;
        return '<link rel="stylesheet" href="/bundles/ads/ads.css"/>';
    }

    public function loadScripts(){
        if(self::$scriptsLoaded) throw new \Exception("The ads scripts have already been loaded");
        self::$scriptsLoaded = true;
        return '<script defer src="/bundles/ads/ads.js"></script>';
    }
    
}