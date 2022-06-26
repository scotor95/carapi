<?php


namespace App\Task;


use App\Entity\Advert;
use App\Entity\Category;

class CreateAd
{
    /**
     * @var array
     */
    private $models;

    public function __construct(array $models)
    {
        $this->models = $models;
    }

    public function __invoke($data): ?Advert
    {
        $ad = new Advert();
        $ad->setCategory($data['category']);
        if($ad->getCategory()->getName() == Category::CARS){
            if(!isset($data['brand']) || !in_array($data['brand'], array_keys($this->models)))
            {
                return null;
            }
            if(!isset($data['model']) || !in_array($data['model'], $this->models[$data['brand']]))
            {
                return null;
            }
            $ad->setModel($data['model'])
                ->setBrand($data['brand']);
        }

        $ad->setContent($data['content'])
            ->setTitle($data['title'])
            ;
        return $ad;
    }
}