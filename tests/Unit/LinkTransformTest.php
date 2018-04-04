<?php

namespace Tests\Unit;

use BadChoice\Reports\DataTransformers\Transformers\Link;
use BadChoice\Reports\Exporters\Field\ExportField;
use PHPUnit\Framework\TestCase;

class LinkTransformTest extends TestCase
{
    /** @test */
    public function can_create_a_link_with_multiple_fields_replacements()
    {
        $link = (new Link)->setRow(['name' => 'pepito', 'id' => 1, 'other' => 'other'])->parseLink('http://www.mynamelink.com/{id}/{name}');
        $this->assertEquals("http://www.mynamelink.com/1/pepito", $link);
    }

    /** @test */
    public function can_create_a_link_with_objects_replacement_using_dots()
    {
        $link = (new Link)->setRow(['person' => ['name' => 'pepito']])->parseLink('http://www.mynamelink.com/{person.name}');

        $this->assertEquals("http://www.mynamelink.com/pepito", $link);
    }

    /** @test */
    public function can_create_a_link_with_dots_replaced_by_value()
    {
        $link = (new Link)->setRow(['name' => 'pepito', 'id' => 1, 'other' => 'other'], "john")->parseLink('http://www.mynamelink.com/{user.name}');
        $this->assertEquals("http://www.mynamelink.com/john", $link);
    }

    /** @test */
    public function can_create_a_link_with_multiple_fields_replacements_and_with_optional_values_both_matching(){
        $link = (new Link)->setRow(['name' => 'pepito', 'id' => 1, 'existingOption1' => 'cool', 'existingOption2' => 'hot', 'other' => 'other'])->parseLink('http://www.mynamelink.com/{id}/{name}/{existingOption1||existingOption2}');
        $this->assertEquals("http://www.mynamelink.com/1/pepito/cool", $link);
    }

    /** @test */
    public function can_create_a_link_with_multiple_fields_replacements_and_with_optional_values_first_matching(){
        $link = (new Link)->setRow(['name' => 'pepito', 'id' => 1, 'existingOption' => 'hot', 'other' => 'other'])->parseLink('http://www.mynamelink.com/{id}/{name}/{existingOption||unexistingOption}');
        $this->assertEquals("http://www.mynamelink.com/1/pepito/hot", $link);
    }

    /** @test */
    public function can_create_a_link_with_multiple_fields_replacements_and_with_optional_values_second_matching(){
        $link = (new Link)->setRow(['name' => 'pepito', 'id' => 1, 'existingOption' => 'cool', 'other' => 'other'])->parseLink('http://www.mynamelink.com/{id}/{name}/{unexistingOption||existingOption}');
        $this->assertEquals("http://www.mynamelink.com/1/pepito/cool", $link);
    }
}
