<?php

use BadChoice\Reports\Utils\QueryUrl;
use PHPUnit\Framework\TestCase as BaseTestCase;

class QueryUrlTest extends BaseTestCase
{
    /** @test */
    public function can_get_url_without_query()
    {
        $this->assertEquals('http://revoxef.works?', QueryUrl::addQueryToUrl('http://revoxef.works'));
    }
    /** @test */
    public function can_get_url_with_path_without_query()
    {
        $this->assertEquals('http://revoxef.works/helloworld?', QueryUrl::addQueryToUrl('http://revoxef.works/helloworld'));
    }

    /** @test */
    public function can_get_url_with_query()
    {
        $this->assertEquals('http://revoxef.works?query1=3', QueryUrl::addQueryToUrl('http://revoxef.works?query1=3'));
    }

    /** @test */
    public function can_get_url_without_query_adding_a_query()
    {
        $this->assertEquals('http://revoxef.works?query1=4&query3=4', QueryUrl::addQueryToUrl('http://revoxef.works', "query1=4&query3=4"));
    }

    /** @test */
    public function can_get_url_with_query_adding_a_query()
    {
        $this->assertEquals('http://revoxef.works?query1=3&query2=3&query3=4', QueryUrl::addQueryToUrl('http://revoxef.works?query1=3', "query2=3&query3=4"));
    }

    /** @test */
    public function can_get_url_with_query_adding_a_query_array()
    {
        $this->assertEquals('http://revoxef.works?query1=3&query2=3&query3=4', QueryUrl::addQueryToUrl('http://revoxef.works?query1=3', ["query2" => 3, "query3" => "4"]));
    }

    /** @test */
    public function can_get_url_with_query_adding_an_existing_query()
    {
        $this->assertEquals('http://revoxef.works?query1=4&query3=4', QueryUrl::addQueryToUrl('http://revoxef.works?query1=3', "query1=4&query3=4"));
    }
}
