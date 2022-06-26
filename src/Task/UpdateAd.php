<?php


namespace App\Task;


use App\Entity\Advert;
use App\Entity\Category;

class UpdateAd
{
    /**
     * @var array
     */
    private $models;

    public function __construct(array $models)
    {
        $this->models = $models;
    }

    public function __invoke($ad, $data)
    {
        if(isset($data['category'])){
            $ad->setCategory($data['category']);
        }


        if($ad->getCategory()->getName() == Category::CARS){
            if(isset($data['brand'])){
                if(!isset($data['model']) || !in_array($data['brand'], array_keys($this->models)))
                {
                    return null;
                }
                $ad->setBrand($data['brand']);
            }
            if(isset($data['model'])){
                if(!in_array($data['model'], $this->models[$ad->getBrand()]))
                {
                    return null;
                }
                $ad->setModel($data['model']);

            }
        }


        if(isset($data['content']))
            $ad->setContent($data['content']);
        if(isset($data['title']))
            $ad->setTitle($data['title']);
        return $ad;
    }
}