<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Location;
use App\Entity\Brand;

class AjaxControllerTest extends WebTestCase
{
    public function testGetBrandByLocations(): void
    {
        $client = static::createClient();

        // Créer une entité Location dans la base de test
        $location = new Location();
        $location->setName('Test Location');

        $brand = new Brand();
        $brand->setName('Brand 1');
        $location->addBrand($brand);

        // Sauvegarder les entités dans la base de données
        $em = static::getContainer()->get('doctrine')->getManager();
        $em->persist($location);
        $em->persist($brand);
        $em->flush();

        // Effectuer la requête
        $client->request('GET', '/ajax/location/' . $location->getId() . '/brands');

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals('Brand 1', $data[0]['name']);
    }
}
