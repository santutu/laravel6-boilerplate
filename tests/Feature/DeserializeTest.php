<?php

namespace Tests\Feature;

use Symfony\Component\Serializer\Annotation\SerializedName;

use Tests\TestCase;


class DeserializeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_deserialize_with_annotation()
    {
        $json = '{ "changed_name" : "john"}';
        $sample = \Serializer::deserialize($json, Person::class, 'json');
        $this->assertEquals("john", $sample->name);
    }
}

class Person
{
    /**
     * @var string
     * @SerializedName("changed_name")
     */
    public $name;

}
