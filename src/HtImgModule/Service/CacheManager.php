<?php
namespace HtImgModule\Service; 

use HtImgModule\Options\CacheOptionsInterface;
use Imagine\Image\ImageInterface;

class CacheManager
{
    /**
     * @var CacheOptionsInterface 
     */
    protected $cacheOptions;
    
    /**
     * Constructor
     * 
     * @param CacheOptionsInterface $cacheOptions
     */
    public function __construct(CacheOptionsInterface $cacheOptions)
    {
        $this->cacheOptions = $cacheOptions;
    }
    
    public function cacheExists($relativeName, $filter)
    {
        $cachePath = $this->getCachePath($relativeName, $filter);
        if (is_readable($cachePath)) {
            if ((time() - filemtime($cachePath)) < $this->cacheOptions->getCacheExpiry()) {
                return true;
            }
            unlink($cachePath);           
        }

        return false;
    }  
    
    public function getCacheUrl($relativeName, $filter)
    {
        return $this->cacheOptions->getCachePath() . '/' . $filter . '/'. $relativeName;        
    }
    
    public function getCachePath($relativeName, $filter)
    {
        return $this->cacheOptions->getWebRoot() . '/' . $this->getCacheUrl($relativeName, $filter);
    }      
    
    public function createCache($relativeName, $filter, ImageInterface $image)
    {
        $cachePath = $this->getCachePath($relativeName, $filter);
        if (!is_dir(dirname($cachePath))) {
            mkdir(dirname($cachePath), 0755, true);
        }
        $image->save($cachePath); 
    }
       
}