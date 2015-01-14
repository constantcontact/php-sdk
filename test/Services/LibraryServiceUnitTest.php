<?php

use Ctct\Services\LibraryService;
use Ctct\Util\CurlResponse;

class LibraryServiceUnitTest extends PHPUnit_Framework_TestCase
{
    private $restClient;
    private $libraryService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->libraryService = new LibraryService("apikey", $this->restClient);
    }

    public function testGetLibraryFile()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getLibraryFileJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->libraryService->getLibraryFile("accessToken", "4");

        $this->assertInstanceOf('Ctct\Components\Library\File', $response);
        $this->assertEquals("IMG_0261.JPG", $response->name);
        $this->assertEquals("4", $response->id);
        $this->assertEquals("chocolates", $response->description);
        $this->assertEquals("Images", $response->folder);
        $this->assertEquals(600, $response->height);
        $this->assertEquals(800, $response->width);
        $this->assertEquals(68825, $response->size);
        $this->assertEquals("https://origin.ih.l1.constantcontact.com/fs115/1100310339939/img/4.jpg", $response->url);
        $this->assertEquals("MyComputer", $response->source);
        $this->assertEquals("Active", $response->status);

        $this->assertInstanceOf('Ctct\Components\Library\Thumbnail', $response->thumbnail);
        $this->assertEquals("https://origin.ih.l1.constantcontact.com/fs115/1100310339939/img/4.jpg", $response->thumbnail->url);
        $this->assertEquals(200, $response->thumbnail->width);
        $this->assertEquals(150, $response->thumbnail->height);

        $this->assertEquals("2013-06-19T11:36:43.000-04:00", $response->created_date);
        $this->assertEquals("2013-08-23T12:54:17.000-04:00", $response->modified_date);
        $this->assertEquals(null, $response->folder_id);
        $this->assertEquals(true, $response->is_image);
        $this->assertEquals("JPG", $response->type);
    }
}