<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $client->submitForm('Valider', [
            'register_user[email]' => 'thierry@exemple.fr',
            'register_user[plainPassword][first]' => 'treizecaracteres',
            'register_user[plainPassword][second]' => 'treizecaracteres',
            'register_user[firstname]' => 'Thierry',
            'register_user[lastname]' => 'Leung'
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Votre compte est correctement créé, veuillez vous connecter")');

    }
}
