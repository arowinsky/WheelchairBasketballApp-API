<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Club;

class clubTest extends ApiTestCase
{
    public function testGetCollectionClub(){
        $client = self::createClient();

        $client->request('GET','/api/clubs');
        $this->assertResponseStatusCodeSame(200);
    }
    public function testCreateClub(){
        $client = self::createClient();

        $client->request('POST','/api/clubs',
            ['json'=>[
                'name'=>'Club1',
                'city'=>'Poznań'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context'=>'/api/contexts/Club',
            '@type'=>'Club',
            'name'=>'Club1',
            'city'=>'Poznań'
        ]);
        
        $this->assertMatchesResourceItemJsonSchema(Club::class);

    }

    public function testUpdateClub(){
        $client = self::createClient();

        $iri = $this->findIriBy(Club::class,['name'=>'club1']);

        $client->request('PATCH',$iri,[
            'headers' =>['Content-Type' => 'application/merge-patch+json'],
            'json'=>['name'=>'Club1234',]
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['@id'=>$iri,
            'name'=>'Club1234',
            'city'=>'Poznań'
            ]);

    }

    public function testDeleteClub(){
        $client = self::createClient();
        $iri = $this->findIriBy(Club::class,['name'=>'club1234']);
        $client->request('DELETE',$iri);

        $this->assertResponseStatusCodeSame(204);

        $this->assertNull(
            static::$container->get('doctrine')->getRepository(Club::class)->findOneBy(['name'=>'club1'])
        );
    }


}