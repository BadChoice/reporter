<?php

namespace Tests\Unit;

use BadChoice\Reports\DataTransformers\Transformers\Link;
use BadChoice\Reports\Exporters\Field\ExportField;
use PHPUnit\Framework\TestCase;

class LinkTransformTest extends TestCase
{

    /** @test */
    public function can_create_a_link_with_multiple_fields_replacements(){
        $link = (new Link)->parseLink(['name' => 'pepito', 'id' => 1, 'other' => 'other'], 'http://www.mynamelink.com/{id}/{name}');
        $this->assertEquals("http://www.mynamelink.com/1/pepito", $link);
    }
}
