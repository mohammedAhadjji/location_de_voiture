<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Reservation;
use DateTime;

class ApiControllerTest extends WebTestCase
{
    public function testMajEventCreate(): void
    {
        $client = static::createClient();

        // Préparer les données JSON de la requête
        $data = [
            'title' => 'Test Event',
            'start' => '2024-12-31T10:00:00',
            'end' => '2024-12-31T12:00:00',
            'description' => 'Test description',
            'backgroundColor' => '#ff0000',
            'borderColor' => '#ff0000',
            'textColor' => '#ffffff',
            'allDay' => false
        ];

        // Convertir les données en JSON
        $jsonData = json_encode($data);

        // Envoyer la requête PUT à l'API (ici en utilisant un ID inexistant)
        $client->request('PUT', '/api/999/edit', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        // Vérifier que la réponse est réussie (status code 201)
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifier que l'événement a bien été créé dans la base de données
        $em = self::getContainer()->get('doctrine')->getManager();
        $calendar = $em->getRepository(Reservation::class)->findOneBy(['title' => 'Test Event']);

        $this->assertNotNull($calendar);
        $this->assertEquals('Test Event', $calendar->getTitle());
    }

    public function testMajEventUpdate(): void
    {
        $client = static::createClient();

        // Créer d'abord un événement pour la mise à jour
        $em = self::getContainer()->get('doctrine')->getManager();
        $event = new Reservation();
        $event->setTitle('Old Event');
        $event->setStart(new DateTime('2024-12-30T10:00:00'));
        $event->setEnd(new DateTime('2024-12-30T12:00:00'));
        $em->persist($event);
        $em->flush();

        // Préparer les données JSON de la mise à jour
        $data = [
            'title' => 'Updated Event',
            'start' => '2024-12-31T11:00:00',
            'end' => '2024-12-31T13:00:00',
            'description' => 'Updated description',
            'backgroundColor' => '#00ff00',
            'borderColor' => '#00ff00',
            'textColor' => '#000000',
            'allDay' => false
        ];

        // Convertir les données en JSON
        $jsonData = json_encode($data);

        // Envoyer la requête PUT pour mettre à jour cet événement
        $client->request('PUT', '/api/' . $event->getId() . '/edit', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        // Vérifier que la réponse est réussie (status code 200)
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifier que l'événement a été mis à jour
        $updatedEvent = $em->getRepository(Reservation::class)->find($event->getId());
        $this->assertEquals('Updated Event', $updatedEvent->getTitle());
    }

    public function testMajEventIncompleteData(): void
    {
        $client = static::createClient();

        // Préparer des données incomplètes (manque un champ essentiel)
        $data = [
            'title' => 'Invalid Event', // titre seulement
            'start' => '2024-12-31T10:00:00',
        ];

        $jsonData = json_encode($data);

        // Envoyer la requête PUT
        $client->request('PUT', '/api/999/edit', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        // Vérifier que la réponse indique des données manquantes (404)
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
