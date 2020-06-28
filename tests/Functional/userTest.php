<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Club;
use App\Entity\CodeActive;
use App\Entity\User;

class userTest extends  ApiTestCase
{
    public function testGetCollectionClub(){
        $client = self::createClient();

        $client->request('GET','/api/users');
        $this->assertResponseStatusCodeSame(200);
    }


    public function testCreateClub(){
        $client = self::createClient();

        $client->request('POST','/api/clubs',
            ['json'=>[
                'name'=>'test',
                'city'=>'test'
            ]
            ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context'=>'/api/contexts/Club',
            '@type'=>'Club',
            'name'=>'test',
            'city'=>'test'
        ]);

        $this->assertMatchesResourceItemJsonSchema(Club::class);

    }


    public function testCreateCorrectUser(){
        $client = self::createClient();
        $iriClub = $this->findIriBy(Club::class,['name'=>'test']);
        $client->request('POST','/api/users',
            ['json'=>[
                'email'=>'example@example.com',
                'password'=>'asdasd',
                'firstname'=>'aaa',
                'lastname'=>'bbb',
                'club'=> $iriClub
            ]
            ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context'=>'/api/contexts/User',
            '@type'=>'User',
            'email'=> 'example@example.com',
            'roles'=> ["ROLE_PLAYER"],
            'firstname'=>'aaa',
            'lastname'=>'bbb',
            'statusAccaunt'=>false,
            'statusPlayer'=>false
        ]);
        $this->assertMatchesResourceItemJsonSchema(User::class);

    }
    public function testCreateWrongUser(){
        $client = self::createClient();
        $iriClub = $this->findIriBy(Club::class,['name'=>'test']);
        $client->request('POST','/api/users',
            ['json'=>[
                'email'=>'example@example.com',
                'password'=>'asdasd',
                'firstname'=>'aaa',
                'lastname'=>'bbb',
                'club'=> $iriClub
            ]
            ]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            '@context'=>'/api/contexts/ConstraintViolationList',
            '@type'=>'ConstraintViolationList',
            'hydra:description'=>'email: This value is already used.',
            ]);
        $this->assertMatchesResourceItemJsonSchema(User::class);

    }
    public function testErrorsFirstNameAndLastNameUser(){
        $client = self::createClient();
        $iriClub = $this->findIriBy(Club::class,['name'=>'test']);
        $client->request('POST','/api/users',
            ['json'=>[
                'email'=>'example1@example.com',
                'password'=>'aasdasd',
                'firstname'=>'a',
                'lastname'=>'b',
                'club'=> $iriClub
            ]
            ]);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            '@context'=>'/api/contexts/ConstraintViolationList',
            '@type'=>'ConstraintViolationList',
            'hydra:description'=>"firstname: Min length firstname is 3 chars\nlastname: Min length lastname is 3 chars",
        ]);
        $this->assertMatchesResourceItemJsonSchema(User::class);

    }
    public function testUpdateUser(){
        $client = self::createClient();

        $iri = $this->findIriBy(User::class,['email'=>'example@example.com']);

        $client->request('PATCH',$iri,[
            'headers' =>['Content-Type' => 'application/merge-patch+json'],
            'json'=>['firstname'=>'zzz',]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id'=>$iri,
            'firstname'=>'zzz'
        ]);

    }

    public function testDeleteUser(){
        $client = self::createClient();
        $iri = $this->findIriBy(User::class,['email'=>'example@example.com']);
        $client->request('DELETE',$iri);

        $this->assertResponseStatusCodeSame(204);

        $this->assertNull(
            static::$container->get('doctrine')->getRepository(User::class)->findOneBy(['email'=>'example@example.com'])
        );
    }
    public function testDeleteClub(){
        $client = self::createClient();
        $iri = $this->findIriBy(Club::class,['name'=>'test']);
        $client->request('DELETE',$iri);

        $this->assertResponseStatusCodeSame(204);

        $this->assertNull(
            static::$container->get('doctrine')->getRepository(Club::class)->findOneBy(['name'=>'test'])
        );
    }


}