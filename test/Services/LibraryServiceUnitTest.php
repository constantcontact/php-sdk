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

    public function testGetFile()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getLibraryFileJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->libraryService->getLibraryFile("accessToken", array());

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
        $this->assertInstanceOf('Ctct\Components\Library\Thumbnail', $response->thumbnail);
        $this->assertEquals("JPG", $response->file_type);
    }
}
