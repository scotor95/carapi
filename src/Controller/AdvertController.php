<?php


namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Category;
use App\Repository\AdvertRepository;
use App\Task\CreateAd;
use App\Task\UpdateAd;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{

    /**
     * @param Request $request
     * @Route("/adverts", name="adverts_list", methods={"GET"})
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAdvertsAction(
        Request $request,
        AdvertRepository $advertRepository
    ): Response
    {
        $query = $request->query->get('model','');

        $res = $advertRepository->getAdvertsByBrands($query);

        return $this->json(['ads' => $res], 200);
    }

    /**
     * @Route("/adverts", name="adverts_create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addAdvertsAction(
        Request $request,
        EntityManagerInterface $entityManager,
        CreateAd $createAd
    )
    {
        $request = json_decode($request->getContent(), true);
        //dump($request);
        $categoryId = isset($request['category']) ? $request['category']: null;
        if(!$categoryId) return $this->json(['error' => "absence d'une categorie"]);
        $category = $entityManager->getRepository(Category::class)->find($categoryId);
        if(!$category) return $this->json(['error' => "Categorie introuvable"]);

        $request['category'] = $category;

        $advert = $createAd($request);
        if($advert){
            $entityManager->persist($advert);
            $entityManager->flush();
            return $this->json(['success' => 'Annonce créée'], 200);
        }
        else{
            return $this->json(['error' => 'Annonce non créée']);
        }
    }

    /**
     * @Route("/adverts/{id}", name="adverts_update", methods={"PUT"})
     * @param string $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UpdateAd $updateAd
     * @return Response
     */
    public function updateAdvertsAction(
        string $id,
        Request $request,
        EntityManagerInterface $entityManager,
        UpdateAd $updateAd
    )
    {
        $advert = $entityManager->getRepository(Advert::class)->find($id);
        if($advert){
            $data = json_decode($request->getContent(), true);
            if(isset($data['category'])) {
                $categoryId = $data['category'];
                $category = $entityManager->getRepository(Category::class)->find($categoryId);
                if ($category) return $this->json(['error' => "Categorie introuvable"]);
                $data['category'] = $category;
            }
            /** @var Advert $advert */
            $advert = $updateAd($advert, $data);
            if($advert){
                $entityManager->persist($advert);
                $entityManager->flush();
            }else{
                return $this->json(['warning' => 'Modification sur l\'annonce impossible'], 404);
            }

            return $this->json(['success' => 'Annonce modifiée'], 200);
        }else{
            return $this->json(['warning' => 'Annonce non trouvée'], 404);
        }

    }

    /**
     * @Route("/adverts/{id}", name="advert_delete", methods={"DELETE"})
     * @param string $id
     * @return Response
     */
    public function deleteAdvertsAction(
        string $id,
        EntityManagerInterface $entityManager
    )
    {
        $advert = $entityManager->getRepository(Advert::class)->find($id);
        if($advert){
            $entityManager->remove($advert);
            $entityManager->flush();
            return $this->json(['success' => 'Annonce supprimée avec succès'], 200);
        }else{
            return $this->json(['warning' => 'Annonce non trouvée'], 404);
        }
    }
}