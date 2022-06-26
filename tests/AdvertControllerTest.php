<?php


namespace App\Tests;

use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{

    private $httpClient;

    private $adverts;
    /**
     * @var object|null
     */
    private $categories;

    protected function setUp():void
    {
        $this->httpClient = static::createClient();
        $this->adverts = static::getContainer()->get(AdvertRepository::class);
        $this->categories = static::getContainer()->get(CategoryRepository::class);
    }

   public function testAddAdverts()
   {
       $category = $this->categories->findOneBy(['name' => 'Automobile']);
       $json =[
           "category"=> $category->getId(),
           "brand" => "Audi",
           "model" => "Q2",
           "title" => "Cambriolet Audi à vendre",
           "content" => "Cambriolet à vendre 8000€"
       ];

       $this->httpClient->jsonRequest('POST', '/adverts',$json);

       $advert = $this->adverts->findOneBy($json,['id'=>'DESC']);

       $this->assertSame('Cambriolet Audi à vendre',$advert->getTitle());
   }

   public function testUpdateAdverts()
   {
        $this->httpClient->jsonRequest('PUT','/adverts/1', ['title' => 'Nouveau titre']);
        $advert = $this->adverts->find(1);
        $this->assertSame('Nouveau titre', $advert->getTitle());
   }

   public function testGetAdverts(){
       $crawler = $this->httpClient->request('GET','/adverts', ['model' => 'rs4 avant']);
       $test = $this->httpClient->getResponse()->getContent();
       $this->assertJson($test);

       $this->assertSame('Rs4', json_decode($test,true)['ads'][0]['model']);
       $this->assertSame('Audi', json_decode($test,true)['ads'][0]['brand']);

       $crawler = $this->httpClient->request('GET','/adverts', ['model' => "Gran Turismo Série5"]);

       $this->assertJson($this->httpClient->getResponse()->getContent());

       $crawler = $this->httpClient->request('GET','/adverts', ['model' => "ds 3 crossback"]);

       $this->assertJson($this->httpClient->getResponse()->getContent());

       $crawler = $this->httpClient->request('GET','/adverts', ['model' => "CrossBack ds 3"]);

       $this->assertJson($this->httpClient->getResponse()->getContent());
    }

   public function testDeleteAdverts(){

       $advert = $this->adverts->findOneBy([],['id'=>'DESC'],1,0);

       $id = $advert->getId();
       $this->httpClient->request('DELETE','/adverts/'.$id);

       $advert = $this->adverts->find($id);

       $this->assertNull($advert);
   }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}