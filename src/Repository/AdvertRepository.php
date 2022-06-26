<?php


namespace App\Repository;


use App\Entity\Advert;
use App\Traits\StringParsing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdvertRepository extends ServiceEntityRepository
{
    use StringParsing;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function getAdvertsByBrands(string $q)
    {
        $query = $this->createQueryBuilder('a');
        $query->select('c.name as category, a.title, a.content, a.brand, a.model');

        $query->join('a.category', 'c');

        $queryStrings = $this->stringParsing($q);

        foreach ($queryStrings as $queryString){
            $query->orWhere($query->expr()->eq('a.model', $query->expr()->literal($queryString)));
        }

        return $query->orderBy('a.model', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}