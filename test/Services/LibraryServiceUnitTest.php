<?php

use Ctct\Components\ResultSet;
use Ctct\Components\Library\File;
use Ctct\Components\Library\Folder;
use Ctct\Components\Library\FileUploadStatus;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Response;

class LibraryServiceUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client();
        $getFileStream = Stream::factory(JsonLoader::getLibraryFileJson());
        $getFilesStream = Stream::factory(JsonLoader::getLibraryFilesJson());
        $getFolderStream = Stream::factory(JsonLoader::getLibraryFolderJson());
        $getFoldersStream = Stream::factory(JsonLoader::getLibraryFoldersJson());
        $getFileUploadStream = Stream::factory(JsonLoader::getFileUploadStatusJson());
        $mock = new Mock([
            new Response(200, array(), $getFileStream),
            new Response(200, array(), $getFilesStream),
            new Response(200, array(), $getFolderStream),
            new Response(200, array(), $getFoldersStream),
            new Response(201, array("Id" => 1)),
            new Response(200, array(), $getFileUploadStream)
        ]);
        self::$client->getEmitter()->attach($mock);
    }

    public function testGetLibraryFile()
    {
        $response = self::$client->get('/');

        $file = File::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Library\File', $file);
        $this->assertEquals("IMG_0261.JPG", $file->name);
        $this->assertEquals("4", $file->id);
        $this->assertEquals("chocolates", $file->description);
        $this->assertEquals("Images", $file->folder);
        $this->assertEquals(600, $file->height);
        $this->assertEquals(800, $file->width);
        $this->assertEquals(68825, $file->size);
        $this->assertEquals("https://origin.ih.l1.constantcontact.com/fs115/1100310339939/img/4.jpg", $file->url);
        $this->assertEquals("MyComputer", $file->source);
        $this->assertEquals("Active", $file->status);

        $this->assertInstanceOf('Ctct\Components\Library\Thumbnail', $file->thumbnail);
        $this->assertEquals("https://origin.ih.l1.constantcontact.com/fs115/1100310339939/img/4.jpg", $file->thumbnail->url);
        $this->assertEquals(200, $file->thumbnail->width);
        $this->assertEquals(150, $file->thumbnail->height);

        $this->assertEquals("2013-06-19T11:36:43.000-04:00", $file->created_date);
        $this->assertEquals("2013-08-23T12:54:17.000-04:00", $file->modified_date);
        $this->assertEquals(null, $file->folder_id);
        $this->assertEquals(true, $file->is_image);
        $this->assertEquals("JPG", $file->type);
    }

    public function testGetLibraryFiles()
    {
        $response = self::$client->get('/')->json();

        $result = new ResultSet($response['results'], $response['meta']);
        $files = array();
        foreach ($result->results as $file) {
            $files[] = File::create($file);
        }

        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);
        $this->assertEquals('c023cmNlPUFsbCUiUmZzZXQ9MTgyIcV5cGU9QWxsJmxpbWl0PTE', $result->next);
        $this->assertInstanceOf('Ctct\Components\Library\File', $files[0]);
        $this->assertEquals("Test_card.png", $files[0]->name);
        $this->assertEquals("182", $files[0]->id);
        $this->assertEquals("descriptionssss", $files[0]->description);
        $this->assertEquals("Images", $files[0]->folder);
        $this->assertEquals(360, $files[0]->height);
        $this->assertEquals(640, $files[0]->width);
        $this->assertEquals(26271, $files[0]->size);
        $this->assertEquals("https://mlsvc01-prod.s3.amazonaws.com/489270ef001/d738eefc-8e8e-4a69-8e7d-8e7217d22a0a.png", $files[0]->url);
        $this->assertEquals("MyComputer", $files[0]->source);
        $this->assertEquals("Active", $files[0]->status);

        $this->assertInstanceOf('Ctct\Components\Library\Thumbnail', $files[0]->thumbnail);
        $this->assertEquals("https://mlsvc01-prod.s3.amazonaws.com/489270ef001/d738eefc-8e8e-4a69-8e7d-8e7217d22a0a.png", $files[0]->thumbnail->url);
        $this->assertEquals(112, $files[0]->thumbnail->height);
        $this->assertEquals(200, $files[0]->thumbnail->width);

        $this->assertEquals("2015-01-09T00:14:08.000-05:00", $files[0]->created_date);
        $this->assertEquals("2015-01-09T00:14:08.000-05:00", $files[0]->modified_date);
        $this->assertEquals(null, $files[0]->folder_id);
        $this->assertEquals(true, $files[0]->is_image);
        $this->assertEquals("PNG", $files[0]->file_type);
    }

    public function testGetLibraryFolder()
    {
        $response = self::$client->get('/');

        $folder = Folder::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Library\Folder', $folder);
        $this->assertEquals("-5", $folder->id);
        $this->assertEquals("Folder", $folder->name);
        $this->assertEquals(1, $folder->level);

        $childrenCount = 0;
        foreach ($folder->children as $child) {
            $this->assertInstanceOf('Ctct\Components\Library\Folder', $child);
            $childrenCount++;
        }
        $this->assertEquals(2, $childrenCount);
        $this->assertEquals("-7", $folder->children[0]->id);
        $this->assertEquals("SubFolder", $folder->children[0]->name);
        $this->assertEquals(2, $folder->children[0]->level);
        $this->assertEquals(null, $folder->children[0]->children);
        $this->assertEquals(0, $folder->children[0]->item_count);
        $this->assertEquals("-5", $folder->children[0]->parent_id);
        $this->assertEquals("2014-08-04T11:40:36.000-04:00", $folder->children[0]->modified_date);
        $this->assertEquals("2014-08-04T11:40:36.000-04:00", $folder->children[0]->created_date);

        $this->assertEquals(3, $folder->item_count);
        $this->assertEquals("2013-09-09T14:25:44.000-04:00", $folder->modified_date);
        $this->assertEquals("2013-09-09T14:25:44.000-04:00", $folder->created_date);
    }

    public function testGetLibraryFolders()
    {
        $response = self::$client->get('/')->json();

        $result = new ResultSet($response['results'], $response['meta']);
        $folders = array();
        foreach ($result->results as $folder) {
            $folders[] = Folder::create($folder);
        }

        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);
        $this->assertEquals("b2Zmc1V0PTzmbGltaJE9Mo", $result->next);
        $this->assertInstanceOf('Ctct\Components\Library\Folder', $folders[0]);
        $this->assertEquals("-5", $folders[0]->id);
        $this->assertEquals("Folder", $folders[0]->name);
        $this->assertEquals(1, $folders[0]->level);

        $childrenCount = 0;
        foreach ($folders[0]->children as $child) {
            $this->assertInstanceOf('Ctct\Components\Library\Folder', $child);
            $childrenCount++;
        }
        $this->assertEquals(2, $childrenCount);
        $this->assertEquals("-7", $folders[0]->children[0]->id);
        $this->assertEquals("SubFolder", $folders[0]->children[0]->name);
        $this->assertEquals(2, $folders[0]->children[0]->level);
        $this->assertEquals(null, $folders[0]->children[0]->children);
        $this->assertEquals(0, $folders[0]->children[0]->item_count);
        $this->assertEquals("-5", $folders[0]->children[0]->parent_id);
        $this->assertEquals("2014-08-04T11:40:36.000-04:00", $folders[0]->children[0]->modified_date);
        $this->assertEquals("2014-08-04T11:40:36.000-04:00", $folders[0]->children[0]->created_date);

        $this->assertEquals(3, $folders[0]->item_count);
        $this->assertEquals("2013-09-09T14:25:44.000-04:00", $folders[0]->modified_date);
        $this->assertEquals("2013-09-09T14:25:44.000-04:00", $folders[0]->created_date);
    }

    public function testUploadFile()
    {
        $response = self::$client->post('/');

        $id = $response->getHeader("Id");
        $code = $response->getStatusCode();
        $this->assertEquals("1", $id);
        $this->assertEquals(201, $code);
    }

    public function testGetFileUploadStatus()
    {
        $response = self::$client->get('/');

        $statuses = array();
        foreach ($response->json() as $result) {
            $statuses[] = FileUploadStatus::create($result);
        }

        $fileUploadStatus = $statuses[0];
        $this->assertEquals("9", $fileUploadStatus->file_id);
        $this->assertEquals("Active", $fileUploadStatus->description);
        $this->assertEquals("Active", $fileUploadStatus->status);
    }
}