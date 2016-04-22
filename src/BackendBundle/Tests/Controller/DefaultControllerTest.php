<?php


namespace BackendBundle\Tests\Controller;

use BackendBundle\Service\BBCodeGenerator;
use BackendBundle\Tests\BaseTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultControllerTest extends BaseTest
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Upload a file")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Drop files here")')->count());
    }

    public function testIndexPost404()
    {
        $client = static::createClient();
        $client->request('POST', '/');
        $this->assertSame(405, $client->getResponse()->getStatusCode());
    }

    public function testViewNonExistent404()
    {
        $client = static::createClient();
        $client->request('POST', '/view/non-existent');
        $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteNonExistent404()
    {
        $client = static::createClient();
        $client->request('POST', '/delete/non-existent');
        $this->assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function filePathsProvider()
    {
        return [
            [__DIR__."/../Fixtures/symfony_logo.png"],
            [__DIR__."/../Fixtures/symfony_logo.gif"],
            [__DIR__."/../Fixtures/symfony_logo.jpg"],
        ];
    }

    /**
     * @dataProvider filePathsProvider
     */
    public function testNormalCycle($path)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'test-image-');
        copy($path, $tmpFile);

        // upload
        $files = [
            new UploadedFile($tmpFile, pathinfo($path, PATHINFO_BASENAME))
        ];

        $client = static::createClient();
        $client->request('POST', '/_uploader/images/upload', [], $files);

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertTrue(is_array($content));
        $this->assertArrayHasKey('success', $content);
        $this->assertArrayHasKey('result', $content);
        $this->assertTrue($content['success']);
        $result = $content['result'];
        $this->assertArrayHasKey('id', $result['original']);
        $this->assertArrayHasKey('originalId', $result['original']);
        $this->assertArrayHasKey('url', $result['original']);
        $this->assertArrayHasKey('viewUrl', $result['original']);
        $this->assertArrayHasKey('deleteUrl', $result['original']);
        $this->assertArrayHasKey('deleteKey', $result['original']);

        $this->assertIsUuid($result['original']['id']);
        $this->assertIsUuid($result['original']['originalId']);
        $this->assertSame($result['original']['id'], $result['original']['originalId']);
        $this->assertSame([], $result['variants']);


        // view
        $img = $result['original'];
        $crawler = $client->request('GET', $img['viewUrl']);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter("img[src='".$img['url']."']")->count());
        $this->assertSame(1, $crawler->filter("input[value='".$img['viewUrl']."']")->count());
        $this->assertSame(1, $crawler->filter("input[value='".$img['url']."']")->count());

        /** @var BBCodeGenerator $bbcg */
        $bbcg = $client->getContainer()->get('backend.bbcode_generator');
        $bbCode = $bbcg->url($img['viewUrl'], $bbcg->img($img['url']));
        $this->assertSame(1, $crawler->filter("input[value='".$bbCode."']")->count());

        $html = '<a href="'.$img['viewUrl'].'"><img src="'.$img['url'].'" /></a>';
        $twig = $client->getContainer()->get('twig');
        /** @var \Twig_SimpleFilter $filter */
        $filter   = $twig->getFilter('escape');
        $callable = $filter->getCallable();
        $html     = $callable($twig, $html, 'html_attr');
        $html     = '<input type="text" value="'.$html.'" class="form-control" readonly>';
        $this->assertNotFalse(strpos($client->getResponse()->getContent(), $html));
        $this->assertFalse(strpos($client->getResponse()->getContent(), $img['deleteKey']));

        // delete page
        $client->request('GET', '/delete');
        $this->assertSame(404, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', $img['deleteUrl']);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter("form[method=post]")->count());
        $this->assertSame(1, $crawler->filter("input[name='form[key]']")->count());
        $this->assertSame('Delete image', $crawler->filter('button[type=submit]')->text());

        // invalid key
        $crawler = $client->request('GET', $img['deleteUrl']);
        $btn     = $crawler->selectButton('Delete image');
        $crawler = $client->submit($btn->form([
            'form[key]' => 'invalid-key',
        ]));
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Invalid key")')->count());

        // deletion
        $btn = $crawler->selectButton('Delete image');
        $client->submit($btn->form([
            'form[key]' => $img['deleteKey'],
        ]));
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/', $client->getResponse()->headers->get('Location'));
    }

    public function testNotImage()
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'test-image-');
        file_put_contents($tmpFile, 'random-content');

        // upload
        $files = [
            new UploadedFile($tmpFile, $tmpFile),
        ];

        $client = static::createClient();
        $client->request('POST', '/_uploader/images/upload', [], $files);

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $result = json_decode($response->getContent(), true);
        $this->assertFalse($result['success']);
        $this->assertSame('This file type is not allowed.', $result['error']);
    }
}

/*

"{"result":{"original":{"id":"421a738f-5e54-46a7-b07f-03d609c54b97","originalId":"421a738f-5e54-46a7-b07f-03d609c54b97","url":"http:\/\/192.168.99.100:8880\/22\/43822\/421\/a73\/421a738f-5e54-46a7-b07f-03d609c54b97.png","viewUrl":"http:\/\/localhost\/view\/421a738f-5e54-46a7-b07f-03d609c54b97","deleteUrl":"http:\/\/localhost\/delete\/421a738f-5e54-46a7-b07f-03d609c54b97","deleteKey":"94af27ba-5f56-4481-87f5-f089019084a7"},"variants":[]},"success":true}"

*/